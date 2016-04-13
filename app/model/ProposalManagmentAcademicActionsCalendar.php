<?php

require_once __PATH__ . "/app/model/Model.php";

class ProposalManagmentAcademicActionsCalendar extends Model {
    protected $proposalManagmentAcademicActionsCalendarId;
    protected $groupId;
    protected $responsibleUserId;
    protected $registryDate;
    
    function getProposalManagmentAcademicActionsCalendarId() {
        return $this->proposalManagmentAcademicActionsCalendarId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setProposalManagmentAcademicActionsCalendarId($proposalManagmentAcademicActionsCalendarId) {
        $this->proposalManagmentAcademicActionsCalendarId = $proposalManagmentAcademicActionsCalendarId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
