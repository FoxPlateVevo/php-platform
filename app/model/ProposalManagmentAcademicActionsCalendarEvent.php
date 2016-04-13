<?php

require_once __PATH__ . "/app/model/Model.php";

class ProposalManagmentAcademicActionsCalendarEvent extends Model {
    //constants
    const TYPE_EVALUATION           = "EVALUACION";
    const TYPE_EDUCATIVE_ACTIVITY   = "ACTIVIDAD_EDUCATIVA";
    const TYPE_TRAINING             = "CAPACITACION";
    const TYPE_OTHER                = "OTRO";
    const STATUS_PENDING            = "PENDIENTE";
    const STATUS_COMPLETED          = "COMPLETADO";
    
    protected $proposalManagmentAcademicActionsCalendarEventId;
    protected $type;
    protected $startDate;
    protected $finalDate;
    protected $description;
    protected $status;
    protected $proposalManagmentAcademicActionsCalendarId;
    
    function getProposalManagmentAcademicActionsCalendarEventId() {
        return $this->proposalManagmentAcademicActionsCalendarEventId;
    }

    function getType() {
        return $this->type;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getFinalDate() {
        return $this->finalDate;
    }

    function getDescription() {
        return $this->description;
    }

    function getStatus() {
        return $this->status;
    }

    function getProposalManagmentAcademicActionsCalendarId() {
        return $this->proposalManagmentAcademicActionsCalendarId;
    }

    function setProposalManagmentAcademicActionsCalendarEventId($proposalManagmentAcademicActionsCalendarEventId) {
        $this->proposalManagmentAcademicActionsCalendarEventId = $proposalManagmentAcademicActionsCalendarEventId;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    function setFinalDate($finalDate) {
        $this->finalDate = $finalDate;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setProposalManagmentAcademicActionsCalendarId($proposalManagmentAcademicActionsCalendarId) {
        $this->proposalManagmentAcademicActionsCalendarId = $proposalManagmentAcademicActionsCalendarId;
    }


}
