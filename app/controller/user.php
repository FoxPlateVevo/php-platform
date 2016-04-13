<?php
require_once __PATH__ . "/app/service/User.php";
require_once __PATH__ . "/app/service/Localization.php";

/*
 * Set service and resource data
 */
$user                       = new Service_User();
$usersResource              = $user->users;
$userProfilesResource       = $user->userProfiles;
$userProfileDetailsResource = $user->userProfileDetails;

$localization           = new Service_Localization();
$countriesResource      = $localization->countries;
$departamentsResource   = $localization->departaments;
$provincesResource      = $localization->provinces;
$districtsResource      = $localization->districts;

$this->respond("GET", "/?", function ($request, $response, $service) use ($usersResource) {
    /*
     * List all users 
     */
    $users = $usersResource->listUsers();
    
    //header params
    $service->pageTitle = "Mis Usuarios";
    
    //content params
    $service->users = $users;
    
    //render
    $service->render(__PATH__ . "/app/view/user/list.phtml");
});

$this->respond("GET", "/create", function ($request, $response, $service) use ($countriesResource) {
    //header params
    $service->pageTitle = "Crear Usuario";
    
    //content params
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/user/create.phtml");
});

$this->respond("POST", "/create", function ($request, $response, $service) use ($usersResource) {
    $dni                = $request->param("user-dni");
    $name               = $request->param("user-name");
    $lastName           = $request->param("user-lastname");
    $motherLastName     = $request->param("user-mother-lastname");
    $birthDate          = $request->param("user-birth-date");
    $civilStatus        = $request->param("user-civil-status");
    $employmentStatus   = $request->param("user-employment-status");
    
    $email              = $request->param("user-email");
    $website            = $request->param("user-website");
    $ditrictId          = $request->param("user-district-id");
    $address            = $request->param("user-address");
    $cellphone          = $request->param("user-cellphone");
    $phone              = $request->param("user-phone");
    
    $insertedUserId = $usersResource->insert(new User([
        "personType"    => User::PERSON_TYPE_NATURAL,
        "registryDate"  => get_datetime(),
        "email"         => $email,
        "website"       => $website,
        "sellerUserId"  => null,
        "ditrictId"     => $ditrictId,
        "address"       => $address,
        "cellphone"     => $cellphone,
        "phone"         => $phone,
        "password"      => "pass123",
        "status"        => User::STATUS_ACTIVATED,
        "directory"     => null
    ]), new NaturalPerson([
        "dni"               => $dni,
        "name"              => $name,
        "lastName"          => $lastName,
        "motherLastName"    => $motherLastName,
        "birthDate"         => $birthDate,
        "civilStatus"       => $civilStatus,
        "employmentStatus"  => $employmentStatus,
        "studyGrade"        => null
    ]));
    
    $response->redirect("/user");
});

$this->respond("GET", "/[i:id]", function ($request, $response, $service) 
use ($usersResource, $countriesResource, $departamentsResource, $provincesResource, $districtsResource) {
    $userId = $request->param("id");
    
    /*
     * Get user
     */
    $user               = $usersResource->get($userId);
    $userDistrict       = $districtsResource->get($user->getDitrictId());
    $userProvince       = $provincesResource->get($userDistrict->getProvinceId());
    $userDepartament    = $departamentsResource->get($userProvince->getDepartamentId());
    
    //header params
    $service->pageTitle = "Ver Usuario";
    
    //content params
    $service->user              = $user;
    $service->userDistrict      = $userDistrict;
    $service->userProvince      = $userProvince;
    $service->userDepartament   = $userDepartament;
    $service->districts         = $districtsResource->listDistricts([
        "provinceId" => $userProvince->getId()
    ]);
    $service->provinces         = $provincesResource->listProvinces([
        "departamentId" => $userDepartament->getId()
    ]);
    $service->departaments      = $departamentsResource->listDepartaments([
        "countryId" => $userDepartament->getCountryId()
    ]);
    $service->countries         = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/user/edit.phtml");
});

$this->respond("POST", "/[i:id]", function ($request, $response, $service) use ($usersResource) {
    $userId = $request->param("id");
    $dni                = $request->param("user-dni");
    $name               = $request->param("user-name");
    $lastName           = $request->param("user-lastname");
    $motherLastName     = $request->param("user-mother-lastname");
    $birthDate          = $request->param("user-birth-date");
    $civilStatus        = $request->param("user-civil-status");
    $employmentStatus   = $request->param("user-employment-status");
    
    $email              = $request->param("user-email");
    $website            = $request->param("user-website");
    $ditrictId          = $request->param("user-district-id");
    $address            = $request->param("user-address");
    $cellphone          = $request->param("user-cellphone");
    $phone              = $request->param("user-phone");
    
    $user = $usersResource->get($userId);
//    $user = new User();
    $user->setEmail($email);
    $user->setWebsite($website);
    $user->setDitrictId($ditrictId);
    $user->setAddress($address);
    $user->setCellphone($cellphone);
    $user->setPhone($phone);
    
    $person = $user->person;
//    $person = new NaturalPerson();
    $person->setDni($dni);
    $person->setName($name);
    $person->setLastName($lastName);
    $person->setMotherLastName($motherLastName);
    $person->setBirthDate($birthDate);
    $person->setCivilStatus($civilStatus);
    $person->setEmploymentStatus($employmentStatus);
    
    $success = $usersResource->update($user, $person);
    
    $response->redirect("/user/{$userId}");
});

$this->respond("GET", "/[i:id]/profile-list", function ($request, $response, $service)
use ($usersResource, $userProfilesResource, $userProfileDetailsResource) {
    $userId = $request->param("id");
//    __class(new UserProfile);
    /*
     * Get user
     */
    $user               = $usersResource->get($userId);
//    $user = new User;
    
    //header params
    $service->pageTitle = "Perfiles de Usuario";
    
    //content params
    $service->user                  = $user;
    $service->userProfiles          = $userProfilesResource->listUserProfiles();
    $service->userProfileDetails    = $userProfileDetailsResource->listUserProfileDetails([
        "userId" => $user->getUserId()
    ]);
    
    //render
    $service->render(__PATH__ . "/app/view/user/edit.profile-list.phtml");
});

$this->respond("POST", "/[i:id]/profile-list", 
function ($request, $response, $service) 
use ($usersResource, $userProfileDetailsResource) {
    $userId         = $request->param("id");
    $userProfileIds = $request->param("userProfileIds");
    
    /*
     * Get user
     */
    $user = $usersResource->get($userId);
    $userProfileDetails    = $userProfileDetailsResource->listUserProfileDetails([
        "userId" => $user->getUserId()
    ]);
    
    /*
     * Verify
     */
    $currentUserProfileIds = array_map(function(UserProfileDetail $userProfileDetail){
        return $userProfileDetail->getUserProfileId();
    }, $userProfileDetails);
    
    foreach ($currentUserProfileIds as $userProfileId){
        if(!in_array($userProfileId, $userProfileIds)){
            $userProfileDetailsResource->delete(new UserProfileDetail([
                "userId"        => $user->getUserId(),
                "userProfileId" => $userProfileId
            ]));
        }
    }
    
    foreach ($userProfileIds as $userProfileId){
        if(!in_array($userProfileId, $currentUserProfileIds)){
            $userProfileDetailsResource->insert(new UserProfileDetail([
                "userId"        => $user->getUserId(),
                "userProfileId" => $userProfileId
            ]));
        }
    }
    
    $response->json([
        "success"       => true
    ]);
});

/* Searchers */
$this->respond("GET", "/students-searcher/?[:filterUserIds]?", function ($request, $response, $service)
use ($usersResource) {
    $filterUserIds = $request->param("filterUserIds");
    
    /*
     * Get users
     */
    $users  = $usersResource->listUsers([
        "UserProfileType" => UserProfile::TYPE_STUDENT
    ]);
    
    //header params
    $service->pageTitle = "Buscar Usuario";
    
    //content params
    $service->users         = $users;
    $service->filterUserIds = ($filterUserIds)? explode(",", $filterUserIds) : null;
    
    //render
    $service->layout(__PATH__ . "/app/view/layouts/default.to-search.phtml");
    $service->render(__PATH__ . "/app/view/user/students-searcher.phtml");
});

$this->respond("GET", "/vendors-searcher/?[:filterUserIds]?", function ($request, $response, $service)
use ($usersResource) {
    $filterUserIds = $request->param("filterUserIds");
    
    /*
     * Get users
     */
    $users  = $usersResource->listUsers([
        "UserProfileType" => UserProfile::TYPE_VENDOR
    ]);
    
    //header params
    $service->pageTitle = "Buscar Vendedor";
    
    //content params
    $service->users         = $users;
    $service->filterUserIds = ($filterUserIds)? explode(",", $filterUserIds) : null;
    
    //render
    $service->layout(__PATH__ . "/app/view/layouts/default.to-search.phtml");
    $service->render(__PATH__ . "/app/view/user/vendors-searcher.phtml");
});

$this->respond("GET", "/clients-searcher/?[:filterUserIds]?", function ($request, $response, $service)
use ($usersResource) {
    $filterUserIds = $request->param("filterUserIds");
    
    /*
     * Get users
     */
    $users  = $usersResource->listUsers([
        "UserProfileType" => [UserProfile::TYPE_CLIENT, UserProfile::TYPE_STUDENT]
    ]);
    
    //header params
    $service->pageTitle = "Buscar Cliente";
    
    //content params
    $service->users         = $users;
    $service->filterUserIds = ($filterUserIds)? explode(",", $filterUserIds) : null;
    
    //render
    $service->layout(__PATH__ . "/app/view/layouts/default.to-search.phtml");
    $service->render(__PATH__ . "/app/view/user/clients-searcher.phtml");
});