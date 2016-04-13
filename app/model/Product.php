<?php

require_once __PATH__ . "/app/model/Model.php";

class Product extends Model {
    //constants
    const TYPE_PRODUCT_COURSE                   = "CURSO";
    const TYPE_PRODUCT_PROGRAM                  = "PROGRAMA";
    const TYPE_PRESENTATION_MULTIMEDIA_FILE     = "ARCHIVO_MULTIMEDIA";
    const TYPE_PRESENTATION_EMBEDDED_RESOURCE   = "RECURSO_EMBEBIDO";
    
    protected $productId;
    protected $name;
    protected $type;
    protected $categoryId;
    protected $information;
    protected $objectiveInformation;
    protected $objectivePublicInformation;
    protected $registryDate;
    protected $responsibleUserId;
    protected $typePresentation;
    protected $contentPresentation;
    protected $minimunGrade;
    protected $maximunGrade;
    protected $evaluativeSystemInformation;
    protected $diplomaInformation;
    protected $directory;
    
    function getProductId() {
        return $this->productId;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function getInformation() {
        return $this->information;
    }

    function getObjectiveInformation() {
        return $this->objectiveInformation;
    }

    function getObjectivePublicInformation() {
        return $this->objectivePublicInformation;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getTypePresentation() {
        return $this->typePresentation;
    }

    function getContentPresentation() {
        return $this->contentPresentation;
    }

    function getMinimunGrade() {
        return $this->minimunGrade;
    }

    function getMaximunGrade() {
        return $this->maximunGrade;
    }

    function getEvaluativeSystemInformation() {
        return $this->evaluativeSystemInformation;
    }

    function getDiplomaInformation() {
        return $this->diplomaInformation;
    }

    function getDirectory() {
        return $this->directory;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    function setInformation($information) {
        $this->information = $information;
    }

    function setObjectiveInformation($objectiveInformation) {
        $this->objectiveInformation = $objectiveInformation;
    }

    function setObjectivePublicInformation($objectivePublicInformation) {
        $this->objectivePublicInformation = $objectivePublicInformation;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setTypePresentation($typePresentation) {
        $this->typePresentation = $typePresentation;
    }

    function setContentPresentation($contentPresentation) {
        $this->contentPresentation = $contentPresentation;
    }

    function setMinimunGrade($minimunGrade) {
        $this->minimunGrade = $minimunGrade;
    }

    function setMaximunGrade($maximunGrade) {
        $this->maximunGrade = $maximunGrade;
    }

    function setEvaluativeSystemInformation($evaluativeSystemInformation) {
        $this->evaluativeSystemInformation = $evaluativeSystemInformation;
    }

    function setDiplomaInformation($diplomaInformation) {
        $this->diplomaInformation = $diplomaInformation;
    }

    function setDirectory($directory) {
        $this->directory = $directory;
    }


}
