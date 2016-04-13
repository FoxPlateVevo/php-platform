<?php

require_once __PATH__ . "/app/model/Model.php";

class UserProfile extends Model {
    //constants
    const TYPE_TREASURE                         = "TESORERO";
    const TYPE_PRODUCTION_ANALIST               = "ANALISTA_PRODUCCION";
    const TYPE_ACADEMIC_COORDINATION_BOSS       = "JEFE_COORDINACION_ACADEMICA";
    const TYPE_ACADEMIC_COORDINATION_ASSISTANT  = "ASISTENTE_COORDINACION_ACADEMICA";
    const TYPE_PROFESSOR                        = "DOCENTE";
    const TYPE_CLIENT                           = "CLIENTE";
    const TYPE_STUDENT                          = "ALUMNO";
    const TYPE_ACADEMIC_MANAGEMENT_ASISSTANT    = "ASISTENTE_GESTION_ACADEMICA";
    const TYPE_SUPPORT_ASSISTANT                = "ASISTENTE_SOPORTE";
    const TYPE_MARKETING_ASSISTANT              = "ASISTENTE_MARKETING";
    const TYPE_MARKETING_BOSS                   = "JEFE_MARKETING";
    const TYPE_VENDOR                           = "VENDEDOR";
    
    protected $userProfileId;
    protected $name;
    protected $description;

    function getUserProfileId() {
        return $this->userProfileId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function setUserProfileId($userProfileId) {
        $this->userProfileId = $userProfileId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }


}
