<?php

require_once __PATH__ . "/app/model/User.php";
require_once __PATH__ . "/app/model/JuridicalPerson.php";
require_once __PATH__ . "/app/model/NaturalPerson.php";
require_once __PATH__ . "/app/model/UserProfile.php";
require_once __PATH__ . "/app/model/UserProfileDetail.php";

class Service_User {
    public $users;
    public $userProfiles;
    public $userProfileDetails;
    
    public function __construct() {
        $this->users                = new Service_User_Users_Resource();
        $this->userProfiles         = new Service_User_UserProfiles_Resource();
        $this->userProfileDetails   = new Service_User_UserProfileDetails_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_User_Users_Resource {
    public function listUsers(array $optionParams = null) {
        /*
         * userId           : optional String, Array
         * email            : optional String, Array
         * password         : optional String, Array
         * 
         * UserProfileType  : optional String
         */
        $alternateAttributes = [
            "userId"    => "U.cod_usuario",
            "email"     => "U.email",
            "password"  => "U.password"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        if($optionParams["UserProfileType"] && is_string($optionParams["UserProfileType"])){
            $UserProfileTypeValue = $optionParams["UserProfileType"];
            
            $WHERE_PART_STRING_ADDED = "
            INNER JOIN perfil_det PD ON PD.cod_usuario = U.cod_usuario 
            INNER JOIN perfil_usuario PU ON PU.cod_perfil_usuario = PD.cod_perfil_usuario
            ";
            
            $CONDITIONALS_PART_STRING_ADDED = "
            AND PU.nombre = '{$UserProfileTypeValue}'
            ";
        }
        
        $query = "
        SELECT
        U.cod_usuario,
        U.tipo_persona,
        U.fecha_registro,
        U.email,
        U.website,
        U.cod_usuario_vendedor,
        U.cod_distrito,
        U.direccion,
        U.celular,
        U.telefono,
        U.password,
        U.estado,
        U.carpeta,
        PN.cod_persona_natural,
        PN.dni,
        PN.nombre,
        PN.apellido_paterno,
        PN.apellido_materno,
        PN.fecha_nacimiento,
        PN.estado_civil,
        PN.situacion_laboral,
        PN.grado_estudio,
        PN.cod_usuario as pn_cod_usuario,
        PJ.cod_persona_juridica,
        PJ.ruc,
        PJ.contacto,
        PJ.razon_social,
        PJ.nombre_comercial,
        PJ.cod_categoria_empresa,
        PJ.mision,
        PJ.vision,
        PJ.path_logo,
        PJ.cod_usuario as pj_cod_usuario
        FROM usuario U
        LEFT JOIN persona_natural PN ON PN.cod_usuario = U.cod_usuario
        LEFT JOIN persona_juridica PJ ON PJ.cod_usuario = U.cod_usuario
        {$WHERE_PART_STRING_ADDED}
        {$CONDITIONALS_PART_STRING}
        {$CONDITIONALS_PART_STRING_ADDED}
        ";
        
        $usersData = db::fetchAll($query);
        
        $users = [];
        
        foreach ($usersData as $userData){
            $user = new User([
                "userId"        => $userData->cod_usuario,
                "personType"    => $userData->tipo_persona,
                "registryDate"  => $userData->fecha_registro,
                "email"         => $userData->email,
                "website"       => $userData->website,
                "sellerUserId"  => $userData->cod_usuario_vendedor,
                "ditrictId"     => $userData->cod_distrito,
                "address"       => $userData->direccion,
                "cellphone"     => $userData->celular,
                "phone"         => $userData->telefono,
                "password"      => $userData->password,
                "status"        => $userData->estado,
                "directory"     => $userData->carpeta
            ]);
            
            if($user->getPersonType() === User::PERSON_TYPE_NATURAL){
                $person = new NaturalPerson([
                    "naturalPersonId"   => $userData->cod_persona_natural,
                    "dni"               => $userData->dni,
                    "name"              => $userData->nombre,
                    "lastName"          => $userData->apellido_paterno,
                    "motherLastName"    => $userData->apellido_materno,
                    "birthDate"         => $userData->fecha_nacimiento,
                    "civilStatus"       => $userData->estado_civil,
                    "employmentStatus"  => $userData->situacion_laboral,
                    "studyGrade"        => $userData->grado_estudio,
                    "userId"            => $userData->pn_cod_usuario
                ]);
            }else if($user->getPersonType() === User::PERSON_TYPE_JURIDICAL){
                $person = new JuridicalPerson([
                    "juridicalPersonId" => $userData->cod_persona_juridica,
                    "ruc"               => $userData->ruc,
                    "contact"           => $userData->contacto,
                    "businessName"      => $userData->razon_social,
                    "tradename"         => $userData->nombre_comercial,
                    "companyCategoryId" => $userData->cod_categoria_empresa,
                    "mission"           => $userData->mision,
                    "vision"            => $userData->vision,
                    "pathLogo"          => $userData->path_logo,
                    "userId"            => $userData->pj_cod_usuario
                ]);
            }
            
            $user->person = $person;
            
            $users[$userData->cod_usuario] = $user;
        }
        
        return $users;
    }
    
    public function insert(User $user, $person){
        if($person instanceof NaturalPerson || $person instanceof JuridicalPerson){
            $insertedUserId = db::insert("usuario", [
                "tipo_persona"              => $user->getPersonType(),
                "fecha_registro"            => $user->getRegistryDate(),
                "email"                     => $user->getEmail(),
                "website"                   => $user->getWebsite(),
                "cod_usuario_vendedor"      => $user->getSellerUserId(),
                "cod_distrito"              => $user->getDitrictId(),
                "direccion"                 => $user->getAddress(),
                "celular"                   => $user->getCellphone(),
                "telefono"                  => $user->getPhone(),
                "password"                  => $user->getPassword(),
                "estado"                    => $user->getStatus(),
                "carpeta"                   => $user->getDirectory()
            ]);

            if($insertedUserId){
                $insertedPersonId = false;
                
                if($user->getPersonType() === User::PERSON_TYPE_NATURAL && $person instanceof NaturalPerson){
                    $insertedPersonId = db::insert("persona_natural", [
                        "dni"               => $person->getDni(),
                        "nombre"            => $person->getName(),
                        "apellido_paterno"  => $person->getLastName(),
                        "apellido_materno"  => $person->getMotherLastName(),
                        "fecha_nacimiento"  => $person->getBirthDate(),
                        "estado_civil"      => $person->getCivilStatus(),
                        "situacion_laboral" => $person->getEmploymentStatus(),
                        "grado_estudio"     => $person->getStudyGrade(),
                        "cod_usuario"       => $insertedUserId
                    ]);
                }else if($user->getPersonType() === User::PERSON_TYPE_JURIDICAL && $person instanceof JuridicalPerson){
                    $insertedPersonId = db::insert("persona_juridica", [
                        "ruc"                       => $person->getRuc(),
                        "contacto"                  => $person->getContact(),
                        "razon_social"              => $person->getBusinessName(),
                        "nombre_comercial"          => $person->getTradename(),
                        "cod_categoria_empresa"     => $person->getCompanyCategoryId(),
                        "mision"                    => $person->getMission(),
                        "vision"                    => $person->getVision(),
                        "path_logo"                 => $person->getPathLogo(),
                        "cod_usuario"               => $insertedUserId
                    ]);
                }
                
                if(!$insertedPersonId){
                    db::delete("usuario", [
                        "cod_usuario"   => $insertedUserId
                    ]);
                    
                    $insertedUserId = null;
                }
            }
        }
        
        return $insertedUserId;
    }
    
    public function get($userId){
        return array_pop($this->listUsers([
            "userId"    => $userId
        ]));
    }
    
    public function update(User $user, $person = null){
        $success = false;
        
        $successUpdatedUser = db::update("usuario", [
            "tipo_persona"              => $user->getPersonType(),
            "fecha_registro"            => $user->getRegistryDate(),
            "email"                     => $user->getEmail(),
            "website"                   => $user->getWebsite(),
            "cod_usuario_vendedor"      => $user->getSellerUserId(),
            "cod_distrito"              => $user->getDitrictId(),
            "direccion"                 => $user->getAddress(),
            "celular"                   => $user->getCellphone(),
            "telefono"                  => $user->getPhone(),
            "password"                  => $user->getPassword(),
            "estado"                    => $user->getStatus(),
            "carpeta"                   => $user->getDirectory()
        ], [
            "cod_usuario"               => $user->getUserId()
        ]);
        
        if($person && ($person instanceof NaturalPerson || $person instanceof JuridicalPerson) && $successUpdatedUser){
            if($user->getPersonType() === User::PERSON_TYPE_NATURAL && $person instanceof NaturalPerson){
                $success = db::update("persona_natural", [
                    "dni"               => $person->getDni(),
                    "nombre"            => $person->getName(),
                    "apellido_paterno"  => $person->getLastName(),
                    "apellido_materno"  => $person->getMotherLastName(),
                    "fecha_nacimiento"  => $person->getBirthDate(),
                    "estado_civil"      => $person->getCivilStatus(),
                    "situacion_laboral" => $person->getEmploymentStatus(),
                    "grado_estudio"     => $person->getStudyGrade(),
                    "cod_usuario"       => $user->getUserId()
                ], [
                    "cod_persona_natural"   => $person->getNaturalPersonId()
                ]);
            }else if($user->getPersonType() === User::PERSON_TYPE_JURIDICAL && $person instanceof JuridicalPerson){
                $success = db::update("persona_juridica", [
                    "ruc"                       => $person->getRuc(),
                    "contacto"                  => $person->getContact(),
                    "razon_social"              => $person->getBusinessName(),
                    "nombre_comercial"          => $person->getTradename(),
                    "cod_categoria_empresa"     => $person->getCompanyCategoryId(),
                    "mision"                    => $person->getMission(),
                    "vision"                    => $person->getVision(),
                    "path_logo"                 => $person->getPathLogo(),
                    "cod_usuario"               => $user->getUserId()
                ], [
                    "cod_persona_juridica"   => $person->getJuridicalPersonId()
                ]);
            }
        }else{
            $success = $successUpdatedUser;
        }
        
        return $success;
    }
    
    public function delete($userId){
        $success = $successDeletedPerson = false;
        
        $user   = $this->get($userId);
        $person = $user->person;
//        $user = new User();
        
        if($user->getPersonType() === User::PERSON_TYPE_NATURAL){
//            $person = new NaturalPerson();
            
            $successDeletedPerson = db::delete("persona_natural", [
                "cod_persona_natural" => $person->getNaturalPersonId()
            ]);
        }elseif($user->getPersonType() === User::PERSON_TYPE_JURIDICAL){
//            $person = new JuridicalPerson();

            $successDeletedPerson = db::delete("persona_natural", [
                "cod_persona_juridica" => $person->getJuridicalPersonId()
            ]);
        }
        
        if($successDeletedPerson){
            $success = db::delete("usuario", [
                "cod_usuario" => $user->getUserId()
            ]);
        }
        
        return $success;
    }
}

class Service_User_UserProfiles_Resource{
    public function listUserProfiles(array $optionParams = null) {
        /*
         * userProfileId : optional String, Array
         * name : optional String, Array
         */
        $alternateAttributes = [
            "userProfileId" => "PU.cod_perfil_usuario",
            "name"          => "PU.nombre",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT
        PU.cod_perfil_usuario,
        PU.nombre,
        PU.descripcion
        FROM perfil_usuario PU
        {$CONDITIONALS_PART_STRING}
        ";
        
        $userProfilesData = db::fetchAll($query);
        
        $userProfiles = [];
        
        foreach ($userProfilesData as $userProfileData){
            $userProfiles[$userProfileData->cod_perfil_usuario] = new UserProfile([
                "userProfileId"     => $userProfileData->cod_perfil_usuario,
                "name"              => $userProfileData->nombre,
                "description"       => $userProfileData->descripcion
            ]);
        }
        
        return $userProfiles;
    }
    
    public function get($userProfileId){
        return array_pop($this->listUserProfiles([
            "userProfileId" => $userProfileId
        ]));
    }
}

class Service_User_UserProfileDetails_Resource{
    public function listUserProfileDetails(array $optionParams = null) {
        /*
         * userId           :  optional String, Array
         * userProfileId    : optional String, Array
         */
        $alternateAttributes = [
            "userId"            => "PD.cod_usuario",
            "userProfileId"     => "PD.cod_perfil_usuario"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        PD.cod_usuario,
        PD.cod_perfil_usuario,
        PD.fecha_registro
        FROM perfil_det PD
        {$CONDITIONALS_PART_STRING}
        ";
        
        $userProfileDetailsData = db::fetchAll($query);
        
        $userProfileDetails = [];
        
        foreach ($userProfileDetailsData as $userProfileDetailData){
            $userProfileDetails[] = new UserProfileDetail([
                "userId"            => $userProfileDetailData->cod_usuario,
                "userProfileId"     => $userProfileDetailData->cod_perfil_usuario,
                "registryDate"      => $userProfileDetailData->fecha_registro
            ]);
        }
        
        return $userProfileDetails;
    }
    
    public function insert(UserProfileDetail $userProfileDetail){
        $insertedUserProfileDetailId = db::insert("perfil_det", [
          "cod_usuario"         => $userProfileDetail->getUserId(),
          "cod_perfil_usuario"  => $userProfileDetail->getUserProfileId(),
          "fecha_registro"      => get_datetime()
        ]);
        
        return $insertedUserProfileDetailId;
    }
    
    public function delete(UserProfileDetail $userProfileDetail){
        $affectedRows = db::delete("perfil_det", [
            "cod_usuario"           => $userProfileDetail->getUserId(),
            "cod_perfil_usuario"    => $userProfileDetail->getUserProfileId()
        ]);
        
        return $affectedRows;
    }
}