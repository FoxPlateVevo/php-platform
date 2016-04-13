<?php

require_once __PATH__ . "/app/model/Model.php";

class Profession extends Model {
    protected $professionId;
    protected $name;
    protected $description;
    
    function getProfessionId() {
        return $this->professionId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setProfessionId($professionId) {
        $this->professionId = $professionId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }



}
