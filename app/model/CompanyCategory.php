<?php

require_once __PATH__ . "/app/model/Model.php";

class CompanyCategory extends Model {
    protected $companyCategoryId;
    protected $name;
    protected $description;
    
    function getCompanyCategoryId() {
        return $this->companyCategoryId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setCompanyCategoryId($companyCategoryId) {
        $this->companyCategoryId = $companyCategoryId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }


}
