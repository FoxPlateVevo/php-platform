<?php

require_once __PATH__ . "/app/model/Model.php";

class ProfessorDetail extends Model {
    //constants
    const STATUS_CONFIRMED  = "CONFIRMADO";
    const STATUS_PENDING    = "PENDIENTE";
    
    protected $courseId;
    protected $groupId;
    protected $professorUserId;
    protected $registryDate;
    protected $status;
    
    function getCourseId() {
        return $this->courseId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function getProfessorUserId() {
        return $this->professorUserId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setProfessorUserId($professorUserId) {
        $this->professorUserId = $professorUserId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }


}
