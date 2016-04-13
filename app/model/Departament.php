<?php

require_once __PATH__ . "/app/model/Model.php";

class Departament extends Model {
    protected $id;
    protected $name;
    protected $countryId;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getCountryId() {
        return $this->countryId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setCountryId($countryId) {
        $this->countryId = $countryId;
    }
}
