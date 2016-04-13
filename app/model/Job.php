<?php

require_once __PATH__ . "/app/model/Model.php";

class Country extends Model {
    protected $jobId;
    protected $name;
    protected $areaId;
    
    function getJobId() {
        return $this->jobId;
    }
    
    function getName() {
        return $this->name;
    }

    function getAreaId() {
        return $this->areaId;
    }

    function setJobId($jobId) {
        $this->jobId = $jobId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setAreaId($areaId) {
        $this->areaId = $areaId;
    }


}
