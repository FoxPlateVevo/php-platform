<?php

require_once __PATH__ . "/app/model/Model.php";

class EvaluationConcept extends Model {
    protected $evaluationConceptId;
    protected $name;
    protected $description;
    protected $weight;
    protected $position;
    protected $courseId;
    protected $groupId;

    function getEvaluationConceptId() {
        return $this->evaluationConceptId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getWeight() {
        return $this->weight;
    }

    function getPosition() {
        return $this->position;
    }

    function getCourseId() {
        return $this->courseId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function setEvaluationConceptId($evaluationConceptId) {
        $this->evaluationConceptId = $evaluationConceptId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setWeight($weight) {
        $this->weight = $weight;
    }

    function setPosition($position) {
        $this->position = $position;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }


}
