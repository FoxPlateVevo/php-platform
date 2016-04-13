<?php

require_once __PATH__ . "/app/model/Model.php";

class Specialty extends Model {
    protected $specialtyId;
    protected $name;
    protected $description;
    protected $professionId;
    
    function getSpecialtyId() {
        return $this->specialtyId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getProfessionId() {
        return $this->professionId;
    }

    function setSpecialtyId($specialtyId) {
        $this->specialtyId = $specialtyId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setProfessionId($professionId) {
        $this->professionId = $professionId;
    }


}
