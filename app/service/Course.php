<?php

require_once __PATH__ . "/app/model/ProfessorDetail.php";
require_once __PATH__ . "/app/model/Unity.php";
require_once __PATH__ . "/app/model/Session.php";
require_once __PATH__ . "/app/model/Material.php";

class Service_Course {
    public $professorDetails;
    public $units;
    public $sessions;
    public $materials;
    
    public function __construct() {
        $this->professorDetails = new Service_Course_ProfessorDetails_Resource();
        $this->units            = new Service_Course_Units_Resource();
        $this->sessions         = new Service_Course_Sessions_Resource();
        $this->materials        = new Service_Course_Materials_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Course_ProfessorDetails_Resource {
    public function listProfessorDetails(array $optionParams = null) {
        /*
         * courseId         : optional String, Array
         * groupId          : optional String, Array
         * professorUserId  : optional String, Array
         */
        $alternateAttributes = [
            "courseId"              => "DC.cod_curso",
            "groupId"               => "DC.cod_grupo",
            "professorUserId"       => "DC.cod_usuario_docente"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        DC.cod_curso,
        DC.cod_grupo,
        DC.cod_usuario_docente,
        DC.fecha_registro,
        DC.estado
        FROM docente_det DC
        {$CONDITIONALS_PART_STRING}
        ";
        
        $professorDetailsData = db::fetchAll($query);
        
        $professorDetails = [];
        
        foreach ($professorDetailsData as $professorDetailData){
            $professorDetails[] = new ProfessorDetail([
                "courseId"          => $professorDetailData->cod_curso,
                "groupId"           => $professorDetailData->cod_grupo,
                "professorUserId"   => $professorDetailData->cod_usuario_docente,
                "registryDate"      => $professorDetailData->fecha_registro,
                "status"            => $professorDetailData->estado
            ]);
        }
        
        return $professorDetails;
    }
    
    public function insert(ProfessorDetail $professorDetail){
        $insertedProfessorDetailId = db::insert("docente_det", [
            "cod_curso"             => $professorDetail->getCourseId(),
            "cod_grupo"             => $professorDetail->getGroupId(),
            "cod_usuario_docente"   => $professorDetail->getProfessorUserId(),
            "fecha_registro"        => get_datetime(),
            "estado"                => $professorDetail->getStatus()
        ]);
        
        return $insertedProfessorDetailId;
    }
    
    public function update(ProfessorDetail $professorDetail){
        $affectedRows = db::update("docente_det", [
            "fecha_registro"        => $professorDetail->getRegistryDate(),
            "estado"                => $professorDetail->getStatus()
        ], [
            "cod_curso"             => $professorDetail->getCourseId(),
            "cod_grupo"             => $professorDetail->getGroupId(),
            "cod_usuario_docente"   => $professorDetail->getProfessorUserId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete(ProfessorDetail $professorDetail){
        $affectedRows = db::delete("docente_det", [
            "cod_curso"             => $professorDetail->getCourseId(),
            "cod_grupo"             => $professorDetail->getGroupId(),
            "cod_usuario_docente"   => $professorDetail->getProfessorUserId()
        ]);
        
        return $affectedRows;
    }
}

class Service_Course_Units_Resource {
    public function listUnits(array $optionParams = null) {
        /*
         * unityId              : optional String, Array
         * courseId             : optional String, Array
         * responsibleUserId    : optional String, Array
         */
        $alternateAttributes = [
            "unityId"           => "U.cod_unidad",
            "courseId"          => "U.cod_curso",
            "responsibleUserId" => "U.cod_usuario_responsable",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        U.cod_unidad,
        U.nombre,
        U.descripcion,
        U.fecha_inicio_disponibilidad,
        U.fecha_final_disponibilidad,
        U.posicion,
        U.fecha_registro,
        U.cod_curso,
        U.cod_usuario_responsable
        FROM unidad U
        {$CONDITIONALS_PART_STRING}
        ";
        
        $unitsData = db::fetchAll($query);
        
        $units = [];
        
        foreach ($unitsData as $unitData){
            $units[$unitData->cod_unidad] = new Unity([
                "unityId"               => $unitData->cod_unidad,
                "name"                  => $unitData->nombre,
                "description"           => $unitData->descripcion,
                "availabilityStartDate" => $unitData->fecha_inicio_disponibilidad,
                "availabilityFinalDate" => $unitData->fecha_final_disponibilidad,
                "position"              => $unitData->posicion,
                "registryDate"          => $unitData->fecha_registro,
                "courseId"              => $unitData->cod_curso,
                "responsibleUserId"     => $unitData->cod_usuario_responsable
            ]);
        }
        
        return $units;
    }
    
    public function insert(Unity $unit){
        $insertedUnitId = db::insert("unidad", [
            "nombre"                        => $unit->getName(),
            "descripcion"                   => $unit->getDescription(),
            "fecha_inicio_disponibilidad"   => $unit->getAvailabilityStartDate(),
            "fecha_final_disponibilidad"    => $unit->getAvailabilityFinalDate(),
            "posicion"                      => $unit->getPosition(),
            "fecha_registro"                => $unit->getRegistryDate(),
            "cod_curso"                     => $unit->getCourseId(),
            "cod_usuario_responsable"       => $unit->getResponsibleUserId()
        ]);
        
        return $insertedUnitId;
    }
    
    public function get($unitId){
        return array_pop($this->listUnits([
            "unityId" => $unitId
        ]));
    }
    
    public function update(Unity $unit){
        $affectedRows = db::update("unidad", [
            "nombre"                        => $unit->getName(),
            "descripcion"                   => $unit->getDescription(),
            "fecha_inicio_disponibilidad"   => $unit->getAvailabilityStartDate(),
            "fecha_final_disponibilidad"    => $unit->getAvailabilityFinalDate(),
            "posicion"                      => $unit->getPosition(),
            "fecha_registro"                => $unit->getRegistryDate(),
            "cod_curso"                     => $unit->getCourseId(),
            "cod_usuario_responsable"       => $unit->getResponsibleUserId()
        ], [
            "cod_unidad"                    => $unit->getUnityId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($unitId){
        $affectedRows = db::delete("unidad", [
            "cod_unidad" => $unitId
        ]);
        
        return $affectedRows;
    }
}

class Service_Course_Sessions_Resource {
    public function listSessions(array $optionParams = null) {
        /*
         * sessionId                : optional String, Array
         * materialId               : optional String, Array
         * unityId                  : optional String, Array
         * responsibleUserId        : optional String, Array
         * professorExhibitorUserId : optional String, Array
         */
        $alternateAttributes = [
            "sessionId"                 => "S.cod_sesion",
            "materialId"                => "S.cod_material",
            "unityId"                   => "S.cod_unidad",
            "responsibleUserId"         => "S.cod_usuario_responsable",
            "professorExhibitorUserId"  => "S.cod_usuario_docente_expositor"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        S.cod_sesion,
        S.nombre,
        S.fecha_inicio_disponibilidad,
        S.fecha_final_disponibilidad,
        S.posicion,
        S.cod_material,
        S.fecha_registro,
        S.cod_unidad,
        S.cod_usuario_responsable,
        S.cod_usuario_docente_expositor
        FROM sesion S
        {$CONDITIONALS_PART_STRING}
        ";
        
        $sessionsData = db::fetchAll($query);
        
        $sessions = [];
        
        foreach ($sessionsData as $sessionData){
            $sessions[$sessionData->cod_sesion] = new Session([
                "sessionId"                 => $sessionData->cod_sesion,
                "name"                      => $sessionData->nombre,
                "availabilityStartDate"     => $sessionData->fecha_inicio_disponibilidad,
                "availabilityFinalDate"     => $sessionData->fecha_final_disponibilidad,
                "position"                  => $sessionData->posicion,
                "materialId"                => $sessionData->cod_material,
                "registryDate"              => $sessionData->fecha_registro,
                "unityId"                   => $sessionData->cod_unidad,
                "responsibleUserId"         => $sessionData->cod_usuario_responsable,
                "professorExhibitorUserId"  => $sessionData->cod_usuario_docente_expositor
            ]);
        }
        
        return $sessions;
    }
    
    public function insert(Session $session){
        $insertedSessionId = db::insert("sesion", [
            "nombre"                        => $session->getName(),
            "fecha_inicio_disponibilidad"   => $session->getAvailabilityStartDate(),
            "fecha_final_disponibilidad"    => $session->getAvailabilityFinalDate(),
            "posicion"                      => $session->getPosition(),
            "cod_material"                  => $session->getMaterialId(),
            "fecha_registro"                => $session->getRegistryDate(),
            "cod_unidad"                    => $session->getUnityId(),
            "cod_usuario_responsable"       => $session->getResponsibleUserId(),
            "cod_usuario_docente_expositor" => $session->getProfessorExhibitorUserId(),
        ]);
        
        return $insertedSessionId;
    }
    
    public function get($sessionId){
        return array_pop($this->listSessions([
            "sessionId" => $sessionId
        ]));
    }
    
    public function update(Session $session){
        $affectedRows = db::update("sesion", [
            "nombre"                        => $session->getName(),
            "fecha_inicio_disponibilidad"   => $session->getAvailabilityStartDate(),
            "fecha_final_disponibilidad"    => $session->getAvailabilityFinalDate(),
            "posicion"                      => $session->getPosition(),
            "cod_material"                  => $session->getMaterialId(),
            "fecha_registro"                => $session->getRegistryDate(),
            "cod_unidad"                    => $session->getUnityId(),
            "cod_usuario_responsable"       => $session->getResponsibleUserId(),
            "cod_usuario_docente_expositor" => $session->getProfessorExhibitorUserId(),
        ], [
            "cod_sesion"                    => $session->getSessionId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($sessionId){
        $affectedRows = db::delete("sesion", [
            "cod_sesion" => $sessionId
        ]);
        
        return $affectedRows;
    }
}

class Service_Course_Materials_Resource {
    public function listMaterials(array $optionParams = null) {
        /*
         * materialId        : optional String, Array
         * categoryId       : optional String, Array
         */
        $alternateAttributes = [
            "materialId"    => "M.cod_material",
            "categoryId"    => "M.cod_categoria"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        M.cod_material,
        M.nombre,
        M.tipo,
        M.contenido,
        M.cod_categoria,
        M.fecha_registro
        FROM material M
        {$CONDITIONALS_PART_STRING}
        ";
        
        $materialsData = db::fetchAll($query);
        
        $materials = [];
        
        foreach ($materialsData as $materialData){
            $materials[$materialData->cod_material] = new Material([
                "materialId"    => $materialData->cod_material,
                "name"          => $materialData->nombre,
                "type"          => $materialData->tipo,
                "content"       => $materialData->contenido,
                "categoryId"    => $materialData->cod_categoria,
                "registryDate"  => $materialData->fecha_registro
            ]);
        }
        
        return $materials;
    }
    
    public function insert(Material $material){
        $insertedMaterialId = db::insert("material", [
            "nombre"            => $material->getName(),
            "tipo"              => $material->getType(),
            "contenido"         => $material->getContent(),
            "cod_categoria"     => $material->getCategoryId(),
            "fecha_registro"    => $material->getRegistryDate()
        ]);
        
        return $insertedMaterialId;
    }
    
    public function get($materialId){
        return array_pop($this->listMaterials([
            "materialId" => $materialId
        ]));
    }
    
    public function update(Material $material){
        $affectedRows = db::update("material", [
            "nombre"            => $material->getName(),
            "tipo"              => $material->getType(),
            "contenido"         => $material->getContent(),
            "cod_categoria"     => $material->getCategoryId(),
            "fecha_registro"    => $material->getRegistryDate(),
        ], [
            "cod_material"      => $material->getMaterialId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($materialId){
        $affectedRows = db::delete("material", [
            "cod_material" => $materialId
        ]);
        
        return $affectedRows;
    }
}