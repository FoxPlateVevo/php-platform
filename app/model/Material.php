<?php

require_once __PATH__ . "/app/model/Model.php";

class Material extends Model {
    //constants
    const TYPE_MULTIMEDIA_FILE     = "ARCHIVO_MULTIMEDIA";
    const TYPE_EMBEDDED_RESOURCE   = "RECURSO_EMBEBIDO";
    
    protected $materialId;
    protected $name;
    protected $type;
    protected $content;
    protected $categoryId;
    protected $registryDate;

    function getMaterialId() {
        return $this->materialId;
    }

    function getName() {
        return $this->name;
    }

    function getType() {
        return $this->type;
    }

    function getContent() {
        return $this->content;
    }

    function getCategoryId() {
        return $this->categoryId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setMaterialId($materialId) {
        $this->materialId = $materialId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setContent($content) {
        $this->content = $content;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
