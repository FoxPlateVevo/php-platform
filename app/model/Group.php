<?php

require_once __PATH__ . "/app/model/Model.php";

class Group extends Model {
    //constants
    const TYPE_ENTRY_BY_DEFULT      = "POR_DEFECTO";
    const TYPE_ENTRY_BY_CREATION    = "POR_CREACION";
    
    protected $groupId;
    protected $description;
    protected $headquarterId;
    protected $starDate;
    protected $finalDate;
    protected $typeEntry;
    protected $storeId;
    
    function getGroupId() {
        return $this->groupId;
    }

    function getDescription() {
        return $this->description;
    }

    function getHeadquarterId() {
        return $this->headquarterId;
    }

    function getStarDate() {
        return $this->starDate;
    }

    function getFinalDate() {
        return $this->finalDate;
    }

    function getTypeEntry() {
        return $this->typeEntry;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setHeadquarterId($headquarterId) {
        $this->headquarterId = $headquarterId;
    }

    function setStarDate($starDate) {
        $this->starDate = $starDate;
    }

    function setFinalDate($finalDate) {
        $this->finalDate = $finalDate;
    }

    function setTypeEntry($typeEntry) {
        $this->typeEntry = $typeEntry;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }


}
