<?php

require_once __PATH__ . "/app/model/Model.php";

class ProfessionDetail extends Model {
    protected $naturalPersonId;
    protected $professionId;
    protected $registryDate;
    protected $tuitionNumber;
    protected $exerciseStartDate;

    function getNaturalPersonId() {
        return $this->naturalPersonId;
    }

    function getProfessionId() {
        return $this->professionId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getTuitionNumber() {
        return $this->tuitionNumber;
    }

    function getExerciseStartDate() {
        return $this->exerciseStartDate;
    }

    function setNaturalPersonId($naturalPersonId) {
        $this->naturalPersonId = $naturalPersonId;
    }

    function setProfessionId($professionId) {
        $this->professionId = $professionId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setTuitionNumber($tuitionNumber) {
        $this->tuitionNumber = $tuitionNumber;
    }

    function setExerciseStartDate($exerciseStartDate) {
        $this->exerciseStartDate = $exerciseStartDate;
    }


}
