<?php

require_once __PATH__ . "/app/model/Model.php";

class Course extends Model {
    protected $courseId;
    protected $professorProfileInformation;
    protected $productId;
    
    function getCourseId() {
        return $this->courseId;
    }

    function getProfessorProfileInformation() {
        return $this->professorProfileInformation;
    }

    function getProductId() {
        return $this->productId;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setProfessorProfileInformation($professorProfileInformation) {
        $this->professorProfileInformation = $professorProfileInformation;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }


}
