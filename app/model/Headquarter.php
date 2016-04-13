<?php

require_once __PATH__ . "/app/model/Model.php";

class Headquarter extends Model {
    protected $headquarterId;
    protected $description;
    protected $address;
    protected $districtId;
    
    function getHeadquarterId() {
        return $this->headquarterId;
    }

    function getDescription() {
        return $this->description;
    }

    function getAddress() {
        return $this->address;
    }

    function getDistrictId() {
        return $this->districtId;
    }

    function setHeadquarterId($headquarterId) {
        $this->headquarterId = $headquarterId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setDistrictId($districtId) {
        $this->districtId = $districtId;
    }


}
