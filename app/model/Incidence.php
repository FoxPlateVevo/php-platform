<?php

require_once __PATH__ . "/app/model/Model.php";

class Incidence extends Model {
    //constants
    const STATUS_PENDING    = "PENDIENTE";
    const STATUS_PROCESSING = "PROCESANDO";
    const STATUS_CLOSED     = "CERRADO";
    const STATUS_REOPENED   = "REABIERTO";
    
    protected $incidenceId;
    protected $clientUserId;
    protected $registryDate;
    protected $storeId;
    protected $supportAssistantUserId;
    protected $categoryIncidenceId;
    protected $subject;
    protected $description;
    protected $status;
    
    function getIncidenceId() {
        return $this->incidenceId;
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

    function getSupportAssistantUserId() {
        return $this->supportAssistantUserId;
    }

    function getCategoryIncidenceId() {
        return $this->categoryIncidenceId;
    }

    function getSubject() {
        return $this->subject;
    }

    function getDescription() {
        return $this->description;
    }

    function getStatus() {
        return $this->status;
    }

    function setIncidenceId($incidenceId) {
        $this->incidenceId = $incidenceId;
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

    function setSupportAssistantUserId($supportAssistantUserId) {
        $this->supportAssistantUserId = $supportAssistantUserId;
    }

    function setCategoryIncidenceId($categoryIncidenceId) {
        $this->categoryIncidenceId = $categoryIncidenceId;
    }

    function setSubject($subject) {
        $this->subject = $subject;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStatus($status) {
        $this->status = $status;
    }


}
