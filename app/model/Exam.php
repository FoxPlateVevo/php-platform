<?php

require_once __PATH__ . "/app/model/Model.php";

class Exam extends Model {
    //constants
    const ACCESS_TYPE_RIGID             = "RIGIDO";
    const ACCESS_TYPE_CUSTOMIZED        = "PERSONALIZADO";
    const OPPORTUNITY_TYPE_DEACTIVATED  = "DESACTIVADO";
    const OPPORTUNITY_TYPE_LIMITED      = "LIMITADO";
    const OPPORTUNITY_TYPE_UNLIMITED    = "ILIMITADO";
    const PRESENTATION_TYPE_NORMAL      = "NORMAL";
    const PRESENTATION_TYPE_RANDOM      = "ALEATORIO";
    const QUALIFICATION_TYPE_MIXED      = "MIXTO";
    const QUALIFICATION_TYPE_MANUAL     = "MANUAL";
    const QUALIFICATION_TYPE_AUTOMATIC  = "AUTOMATICO";
    
    protected $examId;
    protected $accessType;
    protected $duration;
    protected $opportunityType;
    protected $opportunityQuantity;
    protected $presentationType;
    protected $questionViewQuantity;
    protected $toleranceTime;
    protected $qualificationType;
    protected $evaluationId;

    function getExamId() {
        return $this->examId;
    }

    function getAccessType() {
        return $this->accessType;
    }

    function getDuration() {
        return $this->duration;
    }

    function getOpportunityType() {
        return $this->opportunityType;
    }

    function getOpportunityQuantity() {
        return $this->opportunityQuantity;
    }

    function getPresentationType() {
        return $this->presentationType;
    }

    function getQuestionViewQuantity() {
        return $this->questionViewQuantity;
    }

    function getToleranceTime() {
        return $this->toleranceTime;
    }

    function getQualificationType() {
        return $this->qualificationType;
    }

    function getEvaluationId() {
        return $this->evaluationId;
    }

    function setExamId($examId) {
        $this->examId = $examId;
    }

    function setAccessType($accessType) {
        $this->accessType = $accessType;
    }

    function setDuration($duration) {
        $this->duration = $duration;
    }

    function setOpportunityType($opportunityType) {
        $this->opportunityType = $opportunityType;
    }

    function setOpportunityQuantity($opportunityQuantity) {
        $this->opportunityQuantity = $opportunityQuantity;
    }

    function setPresentationType($presentationType) {
        $this->presentationType = $presentationType;
    }

    function setQuestionViewQuantity($questionViewQuantity) {
        $this->questionViewQuantity = $questionViewQuantity;
    }

    function setToleranceTime($toleranceTime) {
        $this->toleranceTime = $toleranceTime;
    }

    function setQualificationType($qualificationType) {
        $this->qualificationType = $qualificationType;
    }

    function setEvaluationId($evaluationId) {
        $this->evaluationId = $evaluationId;
    }


}
