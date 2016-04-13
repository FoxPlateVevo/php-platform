<?php

require_once __PATH__ . "/app/model/Model.php";

class Country extends Model {
    protected $id;
    protected $name;
    protected $postalCode;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getPostalCode() {
        return $this->postalCode;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setPostalCode($postalCode) {
        $this->postalCode = $postalCode;
    }
}
