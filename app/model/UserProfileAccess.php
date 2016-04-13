<?php

require_once __PATH__ . "/app/model/Model.php";

class UserProfileAccess extends Model {
    protected $userProfileAccessId;
    protected $description;
    protected $userProfileId;

    function getUserProfileAccessId() {
        return $this->userProfileAccessId;
    }

    function getDescription() {
        return $this->description;
    }

    function getUserProfileId() {
        return $this->userProfileId;
    }

    function setUserProfileAccessId($userProfileAccessId) {
        $this->userProfileAccessId = $userProfileAccessId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setUserProfileId($userProfileId) {
        $this->userProfileId = $userProfileId;
    }


}
