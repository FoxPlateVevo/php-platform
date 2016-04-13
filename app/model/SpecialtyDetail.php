<?php

require_once __PATH__ . "/app/model/Model.php";

class SpecialtyDetail extends Model {
    protected $specialtyId;
    protected $naturalPersonId;
    protected $registryDate;
    
    function getSpecialtyId() {
        return $this->specialtyId;
    }

    function getNaturalPersonId() {
        return $this->naturalPersonId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setSpecialtyId($specialtyId) {
        $this->specialtyId = $specialtyId;
    }

    function setNaturalPersonId($naturalPersonId) {
        $this->naturalPersonId = $naturalPersonId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
