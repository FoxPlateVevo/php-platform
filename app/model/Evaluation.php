<?php

require_once __PATH__ . "/app/model/Model.php";

class Evaluation extends Model {
    //constants
    const TYPE_EXAM             = "EXAMEN";
    const TYPE_FORUM            = "FORO";
    const TYPE_ATTACHED_FILE    = "ARCHIVO_ADJUNTO";
    const STATUS_STRUCTURING    = "ESTRUCTURANDO";
    const STATUS_STRUCTURED     = "ESTRUCTURADO";
    const STATUS_DEVELOPING     = "DESARROLLANDO";
    const STATUS_COMPLETED      = "COMPLETADO";
    const STATUS_QUALIFIED      = "CALIFICADO";
    
    protected $evaluationId;
    protected $evaluationConceptId;
    protected $name;
    protected $sessionId;
    protected $minimunGrade;
    protected $startDate;
    protected $finalDate;
    protected $startHour;
    protected $finalHour;
    protected $qualificationDeliveryDate;
    protected $isQualified;
    protected $registryDate;
    protected $type;
    protected $status;
    protected $responsibleUserId;
    
    function getEvaluationId() {
        return $this->evaluationId;
    }

    function getEvaluationConceptId() {
        return $this->evaluationConceptId;
    }

    function getName() {
        return $this->name;
    }

    function getSessionId() {
        return $this->sessionId;
    }

    function getMinimunGrade() {
        return $this->minimunGrade;
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

    function getQualificationDeliveryDate() {
        return $this->qualificationDeliveryDate;
    }

    function getIsQualified() {
        return $this->isQualified;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getType() {
        return $this->type;
    }

    function getStatus() {
        return $this->status;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setEvaluationId($evaluationId) {
        $this->evaluationId = $evaluationId;
    }

    function setEvaluationConceptId($evaluationConceptId) {
        $this->evaluationConceptId = $evaluationConceptId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    function setMinimunGrade($minimunGrade) {
        $this->minimunGrade = $minimunGrade;
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

    function setQualificationDeliveryDate($qualificationDeliveryDate) {
        $this->qualificationDeliveryDate = $qualificationDeliveryDate;
    }

    function setIsQualified($isQualified) {
        $this->isQualified = $isQualified;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
