<?php

require_once __PATH__ . "/app/model/Model.php";

class IncidenceCategory extends Model {
    protected $incidenceCategoryId;
    protected $name;
    protected $description;
    protected $areaId;
    
    function getIncidenceCategoryId() {
        return $this->incidenceCategoryId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getAreaId() {
        return $this->areaId;
    }

    function setIncidenceCategoryId($incidenceCategoryId) {
        $this->incidenceCategoryId = $incidenceCategoryId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setAreaId($areaId) {
        $this->areaId = $areaId;
    }


}
