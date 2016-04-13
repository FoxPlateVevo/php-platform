<?php

require_once __PATH__ . "/app/model/Model.php";

class EducativeResource extends Model {
    protected $educativeResourceId;
    protected $name;
    protected $description;
    protected $quantity;
    protected $courseId;
    
    function getEducativeResourceId() {
        return $this->educativeResourceId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getCourseId() {
        return $this->courseId;
    }

    function setEducativeResourceId($educativeResourceId) {
        $this->educativeResourceId = $educativeResourceId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }


}
