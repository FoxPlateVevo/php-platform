<?php

require_once __PATH__ . "/app/model/Model.php";

class UserProfileDetail extends Model {
    protected $userId;
    protected $userProfileId;
    protected $registryDate;
    
    function getUserId() {
        return $this->userId;
    }

    function getUserProfileId() {
        return $this->userProfileId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function setUserId($userId) {
        $this->userId = $userId;
    }

    function setUserProfileId($userProfileId) {
        $this->userProfileId = $userProfileId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }


}
