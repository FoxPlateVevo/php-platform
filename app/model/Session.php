<?php

require_once __PATH__ . "/app/model/Model.php";

class Session extends Model {
    protected $sessionId;
    protected $name;
    protected $availabilityStartDate;
    protected $availabilityFinalDate;
    protected $position;
    protected $materialId;
    protected $registryDate;
    protected $unityId;
    protected $responsibleUserId;
    protected $professorExhibitorUserId;
    
    function getSessionId() {
        return $this->sessionId;
    }

    function getName() {
        return $this->name;
    }

    function getAvailabilityStartDate() {
        return $this->availabilityStartDate;
    }

    function getAvailabilityFinalDate() {
        return $this->availabilityFinalDate;
    }

    function getPosition() {
        return $this->position;
    }

    function getMaterialId() {
        return $this->materialId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getUnityId() {
        return $this->unityId;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getProfessorExhibitorUserId() {
        return $this->professorExhibitorUserId;
    }

    function setSessionId($sessionId) {
        $this->sessionId = $sessionId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setAvailabilityStartDate($availabilityStartDate) {
        $this->availabilityStartDate = $availabilityStartDate;
    }

    function setAvailabilityFinalDate($availabilityFinalDate) {
        $this->availabilityFinalDate = $availabilityFinalDate;
    }

    function setPosition($position) {
        $this->position = $position;
    }

    function setMaterialId($materialId) {
        $this->materialId = $materialId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setUnityId($unityId) {
        $this->unityId = $unityId;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setProfessorExhibitorUserId($professorExhibitorUserId) {
        $this->professorExhibitorUserId = $professorExhibitorUserId;
    }


}
