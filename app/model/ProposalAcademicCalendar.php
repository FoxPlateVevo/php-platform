<?php

require_once __PATH__ . "/app/model/Model.php";

class ProposalAcademicCalendar extends Model {
    //constants
    const STATUS_PENDING        = "PENDIENTE";
    const STATUS_GRANTED        = "CONCEDIDO";
    const STATUS_FILED          = "ARCHIVADO";
    
    protected $proposalAcademicCalendarId;
    protected $description;
    protected $registryDate;
    protected $status;
    protected $responsibleUserId;
    
    function getProposalAcademicCalendarId() {
        return $this->proposalAcademicCalendarId;
    }

    function getDescription() {
        return $this->description;
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

    function setProposalAcademicCalendarId($proposalAcademicCalendarId) {
        $this->proposalAcademicCalendarId = $proposalAcademicCalendarId;
    }

    function setDescription($description) {
        $this->description = $description;
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


}
