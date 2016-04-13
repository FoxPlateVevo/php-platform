<?php
require_once __PATH__ . "/app/service/Localization.php";

/*
 * Set service and resource data
 */
$localization = new Service_Localization();
$countriesResource = $localization->countries;
$departamentsResource = $localization->departaments;
$provincesResource = $localization->provinces;
$districtsResource = $localization->districts;
$headquartersResource = $localization->headquarters;

/*
 * Countries
 */
$this->respond("GET", "/country", function ($request, $response, $service) use ($countriesResource) {
    /*
     * List all countries 
     */
    $countries = $countriesResource->listCountries();
    
    //header params
    $service->pageTitle = "Países";
    
    //content params
    $service->countries = $countries;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/country.list.phtml");
});

$this->respond("GET", "/country/create", function ($request, $response, $service) {
    //header params
    $service->pageTitle = "Crear País";
    
    //render
    $service->render(__PATH__ . "/app/view/localization/country.create.phtml");
});

$this->respond("POST", "/country/create", function ($request, $response, $service) use ($countriesResource) {
    $name       = $request->param("country-name");
    $postalCode = $request->param("country-postal-code");
    
    $insertedCountryId = $countriesResource->insert(new Country([
        "name"         => strtoupper($name),
        "postalCode"   => $postalCode
    ]));
    
    $response->redirect("/localization/country");
});

$this->respond("GET", "/country/[i:id]", function ($request, $response, $service) use ($countriesResource) {
    $countryId  = $request->param("id");
    
    /*
     * Get country
     */
    $country = $countriesResource->get($countryId);
    
    //header params
    $service->pageTitle = "Ver País";
    
    //content params
    $service->country = $country;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/country.edit.phtml");
});

$this->respond("POST", "/country/[i:id]", function ($request, $response, $service) use ($countriesResource) {
    $countryId  = $request->param("id");
    $name       = $request->param("country-name");
    $postalCode = $request->param("country-postal-code");
    
    $success = $countriesResource->update(new Country([
        "id"            => $countryId,
        "name"          => strtoupper($name),
        "postalCode"    => $postalCode
    ]));
    
    $response->redirect("/localization/country/{$countryId}");
});

/*
 * Departaments
 */
$this->respond("GET", "/departament", function ($request, $response, $service) 
use ($countriesResource, $departamentsResource) {
    /*
     * List all countries 
     */
    $departaments = $departamentsResource->listDepartaments();
    
    $countryIds = array_map(function(Departament $departament){
        return $departament->getCountryId();
    }, $departaments);
    
    $countries = $countriesResource->listCountries([
        "id" => $countryIds
    ]);
    
    //header params
    $service->pageTitle = "Departamentos";
    
    //content params
    $service->departaments  = $departaments;
    $service->countries     = $countries;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/departament.list.phtml");
});

$this->respond("GET", "/departament/create", function ($request, $response, $service) use ($countriesResource) {
    //header params
    $service->pageTitle = "Crear Departamento";
    
    //content params
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/departament.create.phtml");
});

$this->respond("POST", "/departament/create", function ($request, $response, $service) use ($departamentsResource) {
    $name       = $request->param("departament-name");
    $countryId  = $request->param("departament-country");
    
    $insertedDepartamentId = $departamentsResource->insert(new Departament([
        "name"      => strtoupper($name),
        "countryId" => $countryId
    ]));
    
    $response->redirect("/localization/departament");
});

$this->respond("GET", "/departament/[i:id]", function ($request, $response, $service) use ($countriesResource, $departamentsResource) {
    $departamentId  = $request->param("id");
    
    /*
     * Get departament
     */
    $departament = $departamentsResource->get($departamentId);
    
    //header params
    $service->pageTitle = "Ver Departamento";
    
    //content params
    $service->departament = $departament;
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/departament.edit.phtml");
});

$this->respond("POST", "/departament/[i:id]", function ($request, $response, $service) use ($departamentsResource) {
    $departamentId  = $request->param("id");
    $name           = $request->param("departament-name");
    $countryId      = $request->param("departament-country");
    
    $success = $departamentsResource->update(new Departament([
        "id"        => $departamentId,
        "name"      => strtoupper($name),
        "countryId" => $countryId
    ]));
    
    $response->redirect("/localization/departament/{$departamentId}");
});

/*
 * Provinces
 */
$this->respond("GET", "/province", function ($request, $response, $service) 
use ($provincesResource, $departamentsResource, $countriesResource) {
    /*
     * List all provinces 
     */
    $provinces = $provincesResource->listProvinces();
    
    $departamentIds = array_map(function(Province $province){
        return $province->getDepartamentId();
    }, $provinces);
    
    $departaments = $departamentsResource->listDepartaments([
        "id" => $departamentIds
    ]);
    
    $countryIds = array_map(function(Departament $departament){
        return $departament->getCountryId();
    }, $departaments);
    
    $countries = $countriesResource->listCountries([
        "id" => $countryIds
    ]);
    
    //header params
    $service->pageTitle = "Provincias";
    
    //content params
    $service->provinces     = $provinces;
    $service->departaments  = $departaments;
    $service->countries     = $countries;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/province.list.phtml");
});

$this->respond("GET", "/province/create", function ($request, $response, $service) use ($countriesResource) {
    //header params
    $service->pageTitle = "Crear Provincia";
    
    //content params
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/province.create.phtml");
});

$this->respond("POST", "/province/create", function ($request, $response, $service) use ($provincesResource) {
    $name           = $request->param("province-name");
    $departamentId  = $request->param("province-departament");
    
    $insertedProvinceId = $provincesResource->insert(new Province([
        "name"          => strtoupper($name),
        "departamentId" => $departamentId
    ]));
    
    $response->redirect("/localization/province");
});

$this->respond("GET", "/province/[i:id]", function ($request, $response, $service) use ($countriesResource, $departamentsResource, $provincesResource) {
    $provinceId  = $request->param("id");
    
    /*
     * Get province
     */
    $province               = $provincesResource->get($provinceId);
    $provinceDepartament    = $departamentsResource->get($province->getDepartamentId());
    
    //header params
    $service->pageTitle = "Ver Provincia";
    
    //content params
    $service->province              = $province;
    $service->provinceDepartament   = $provinceDepartament;
    $service->departaments          = $departamentsResource->listDepartaments([
        "countryId" => $provinceDepartament->getCountryId()
    ]);
    $service->countries             = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/province.edit.phtml");
});

$this->respond("POST", "/province/[i:id]", function ($request, $response, $service) use ($provincesResource) {
    $provinceId     = $request->param("id");
    $name           = $request->param("province-name");
    $departamentId  = $request->param("province-departament");
    
    $success = $provincesResource->update(new Province([
        "id"            => $provinceId,
        "name"          => strtoupper($name),
        "departamentId" => $departamentId
    ]));
    
    $response->redirect("/localization/province/{$provinceId}");
});

/*
 * Districts
 */
$this->respond("GET", "/district", function ($request, $response, $service) 
use ($districtsResource, $provincesResource, $departamentsResource, $countriesResource) {
    /*
     * List all districts 
     */
    $districts = $districtsResource->listDistricts();
    
    $provinceIds = array_map(function(District $district){
        return $district->getProvinceId();
    }, $districts);
    
    $provinces = $provincesResource->listProvinces([
        "id" => $provinceIds
    ]);
    
    $departamentIds = array_map(function(Province $province){
        return $province->getDepartamentId();
    }, $provinces);
    
    $departaments = $departamentsResource->listDepartaments([
        "id" => $departamentIds
    ]);
    
    $countryIds = array_map(function(Departament $departament){
        return $departament->getCountryId();
    }, $departaments);
    
    $countries = $countriesResource->listCountries([
        "id" => $countryIds
    ]);
    
    //header params
    $service->pageTitle = "Distritos";
    
    //content params
    $service->districts     = $districts;
    $service->provinces     = $provinces;
    $service->departaments  = $departaments;
    $service->countries     = $countries;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/district.list.phtml");
});

$this->respond("GET", "/district/create", function ($request, $response, $service) use ($countriesResource) {
    //header params
    $service->pageTitle = "Crear Distrito";
    
    //content params
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/district.create.phtml");
});

$this->respond("POST", "/district/create", function ($request, $response, $service) use ($districtsResource) {
    $name       = $request->param("district-name");
    $provinceId = $request->param("district-province");
    
    $insertedDistrictId = $districtsResource->insert(new District([
        "name"          => strtoupper($name),
        "provinceId"    => $provinceId
    ]));
    
    $response->redirect("/localization/district");
});

$this->respond("GET", "/district/[i:id]", function ($request, $response, $service) use ($countriesResource, $departamentsResource, $provincesResource, $districtsResource) {
    $districtId  = $request->param("id");
    
    /*
     * Get district
     */
    $district               = $districtsResource->get($districtId);
    $districtProvince       = $provincesResource->get($district->getProvinceId());
    $districtDepartament    = $departamentsResource->get($districtProvince->getDepartamentId());
    
    //header params
    $service->pageTitle = "Ver Distrito";
    
    //content params
    $service->district              = $district;
    $service->districtProvince      = $districtProvince;
    $service->districtDepartament   = $districtDepartament;
    $service->provinces             = $provincesResource->listProvinces([
        "departamentId" => $districtProvince->getDepartamentId()
    ]);
    $service->departaments          = $departamentsResource->listDepartaments([
        "countryId" => $districtDepartament->getCountryId()
    ]);
    $service->countries             = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/district.edit.phtml");
});

$this->respond("POST", "/district/[i:id]", function ($request, $response, $service) use ($districtsResource) {
    $districtId     = $request->param("id");
    $name           = $request->param("district-name");
    $provinceId     = $request->param("district-province");
    
    $success = $districtsResource->update(new District([
        "id"            => $districtId,
        "name"          => strtoupper($name),
        "provinceId"    => $provinceId
    ]));
    
    $response->redirect("/localization/district/{$districtId}");
});

/*
 * Headquarters
 */
$this->respond("GET", "/headquarter", function ($request, $response, $service) 
use ($headquartersResource, $districtsResource, $provincesResource, $departamentsResource, $countriesResource) {
    /*
     * List all headquarters 
     */
    $headquarters = $headquartersResource->listHeadquarters();
    
    $districtIds = array_map(function(Headquarter $headquarter){
        return $headquarter->getDistrictId();
    }, $headquarters);
    
    $districts = $districtsResource->listDistricts([
        "id" => $districtIds
    ]);
    
    $provinceIds = array_map(function(District $district){
        return $district->getProvinceId();
    }, $districts);
    
    $provinces = $provincesResource->listProvinces([
        "id" => $provinceIds
    ]);
    
    $departamentIds = array_map(function(Province $province){
        return $province->getDepartamentId();
    }, $provinces);
    
    $departaments = $departamentsResource->listDepartaments([
        "id" => $departamentIds
    ]);
    
    $countryIds = array_map(function(Departament $departament){
        return $departament->getCountryId();
    }, $departaments);
    
    $countries = $countriesResource->listCountries([
        "id" => $countryIds
    ]);
    
    //header params
    $service->pageTitle = "Sedes";
    
    //content params
    $service->headquarters = $headquarters;
    $service->districts     = $districts;
    $service->provinces     = $provinces;
    $service->departaments  = $departaments;
    $service->countries     = $countries;
    
    //render
    $service->render(__PATH__ . "/app/view/localization/headquarter.list.phtml");
});

$this->respond("GET", "/headquarter/create", function ($request, $response, $service) use ($countriesResource) {
    //header params
    $service->pageTitle = "Crear Sede";
    
    //content params
    $service->countries = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/headquarter.create.phtml");
});

$this->respond("POST", "/headquarter/create", function ($request, $response, $service) use ($headquartersResource) {
    $districtId     = $request->param("headquarter-district-id");
    $description    = $request->param("headquarter-description");
    $address        = $request->param("headquarter-address");
    
    /*
     * Validators
     */
    $service->validate($districtId, "Se debe establecer un distrito para la sede")->isInt();
    $service->validate($description, "Se debe establecer una descripción para la sede")->notEmpty();
    $service->validate($address, "Se debe establecer una dirección para la sede")->notEmpty();
    
    $insertedHeadquarterId = $headquartersResource->insert(new Headquarter([
        "description"   => $description,
        "address"       => strtoupper($address),
        "districtId"    => $districtId
    ]));
    
    $response->redirect("/localization/headquarter");
});

$this->respond("GET", "/headquarter/[i:headquarterId]", 
function ($request, $response, $service) use ($headquartersResource, $countriesResource, $departamentsResource, $provincesResource, $districtsResource) {
    $headquarterId = $request->param("headquarterId");
    
    /*
     * Get headquarter
     */
    $headquarter                = $headquartersResource->get($headquarterId);
    $headquarterDistrict        = $districtsResource->get($headquarter->getDistrictId());
    $headquarterProvince        = $provincesResource->get($headquarterDistrict->getProvinceId());
    $headquarterDepartament     = $departamentsResource->get($headquarterProvince->getDepartamentId());
    
    //header params
    $service->pageTitle = "Ver Sede";
    
    //content params
    $service->headquarter               = $headquarter;
    $service->headquarterDistrict       = $headquarterDistrict;
    $service->headquarterProvince       = $headquarterProvince;
    $service->headquarterDepartament    = $headquarterDepartament;
    $service->districts                 = $districtsResource->listDistricts([
        "provinceId" => $headquarterProvince->getId()
    ]);
    $service->provinces                 = $provincesResource->listProvinces([
        "departamentId" => $headquarterDepartament->getId()
    ]);
    $service->departaments              = $departamentsResource->listDepartaments([
        "countryId" => $headquarterDepartament->getCountryId()
    ]);
    $service->countries                 = $countriesResource->listCountries();
    
    //render
    $service->render(__PATH__ . "/app/view/localization/headquarter.edit.phtml");
});

$this->respond("POST", "/headquarter/[i:headquarterId]", function ($request, $response, $service) use ($headquartersResource) {
    $headquarterId  = $request->param("headquarterId");
    $districtId     = $request->param("headquarter-district-id");
    $description    = $request->param("headquarter-description");
    $address        = $request->param("headquarter-address");
    
    /*
     * Validators
     */
    $service->validate($districtId, "Se debe establecer un distrito para la sede")->isInt();
    $service->validate($description, "Se debe establecer una descripción para la sede")->notEmpty();
    $service->validate($address, "Se debe establecer una dirección para la sede")->notEmpty();
    
    $success = $headquartersResource->update(new Headquarter([
        "headquarterId"     => $headquarterId,
        "description"       => $description,
        "address"           => $address,
        "districtId"        => $districtId
    ]));
    
    $response->redirect("/localization/headquarter/{$headquarterId}");
});


/*
 * JSON responses
 */
$this->respond("GET", "/json/departament-of-country", function ($request, $response, $service) use ($departamentsResource) {
    $countryId   = $request->param("country-id");
    
    $departaments  = $departamentsResource->listDepartaments([
        "countryId" => $countryId
    ]);
    
    $departamentsData = array_map(function(Departament $departament){
        return [
            "value" => $departament->getId(),
            "text"  => $departament->getName()
        ];
    }, $departaments);
    
    $response->json($departamentsData);
});

$this->respond("GET", "/json/province-of-departament", function ($request, $response, $service) use ($provincesResource) {
    $departamentId = $request->param("departament-id");
    
    $provinces = $provincesResource->listProvinces([
        "departamentId" => $departamentId
    ]);
    
    $provincesData = array_map(function(Province $province){
        return [
            "value" => $province->getId(),
            "text"  => $province->getName()
        ];
    }, $provinces);
    
    $response->json($provincesData);
});

$this->respond("GET", "/json/district-of-province", function ($request, $response, $service) use ($districtsResource) {
    $provinceId = $request->param("province-id");
    
    $districts = $districtsResource->listDistricts([
        "provinceId" => $provinceId
    ]);
    
    $districtsData = array_map(function(District $district){
        return [
            "value" => $district->getId(),
            "text"  => $district->getName()
        ];
    }, $districts);
    
    $response->json($districtsData);
});