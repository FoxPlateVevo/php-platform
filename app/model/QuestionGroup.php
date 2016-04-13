<?php

require_once __PATH__ . "/app/model/Model.php";

class QuestionGroup extends Model {
    //constants
    const TYPE_ENTRY_BY_DEFULT      = "POR_DEFECTO";
    const TYPE_ENTRY_BY_CREATION    = "POR_CREACION";
    const PRESENTATION_TYPE_NORMAL  = "NORMAL";
    const PRESENTATION_TYPE_RANDOM  = "ALEATORIO";
    
    protected $questionGroupId;
    protected $typeEntry;
    protected $presentationType;
    protected $questionViewQuantity;
    protected $examId;

    function getQuestionGroupId() {
        return $this->questionGroupId;
    }

    function getTypeEntry() {
        return $this->typeEntry;
    }

    function getPresentationType() {
        return $this->presentationType;
    }

    function getQuestionViewQuantity() {
        return $this->questionViewQuantity;
    }

    function getExamId() {
        return $this->examId;
    }

    function setQuestionGroupId($questionGroupId) {
        $this->questionGroupId = $questionGroupId;
    }

    function setTypeEntry($typeEntry) {
        $this->typeEntry = $typeEntry;
    }

    function setPresentationType($presentationType) {
        $this->presentationType = $presentationType;
    }

    function setQuestionViewQuantity($questionViewQuantity) {
        $this->questionViewQuantity = $questionViewQuantity;
    }

    function setExamId($examId) {
        $this->examId = $examId;
    }


}
