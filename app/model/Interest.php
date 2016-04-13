<?php

require_once __PATH__ . "/app/model/Model.php";

class Interest extends Model {
    //constants
    const TYPE_PERCENT      = "PORCENTAJE";
    const TYPE_FIX_CHARGE   = "CARGO_FIJO";
    
    protected $interestId;
    protected $type;
    protected $value;
    protected $description;
    protected $storeId;
    
    function getInterestId() {
        return $this->interestId;
    }

    function getType() {
        return $this->type;
    }

    function getValue() {
        return $this->value;
    }

    function getDescription() {
        return $this->description;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function setInterestId($interestId) {
        $this->interestId = $interestId;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setValue($value) {
        $this->value = $value;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }


}
