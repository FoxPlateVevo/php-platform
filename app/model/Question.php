<?php

require_once __PATH__ . "/app/model/Model.php";

class Question extends Model {
    //constants
    const TYPE_SELECTIVE        = "SELECTIVO";
    const TYPE_MULTISELECTIVE   = "MULTISELECTIVO";
    const TYPE_OPEN             = "ABIERTO";
    
    protected $questioId;
    protected $description;
    protected $type;
    protected $note;
    protected $position;
    protected $questionGroupId;
    
    function getQuestioId() {
        return $this->questioId;
    }

    function getDescription() {
        return $this->description;
    }

    function getType() {
        return $this->type;
    }

    function getNote() {
        return $this->note;
    }

    function getPosition() {
        return $this->position;
    }

    function getQuestionGroupId() {
        return $this->questionGroupId;
    }

    function setQuestioId($questioId) {
        $this->questioId = $questioId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setNote($note) {
        $this->note = $note;
    }

    function setPosition($position) {
        $this->position = $position;
    }

    function setQuestionGroupId($questionGroupId) {
        $this->questionGroupId = $questionGroupId;
    }


}
