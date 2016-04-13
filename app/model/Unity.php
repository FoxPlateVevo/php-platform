<?php

require_once __PATH__ . "/app/model/Model.php";

class Unity extends Model {
    protected $unityId;
    protected $name;
    protected $description;
    protected $availabilityStartDate;
    protected $availabilityFinalDate;
    protected $position;
    protected $registryDate;
    protected $courseId;
    protected $responsibleUserId;
    
    function getUnityId() {
        return $this->unityId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
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

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getCourseId() {
        return $this->courseId;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setUnityId($unityId) {
        $this->unityId = $unityId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
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

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
