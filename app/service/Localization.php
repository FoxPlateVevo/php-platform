<?php

require_once __PATH__ . "/app/model/Country.php";
require_once __PATH__ . "/app/model/Departament.php";
require_once __PATH__ . "/app/model/Province.php";
require_once __PATH__ . "/app/model/District.php";
require_once __PATH__ . "/app/model/Headquarter.php";

class Service_Localization {
    public $countries;
    public $departaments;
    public $provinces;
    public $districts;
    public $headquarters;
    
    public function __construct() {
        $this->countries    = new Service_Localization_Countries_Resource();
        $this->departaments = new Service_Localization_Departaments_Resource();
        $this->provinces    = new Service_Localization_Provinces_Resource();
        $this->districts    = new Service_Localization_Districts_Resource();
        $this->headquarters = new Service_Localization_Headquarters_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Localization_Countries_Resource {
    public function listCountries(array $optionParams = null) {
        /*
         * id           : optional String, Array
         */
        $alternateAttributes = [
            "id"        => "cod_pais"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_pais,
        nombre,
        codigo_postal
        FROM pais
        {$CONDITIONALS_PART_STRING}
        ";
        
        $countriesData = db::fetchAll($query);
        
        $countries = [];
        
        foreach ($countriesData as $countryData){
            $countries[$countryData->cod_pais] = new Country([
                "id"            => $countryData->cod_pais,
                "name"          => $countryData->nombre,
                "postalCode"    => $countryData->codigo_postal
            ]);
        }
        
        return $countries;
    }
    
    public function insert(Country $country){
        $insertedCountryId = db::insert("pais", [
          "nombre"          => $country->getName(),
          "codigo_postal"   => $country->getPostalCode()
        ]);
        
        return $insertedCountryId;
    }
    
    public function get($countryId){
        return array_pop($this->listCountries([
            "id" => $countryId
        ]));
    }
    
    public function update(Country $country){
        $affectedRows = db::update("pais", [
            "nombre"          => $country->getName(),
            "codigo_postal"   => $country->getPostalCode()
        ], [
            "cod_pais"        => $country->getId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($countryId){
        $affectedRows = db::delete("pais", [
            "cod_pais" => $countryId
        ]);
        
        return $affectedRows;
    }
}

class Service_Localization_Departaments_Resource {
    public function listDepartaments(array $optionParams = null) {
        /*
         * id           : optional String, Array
         * countryId    : optional String, Array
         */
        $alternateAttributes = [
            "id"        => "cod_departamento",
            "countryId" => "cod_pais"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_departamento,
        nombre,
        cod_pais
        FROM departamento
        {$CONDITIONALS_PART_STRING}
        ";
        
        $departamentsData = db::fetchAll($query);
        
        $departaments = [];
        
        foreach ($departamentsData as $departamentData){
            $departaments[$departamentData->cod_departamento] = new Departament([
                "id"            => $departamentData->cod_departamento,
                "name"          => $departamentData->nombre,
                "countryId"     => $departamentData->cod_pais
            ]);
        }
        
        return $departaments;
    }
    
    public function insert(Departament $departament){
        $insertedDepartamentId = db::insert("departamento", [
            "nombre"    => $departament->getName(),
            "cod_pais"  => $departament->getCountryId()
        ]);
        
        return $insertedDepartamentId;
    }
    
    public function get($departamentId){
        return array_pop($this->listDepartaments([
            "id" => $departamentId
        ]));
    }
    
    public function update(Departament $departament){
        $affectedRows = db::update("departamento", [
            "nombre"            => $departament->getName(),
            "cod_pais"          => $departament->getCountryId()
        ], [
            "cod_departamento"  => $departament->getId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($departamentId){
        $affectedRows = db::delete("departamento", [
            "cod_departamento" => $departamentId
        ]);
        
        return $affectedRows;
    }
}

class Service_Localization_Provinces_Resource {
    public function listProvinces(array $optionParams = null) {
        /*
         * id               : optional String, Array
         * departamentId    : optional String, Array
         */
        $alternateAttributes = [
            "id"            => "cod_provincia",
            "departamentId" => "cod_departamento"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_provincia,
        nombre,
        cod_departamento
        FROM provincia
        {$CONDITIONALS_PART_STRING}
        ";
        
        $provincesData = db::fetchAll($query);
        
        $provinces = [];
        
        foreach ($provincesData as $provinceData){
            $provinces[$provinceData->cod_provincia] = new Province([
                "id"            => $provinceData->cod_provincia,
                "name"          => $provinceData->nombre,
                "departamentId" => $provinceData->cod_departamento
            ]);
        }
        
        return $provinces;
    }
    
    public function insert(Province $province){
        $insertedProvinceId = db::insert("provincia", [
            "nombre"            => $province->getName(),
            "cod_departamento"  => $province->getDepartamentId()
        ]);
        
        return $insertedProvinceId;
    }
    
    public function get($provinceId){
        return array_pop($this->listProvinces([
            "id" => $provinceId
        ]));
    }
    
    public function update(Province $province){
        $affectedRows = db::update("provincia", [
            "nombre"                => $province->getName(),
            "cod_departamento"      => $province->getDepartamentId()
        ], [
            "cod_provincia"         => $province->getId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($provinceId){
        $affectedRows = db::delete("provincia", [
            "cod_provincia" => $provinceId
        ]);
        
        return $affectedRows;
    }
}

class Service_Localization_Districts_Resource {
    public function listDistricts(array $optionParams = null) {
        /*
         * id               : optional String, Array
         * provinceId       : optional String, Array
         */
        $alternateAttributes = [
            "id"            => "cod_distrito",
            "provinceId"    => "cod_provincia"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_distrito,
        nombre,
        cod_provincia
        FROM distrito
        {$CONDITIONALS_PART_STRING}
        ";
        
        $districtsData = db::fetchAll($query);
        
        $districts = [];
        
        foreach ($districtsData as $districtData){
            $districts[$districtData->cod_distrito] = new District([
                "id"            => $districtData->cod_distrito,
                "name"          => $districtData->nombre,
                "provinceId"    => $districtData->cod_provincia
            ]);
        }
        
        return $districts;
    }
    
    public function insert(District $district){
        $insertedDistrictId = db::insert("distrito", [
            "nombre"            => $district->getName(),
            "cod_provincia"     => $district->getProvinceId()
        ]);
        
        return $insertedDistrictId;
    }
    
    public function get($districtId){
        return array_pop($this->listDistricts([
            "id" => $districtId
        ]));
    }
    
    public function update(District $district){
        $affectedRows = db::update("distrito", [
            "nombre"            => $district->getName(),
            "cod_provincia"     => $district->getProvinceId()
        ], [
            "cod_distrito"     => $district->getId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($districtId){
        $affectedRows = db::delete("distrito", [
            "cod_distrito" => $districtId
        ]);
        
        return $affectedRows;
    }
}

class Service_Localization_Headquarters_Resource {
    public function listHeadquarters(array $optionParams = null){
        /*
         * headquarterId    : optional String, Array
         */
        $alternateAttributes = [
            "headquarterId"    => "S.cod_sede"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        S.cod_sede,
        S.descripcion,
        S.direccion,
        S.cod_distrito
        FROM sede S
        {$CONDITIONALS_PART_STRING}
        ";
        
        $headquartersData = db::fetchAll($query);
        
        $headquarters = [];
        
        foreach ($headquartersData as $headquarterData){
            $headquarters[$headquarterData->cod_sede] = new Headquarter([
                "headquarterId"     => $headquarterData->cod_sede,
                "description"       => $headquarterData->descripcion,
                "address"           => $headquarterData->direccion,
                "districtId"        => $headquarterData->cod_distrito
            ]);
        }
        
        return $headquarters;
    }
    
    public function insert(Headquarter $headquarter){
        $insertedHeadquarterId = db::insert("sede", [
            "descripcion"   => $headquarter->getDescription(),
            "direccion"     => $headquarter->getAddress(),
            "cod_distrito"  => $headquarter->getDistrictId()
        ]);
        
        return $insertedHeadquarterId;
    }
    
    public function get($headquarterId){
        return array_pop($this->listHeadquarters([
            "headquarterId" => $headquarterId
        ]));
    }
    
    public function update(Headquarter $headquarter){
        $affectedRows = db::update("sede", [
            "descripcion"   => $headquarter->getDescription(),
            "direccion"     => $headquarter->getAddress(),
            "cod_distrito"  => $headquarter->getDistrictId()
        ], [
            "cod_sede"      => $headquarter->getHeadquarterId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($headquarterId){
        $affectedRows = db::delete("sede", [
            "cod_sede" => $headquarterId
        ]);
        
        return $affectedRows;
    }
}