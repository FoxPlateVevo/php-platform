<?php

require_once __PATH__ . "/app/model/Model.php";

class HourHand extends Model {
    protected $hourHandId;
    protected $courseId;
    protected $groupId;
    protected $responsibleUserId;
    protected $startDay;
    protected $finalDay;
    protected $regitryDate;

    function getHourHandId() {
        return $this->hourHandId;
    }

    function getCourseId() {
        return $this->courseId;
    }

    function getGroupId() {
        return $this->groupId;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getStartDay() {
        return $this->startDay;
    }

    function getFinalDay() {
        return $this->finalDay;
    }

    function getRegitryDate() {
        return $this->regitryDate;
    }

    function setHourHandId($hourHandId) {
        $this->hourHandId = $hourHandId;
    }

    function setCourseId($courseId) {
        $this->courseId = $courseId;
    }

    function setGroupId($groupId) {
        $this->groupId = $groupId;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setStartDay($startDay) {
        $this->startDay = $startDay;
    }

    function setFinalDay($finalDay) {
        $this->finalDay = $finalDay;
    }

    function setRegitryDate($regitryDate) {
        $this->regitryDate = $regitryDate;
    }


}
