<?php

require_once __PATH__ . "/app/model/Model.php";

class NaturalPerson extends Model {
    //constants
    const CIVIL_STATUS_SINGLE           = "SOLTERO";
    const CIVIL_STATUS_COHABITANT       = "CONVIVIENTE";
    const CIVIL_STATUS_MARRIED          = "CASADO";
    const CIVIL_STATUS_WIDOWER          = "VIUDO";
    const EMPLOYMENT_STATUS_DEPENDENT   = "DEPENDIENTE";
    const EMPLOYMENT_STATUS_INDEPENDENT = "INDEPENDIENTE";
    const EMPLOYMENT_STATUS_RETIRED     = "JUBILADO";
    
    protected $naturalPersonId;
    protected $dni;
    protected $name;
    protected $lastName;
    protected $motherLastName;
    protected $birthDate;
    protected $civilStatus;
    protected $employmentStatus;
    protected $studyGrade;
    protected $userId;
    
    function getNaturalPersonId() {
        return $this->naturalPersonId;
    }

    function getDni() {
        return $this->dni;
    }

    function getName() {
        return $this->name;
    }

    function getLastName() {
        return $this->lastName;
    }

    function getMotherLastName() {
        return $this->motherLastName;
    }

    function getBirthDate() {
        return $this->birthDate;
    }

    function getCivilStatus() {
        return $this->civilStatus;
    }

    function getEmploymentStatus() {
        return $this->employmentStatus;
    }

    function getStudyGrade() {
        return $this->studyGrade;
    }

    function getUserId() {
        return $this->userId;
    }

    function setNaturalPersonId($naturalPersonId) {
        $this->naturalPersonId = $naturalPersonId;
    }

    function setDni($dni) {
        $this->dni = $dni;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setLastName($lastName) {
        $this->lastName = $lastName;
    }

    function setMotherLastName($motherLastName) {
        $this->motherLastName = $motherLastName;
    }

    function setBirthDate($birthDate) {
        $this->birthDate = $birthDate;
    }

    function setCivilStatus($civilStatus) {
        $this->civilStatus = $civilStatus;
    }

    function setEmploymentStatus($employmentStatus) {
        $this->employmentStatus = $employmentStatus;
    }

    function setStudyGrade($studyGrade) {
        $this->studyGrade = $studyGrade;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    
}
