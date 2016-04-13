<?php

require_once __PATH__ . "/app/model/Model.php";

class StudentEvaluation extends Model {
    //constants
    const STATUS_PENDING        = "PENDIENTE";
    const STATUS_INITIATED      = "INICIADO";
    const STATUS_SENT           = "ENVIADO";
    const STATUS_REVISED        = "REVISADO";
    
    protected $studentEvaluationId;
    protected $evaluationId;
    protected $studentUserId;
    protected $isGenerated;
    protected $registryDate;
    protected $startDate;
    protected $finalDate;
    protected $startHour;
    protected $finalHour;
    protected $updateDate;
    protected $grade;
    protected $status;
    
    function getStudentEvaluationId() {
        return $this->studentEvaluationId;
    }

    function getEvaluationId() {
        return $this->evaluationId;
    }

    function getStudentUserId() {
        return $this->studentUserId;
    }

    function getIsGenerated() {
        return $this->isGenerated;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getFinalDate() {
        return $this->finalDate;
    }

    function getStartHour() {
        return $this->startHour;
    }

    function getFinalHour() {
        return $this->finalHour;
    }

    function getUpdateDate() {
        return $this->updateDate;
    }

    function getGrade() {
        return $this->grade;
    }

    function getStatus() {
        return $this->status;
    }

    function setStudentEvaluationId($studentEvaluationId) {
        $this->studentEvaluationId = $studentEvaluationId;
    }

    function setEvaluationId($evaluationId) {
        $this->evaluationId = $evaluationId;
    }

    function setStudentUserId($studentUserId) {
        $this->studentUserId = $studentUserId;
    }

    function setIsGenerated($isGenerated) {
        $this->isGenerated = $isGenerated;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    function setFinalDate($finalDate) {
        $this->finalDate = $finalDate;
    }

    function setStartHour($startHour) {
        $this->startHour = $startHour;
    }

    function setFinalHour($finalHour) {
        $this->finalHour = $finalHour;
    }

    function setUpdateDate($updateDate) {
        $this->updateDate = $updateDate;
    }

    function setGrade($grade) {
        $this->grade = $grade;
    }

    function setStatus($status) {
        $this->status = $status;
    }


}
