<?php

require_once __PATH__ . "/app/model/Model.php";

class Country extends Model {
    protected $areaId;
    protected $name;
    
    function getAreaId() {
        return $this->areaId;
    }

    function getName() {
        return $this->name;
    }

    function setAreaId($areaId) {
        $this->areaId = $areaId;
    }

    function setName($name) {
        $this->name = $name;
    }


}
