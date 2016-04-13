<?php

require_once __PATH__ . "/app/model/Model.php";

class User extends Model {
    //constants
    const PERSON_TYPE_NATURAL       = "NATURAL";
    const PERSON_TYPE_JURIDICAL     = "JURIDICO";
    const STATUS_ACTIVATED          = "ACTIVADO";
    const STATUS_DEACTIVATED        = "DESACTIVADO";
    
    protected $userId;
    protected $personType;
    protected $registryDate;
    protected $email;
    protected $website;
    protected $sellerUserId;
    protected $ditrictId;
    protected $address;
    protected $cellphone;
    protected $phone;
    protected $password;
    protected $status;
    protected $directory;
    
    function getUserId() {
        return $this->userId;
    }

    function getPersonType() {
        return $this->personType;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getEmail() {
        return $this->email;
    }

    function getWebsite() {
        return $this->website;
    }

    function getSellerUserId() {
        return $this->sellerUserId;
    }

    function getDitrictId() {
        return $this->ditrictId;
    }

    function getAddress() {
        return $this->address;
    }

    function getCellphone() {
        return $this->cellphone;
    }

    function getPhone() {
        return $this->phone;
    }

    function getPassword() {
        return $this->password;
    }

    function getStatus() {
        return $this->status;
    }

    function getDirectory() {
        return $this->directory;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setPersonType($personType) {
        $this->personType = $personType;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setEmail($email) {
        $this->email = $email;
    }

    function setWebsite($website) {
        $this->website = $website;
    }

    function setSellerUserId($sellerUserId) {
        $this->sellerUserId = $sellerUserId;
    }

    function setDitrictId($ditrictId) {
        $this->ditrictId = $ditrictId;
    }

    function setAddress($address) {
        $this->address = $address;
    }

    function setCellphone($cellphone) {
        $this->cellphone = $cellphone;
    }

    function setPhone($phone) {
        $this->phone = $phone;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setDirectory($directory) {
        $this->directory = $directory;
    }
}
