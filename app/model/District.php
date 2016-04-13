<?php

require_once __PATH__ . "/app/model/Model.php";

class District extends Model {
    protected $id;
    protected $name;
    protected $provinceId;
    
    function getId() {
        return $this->id;
    }

    function getName() {
        return $this->name;
    }

    function getProvinceId() {
        return $this->provinceId;
    }

    function setId($id) {
        $this->id = $id;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setProvinceId($provinceId) {
        $this->provinceId = $provinceId;
    }
}
