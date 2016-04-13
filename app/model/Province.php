<?php

require_once __PATH__ . "/app/model/Model.php";

class Province extends Model {
    protected $id;
    protected $name;
    protected $departamentId;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getDepartamentId() {
        return $this->departamentId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDepartamentId($departamentId) {
        $this->departamentId = $departamentId;
    }
}
