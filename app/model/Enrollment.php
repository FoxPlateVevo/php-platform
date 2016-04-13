<?php

require_once __PATH__ . "/app/model/Model.php";

class Enrollment extends Model {
    //constants
    const STATUS_ENABLED    = "HABILITADO";
    const STATUS_DISABLED   = "DESHABILITADO";
    
    protected $enrollmentId;
    protected $saleId;
    protected $studentUserId;
    protected $groupId;
    protected $registryDate;
    protected $observation;
    protected $status;
    protected $responsibleUserId;

    function getEnrollmentId() {
        return $this->enrollmentId;
    }

    function getSaleId() {
        return $this->saleId;
    }

    function getStudentUserId() {
        return $this->studentUserId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getObservation() {
        return $this->observation;
    }

    function getStatus() {
        return $this->status;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setEnrollmentId($enrollmentId) {
        $this->enrollmentId = $enrollmentId;
    }

    function setSaleId($saleId) {
        $this->saleId = $saleId;
    }

    function setStudentUserId($studentUserId) {
        $this->studentUserId = $studentUserId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setObservation($observation) {
        $this->observation = $observation;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
