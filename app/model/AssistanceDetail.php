<?php

require_once __PATH__ . "/app/model/Model.php";

class AssistanceDetail extends Model {
    //constants
    const STATUS_ASSISTANCE = "ASISTENCIA";
    const STATUS_DELAY      = "TARDANZA";
    const STATUS_LACK       = "FALTA";
    
    protected $courseId;
    protected $groupId;
    protected $studentUserId;
    protected $registryDate;
    protected $status;
    protected $responsibleUserId;
    
    function getCourseId() {
        return $this->courseId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function getStudentUserId() {
        return $this->studentUserId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setStudentUserId($studentUserId) {
        $this->studentUserId = $studentUserId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
