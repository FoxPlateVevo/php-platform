<?php

require_once __PATH__ . "/app/model/Model.php";

class HourHandItem extends Model {
    protected $hourHandItemId;
    protected $hourHandId;
    protected $classroomId;
    protected $day;
    protected $startHour;
    protected $finalHour;
    
    function getHourHandItemId() {
        return $this->hourHandItemId;
    }

    function getHourHandId() {
        return $this->hourHandId;
    }

    function getClassroomId() {
        return $this->classroomId;
    }

    function getDay() {
        return $this->day;
    }

    function getStartHour() {
        return $this->startHour;
    }

    function getFinalHour() {
        return $this->finalHour;
    }

    function setHourHandItemId($hourHandItemId) {
        $this->hourHandItemId = $hourHandItemId;
    }

    function setHourHandId($hourHandId) {
        $this->hourHandId = $hourHandId;
    }

    function setClassroomId($classroomId) {
        $this->classroomId = $classroomId;
    }

    function setDay($day) {
        $this->day = $day;
    }

    function setStartHour($startHour) {
        $this->startHour = $startHour;
    }

    function setFinalHour($finalHour) {
        $this->finalHour = $finalHour;
    }


}
