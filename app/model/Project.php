<?php

require_once __PATH__ . "/app/model/Model.php";

class Project extends Model {
//constants
    const STATUS_ACTIVATED          = "ACTIVADO";
    const STATUS_DEACTIVATED        = "DESACTIVADO";
    
    protected $projectId;
    protected $registryDate;
    protected $status;
    protected $ACproposalId;
    protected $storeId;
    
    function getProjectId() {
        return $this->projectId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function getACproposalId() {
        return $this->ACproposalId;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function setProjectId($projectId) {
        $this->projectId = $projectId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setACproposalId($ACproposalId) {
        $this->ACproposalId = $ACproposalId;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }


}
