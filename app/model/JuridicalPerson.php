<?php

require_once __PATH__ . "/app/model/Model.php";

class JuridicalPerson extends Model {
    protected $juridicalPersonId;
    protected $ruc;
    protected $contact;
    protected $businessName;
    protected $tradename;
    protected $companyCategoryId;
    protected $mission;
    protected $vision;
    protected $pathLogo;
    protected $userId;
    
    function getJuridicalPersonId() {
        return $this->juridicalPersonId;
    }

    function getRuc() {
        return $this->ruc;
    }

    function getContact() {
        return $this->contact;
    }

    function getBusinessName() {
        return $this->businessName;
    }

    function getTradename() {
        return $this->tradename;
    }

    function getCompanyCategoryId() {
        return $this->companyCategoryId;
    }

    function getMission() {
        return $this->mission;
    }

    function getVision() {
        return $this->vision;
    }

    function getPathLogo() {
        return $this->pathLogo;
    }

    function getUserId() {
        return $this->userId;
    }

    function setJuridicalPersonId($juridicalPersonId) {
        $this->juridicalPersonId = $juridicalPersonId;
    }

    function setRuc($ruc) {
        $this->ruc = $ruc;
    }

    function setContact($contact) {
        $this->contact = $contact;
    }

    function setBusinessName($businessName) {
        $this->businessName = $businessName;
    }

    function setTradename($tradename) {
        $this->tradename = $tradename;
    }

    function setCompanyCategoryId($companyCategoryId) {
        $this->companyCategoryId = $companyCategoryId;
    }

    function setMission($mission) {
        $this->mission = $mission;
    }

    function setVision($vision) {
        $this->vision = $vision;
    }

    function setPathLogo($pathLogo) {
        $this->pathLogo = $pathLogo;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }


}
