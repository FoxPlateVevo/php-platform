<?php

require_once __PATH__ . "/app/model/Model.php";

class JobDetail extends Model {
    protected $jobId;
    protected $naturalPersonId;
    protected $description;
    protected $registryDate;
    
    function getJobId() {
        return $this->jobId;
    }

    function getNaturalPersonId() {
        return $this->naturalPersonId;
    }

    function getDescription() {
        return $this->description;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setJobId($jobId) {
        $this->jobId = $jobId;
    }

    function setNaturalPersonId($naturalPersonId) {
        $this->naturalPersonId = $naturalPersonId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
