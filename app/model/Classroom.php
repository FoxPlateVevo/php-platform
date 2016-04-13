<?php

require_once __PATH__ . "/app/model/Model.php";

class Classroom extends Model {
    protected $classroomId;
    protected $description;
    protected $headquarterId;
    
    function getClassroomId() {
        return $this->classroomId;
    }

    function getDescription() {
        return $this->description;
    }

    function getHeadquarterId() {
        return $this->headquarterId;
    }

    function setClassroomId($classroomId) {
        $this->classroomId = $classroomId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setHeadquarterId($headquarterId) {
        $this->headquarterId = $headquarterId;
    }


}
