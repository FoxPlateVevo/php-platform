<?php

require_once __PATH__ . "/app/model/Model.php";

class Curriculum extends Model {
    protected $programId;
    protected $courseId;
    protected $programStoreId;
    protected $courseStoreId;
    protected $registryDate;
    
    function getProgramId() {
        return $this->programId;
    }

    function getCourseId() {
        return $this->courseId;
    }

    function getProgramStoreId() {
        return $this->programStoreId;
    }

    function getCourseStoreId() {
        return $this->courseStoreId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setProgramId($programId) {
        $this->programId = $programId;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setProgramStoreId($programStoreId) {
        $this->programStoreId = $programStoreId;
    }

    function setCourseStoreId($courseStoreId) {
        $this->courseStoreId = $courseStoreId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
