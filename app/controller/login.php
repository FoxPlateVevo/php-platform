<?php
require_once __PATH__ . "/app/service/User.php";

/*
 * Set service and resource data
 */
$user = new Service_User();
$usersResource = $user->users;
$userProfilesResource       = $user->userProfiles;
$userProfileDetailsResource = $user->userProfileDetails;

$this->respond("GET", "/?", function ($request, $response, $service) {
    /*
     * Conditioning if user session exists
     */
    $user = get_user_session();
    
    if($user){
        $response
        ->redirect("/login/forwarder")
        ->send();
    }
    
    //header params
    $service->pageTitle = "Login";
        
    //render
    $service->render(__PATH__ . "/app/view/login/main.phtml");
});

$this->respond("GET", "/forwarder", function ($request, $response, $service) {
    /*
     * Conditioning access and redirect
     */
    $user = get_user_session();
    
    $destinty = get_destiny_login($user->userProfiles);
    
    if($destinty === "educative"){
        $response->redirect("/desktop");
    }else if($destinty === "administration"){
        $response->redirect("/project");
    }else if($destinty === "both"){
        $response->redirect("/login/select-module");
    }
});

$this->respond("GET", "/signout", function ($request, $response, $service) {
    /*
     * Destroy session
     */
    session_destroy();
    
    $response->redirect("/login");
});

$this->respond("GET", "/select-module", function ($request, $response, $service) {
    $service->render(__PATH__ . "/app/view/login/select-module.phtml");
});

$this->respond("GET", "/change-administration-user-profile/[:userProfile]", function ($request, $response, $service) {
    $userProfile = $request->param("userProfile");
    
    /*
     * Change user administration profile
     */
    $user = get_user_session();
    
    $user->activeAdministrationUserProfile = $userProfile;
    
    $_SESSION["user"] = serialize($user);
    
    $service->back();
});

$this->respond("POST", "/?", function ($request, $response, $service) 
use ($usersResource, $userProfileDetailsResource, $userProfilesResource) {
    $email =    $request->param("user-email");
    $password = $request->param("user-password");
    
    $users = $usersResource->listUsers([
        "email"     => $email,
        "password"  => $password
    ]);
    
    $user = array_pop($users);
//    $user = new User();
    
    if($user){
        /*
         * Load access
         */
        $userProfileDetails = $userProfileDetailsResource->listUserProfileDetails([
            "userId" => $user->getUserId()
        ]);
        
        $userProfileIds = array_map(function(UserProfileDetail $userProfileDetail){
            return $userProfileDetail->getUserProfileId();
        }, $userProfileDetails);
        
        $userProfiles = $userProfilesResource->listUserProfiles([
            "userProfileId" => $userProfileIds
        ]);
        
        $userProfileNames = array_map(function(UserProfile $userProfile){
            return $userProfile->getName();
        }, $userProfiles);
        
        $userProfileNames = array_values($userProfileNames);
        
        $user->userProfiles = $userProfileNames;
        
        $destinty = get_destiny_login($userProfileNames);
    
        if($destinty === "educative"){
            $user->activeEducativeUserProfile = $userProfileNames[0];
        }else if($destinty === "administration"){
            $user->activeAdministrationUserProfile = $userProfileNames[0];
        }else if($destinty === "both"){
            $educativeProfiles = [
                UserProfile::TYPE_PROFESSOR,
                UserProfile::TYPE_STUDENT,
            ];
            
            //for active educative profile
            foreach($userProfileNames as $userProfileName){
                if(in_array($userProfileName, $educativeProfiles)){
                    $user->activeEducativeUserProfile = $userProfileName;
                    break;
                }
            }
            
            //for active administration profile
            foreach($userProfileNames as $userProfileName){
                if(!in_array($userProfileName, $educativeProfiles)){
                    $user->activeAdministrationUserProfile = $userProfileName;
                    break;
                }
            }
        }
        
        $_SESSION["user"] = serialize($user);
    }
    
    $response->json(isset($user));
});

