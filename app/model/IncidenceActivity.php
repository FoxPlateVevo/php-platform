<?php

require_once __PATH__ . "/app/model/Model.php";

class IncidenceActivity extends Model {
    //constants
    const STATUS_PENDING_TO_VIEW    = "VISTA_PENDIENTE";
    const STATUS_VIEWED             = "VISTO";
    
    protected $incidenceActivityId;
    protected $message;
    protected $registryDate;
    protected $status;
    protected $responsibleUserId;
    protected $incidenceId;

    function getIncidenceActivityId() {
        return $this->incidenceActivityId;
    }

    function getMessage() {
        return $this->message;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getIncidenceId() {
        return $this->incidenceId;
    }

    function setIncidenceActivityId($incidenceActivityId) {
        $this->incidenceActivityId = $incidenceActivityId;
    }

    function setMessage($message) {
        $this->message = $message;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setIncidenceId($incidenceId) {
        $this->incidenceId = $incidenceId;
    }


}
