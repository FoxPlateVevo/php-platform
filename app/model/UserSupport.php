<?php

require_once __PATH__ . "/app/model/Model.php";

class UserSupport extends Model {
    protected $userSupportId;
    protected $clientUserId;
    protected $registryDate;
    protected $storeId;
    protected $observation;
    protected $responsibleUserId;
    
    function getUserSupportId() {
        return $this->userSupportId;
    }

    function getClientUserId() {
        return $this->clientUserId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function getObservation() {
        return $this->observation;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setUserSupportId($userSupportId) {
        $this->userSupportId = $userSupportId;
    }

    function setClientUserId($clientUserId) {
        $this->clientUserId = $clientUserId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    function setObservation($observation) {
        $this->observation = $observation;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
