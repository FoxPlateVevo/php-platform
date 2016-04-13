<?php

require_once __PATH__ . "/app/model/Model.php";

class AcademicStaffManagementDetail extends Model {
    //constants
    const STATUS_DEACTIVATED    = "DESACTIVADO";
    const STATUS_ACTIVATED      = "ACTIVADO";
    
    protected $personalUserId;
    protected $profileUserId;
    protected $storeId;
    protected $registryDate;
    protected $status;

    function getPersonalUserId() {
        return $this->personalUserId;
    }

    function getProfileUserId() {
        return $this->profileUserId;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function setPersonalUserId($personalUserId) {
        $this->personalUserId = $personalUserId;
    }

    function setProfileUserId($profileUserId) {
        $this->profileUserId = $profileUserId;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }


}
