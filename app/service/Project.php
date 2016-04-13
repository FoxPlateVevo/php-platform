<?php

require_once __PATH__ . "/app/model/Project.php";
require_once __PATH__ . "/app/model/ProposalAcademicCalendar.php";
require_once __PATH__ . "/app/model/ProposalAcademicCalendarRange.php";

class Service_Project {
    public $projects;
    public $proposalsAcademicCalendar;
    public $proposalAcademicCalendarRanges;
    
    public function __construct() {
        $this->projects                         = new Service_Project_Projects_Resource();
        $this->proposalsAcademicCalendar        = new Service_Project_ProposalsAcademicCalendar_Resource();
        $this->proposalAcademicCalendarRanges   = new Service_Project_ProposalAcademicCalendarRanges_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Project_Projects_Resource {
    public function listProjects(array $optionParams = null) {
        /*
         * projectId    : optional String, Array
         * storeId      : optional String, Array
         */
        $alternateAttributes = [
            "projectId"     => "P.cod_proyecto",
            "storeId"       => "P.cod_almacen",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        P.cod_proyecto,
        P.fecha_registro,
        P.estado,
        P.cod_propuesta_ca,
        P.cod_almacen
        FROM proyecto P
        {$CONDITIONALS_PART_STRING}
        ";
        
        $projectsData = db::fetchAll($query);
        
        $projects = [];
        
        foreach ($projectsData as $projectData){
            $projects[$projectData->cod_proyecto] = new Project([
                "projectId"         => $projectData->cod_proyecto,
                "registryDate"      => $projectData->fecha_registro,
                "status"            => $projectData->estado,
                "ACproposalId"      => $projectData->cod_propuesta_ca,
                "storeId"           => $projectData->cod_almacen
            ]);
        }
        
        return $projects;
    }
    
    public function insert(Project $project){
        $insertedProjectId = db::insert("proyecto", [
            "fecha_registro"    => $project->getRegistryDate(),
            "estado"            => $project->getStatus(),
            "cod_propuesta_ca"  => $project->getACproposalId(),
            "cod_almacen"       => $project->getStoreId()
        ]);
        
        return $insertedProjectId;
    }
    
    public function get($projectId){
        return array_pop($this->listProjects([
            "projectId" => $projectId
        ]));
    }
    
    public function update(Project $project){
        $affectedRows = db::update("proyecto", [
            "fecha_registro"    => $project->getRegistryDate(),
            "estado"            => $project->getStatus(),
            "cod_propuesta_ca"  => $project->getACproposalId(),
            "cod_almacen"       => $project->getStoreId()
        ], [
            "cod_proyecto"      => $project->getProjectId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($projectId){
        $affectedRows = db::delete("proyecto", [
            "cod_proyecto" => $projectId
        ]);
        
        return $affectedRows;
    }
}

class Service_Project_ProposalsAcademicCalendar_Resource {
    public function listProposalsAcademicCalendar(array $optionParams = null) {
        /*
         * proposalAcademicCalendarId : optional String, Array
         */
        $alternateAttributes = [
            "proposalAcademicCalendarId"    => "PCA.cod_propuesta_ca"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        PCA.cod_propuesta_ca,
        PCA.descripcion,
        PCA.fecha_creacion,
        PCA.estado,
        PCA.cod_usuario_responsable
        FROM propuesta_ca PCA
        {$CONDITIONALS_PART_STRING}
        ";
        
        $proposalsAcademicCalendarData = db::fetchAll($query);
        
        $proposalsAcademicCalendar = [];
        
        foreach ($proposalsAcademicCalendarData as $proposalAcademicCalendarData){
            $proposalsAcademicCalendar[$proposalAcademicCalendarData->cod_propuesta_ca] = new ProposalAcademicCalendar([
                "proposalAcademicCalendarId"    => $proposalAcademicCalendarData->cod_propuesta_ca,
                "description"                   => $proposalAcademicCalendarData->descripcion,
                "registryDate"                  => $proposalAcademicCalendarData->fecha_creacion,
                "status"                        => $proposalAcademicCalendarData->estado,
                "responsibleUserId"             => $proposalAcademicCalendarData->cod_usuario_responsable
            ]);
        }
        
        return $proposalsAcademicCalendar;
    }
    
    public function insert(ProposalAcademicCalendar $proposalAcademicCalendar){
        $insertedProposalAcademicCalendarId = db::insert("propuesta_ca", [
            "descripcion"               => $proposalAcademicCalendar->getDescription(),
            "fecha_creacion"            => $proposalAcademicCalendar->getRegistryDate(),
            "estado"                    => $proposalAcademicCalendar->getStatus(),
            "cod_usuario_responsable"   => $proposalAcademicCalendar->getResponsibleUserId()
        ]);
        
        return $insertedProposalAcademicCalendarId;
    }
    
    public function get($proposalAcademicCalendarId){
        return array_pop($this->listProposalsAcademicCalendar([
            "proposalAcademicCalendarId" => $proposalAcademicCalendarId
        ]));
    }
    
    public function update(ProposalAcademicCalendar $proposalAcademicCalendar){
        $affectedRows = db::update("propuesta_ca", [
            "descripcion"               => $proposalAcademicCalendar->getDescription(),
            "fecha_creacion"            => $proposalAcademicCalendar->getRegistryDate(),
            "estado"                    => $proposalAcademicCalendar->getStatus(),
            "cod_usuario_responsable"   => $proposalAcademicCalendar->getResponsibleUserId()
        ], [
            "cod_propuesta_ca"          => $proposalAcademicCalendar->getProposalAcademicCalendarId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($proposalAcademicCalendarId){
        $affectedRows = db::delete("propuesta_ca", [
            "cod_propuesta_ca" => $proposalAcademicCalendarId
        ]);
        
        return $affectedRows;
    }
}

class Service_Project_ProposalAcademicCalendarRanges_Resource {
    public function listProposalAcademicCalendarRanges(array $optionParams = null) {
        /*
         * proposalAcademicCalendarRangeId : optional String, Array
         */
        $alternateAttributes = [
            "proposalAcademicCalendarRangeId"   => "PCAR.cod_propuesta_ca_rango",
            "proposalAcademicCalendarId"        => "PCAR.cod_propuesta_ca"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        PCAR.cod_propuesta_ca_rango,
        PCAR.tipo,
        PCAR.descripcion,
        PCAR.fecha_inicio,
        PCAR.fecha_final,
        PCAR.cod_propuesta_ca
        FROM propuesta_ca_rango PCAR
        {$CONDITIONALS_PART_STRING}
        ";
        
        $proposalAcademicCalendarRangesData = db::fetchAll($query);
        
        $proposalAcademicCalendarRanges = [];
        
        foreach ($proposalAcademicCalendarRangesData as $proposalAcademicCalendarRangeData){
            $proposalAcademicCalendarRanges[$proposalAcademicCalendarRangeData->cod_propuesta_ca_rango] = new ProposalAcademicCalendarRange([
                "proposalAcademicCalendarRangeId"   => $proposalAcademicCalendarRangeData->cod_propuesta_ca_rango,
                "type"                              => $proposalAcademicCalendarRangeData->tipo,
                "description"                       => $proposalAcademicCalendarRangeData->descripcion,
                "startDate"                         => $proposalAcademicCalendarRangeData->fecha_inicio,
                "finalDate"                         => $proposalAcademicCalendarRangeData->fecha_final,
                "proposalAcademicCalendarId"        => $proposalAcademicCalendarRangeData->cod_propuesta_ca
            ]);
        }
        
        return $proposalAcademicCalendarRanges;
    }
    
    public function insert(ProposalAcademicCalendarRange $proposalAcademicCalendarRange){
        $insertedProposalAcademicCalendarRangeId = db::insert("propuesta_ca_rango", [
            "tipo"              => $proposalAcademicCalendarRange->getType(),
            "descripcion"       => $proposalAcademicCalendarRange->getDescription(),
            "fecha_inicio"      => $proposalAcademicCalendarRange->getStartDate(),
            "fecha_final"       => $proposalAcademicCalendarRange->getFinalDate(),
            "cod_propuesta_ca"  => $proposalAcademicCalendarRange->getProposalAcademicCalendarId()
        ]);
        
        return $insertedProposalAcademicCalendarRangeId;
    }
    
    public function get($insertedProposalAcademicCalendarRangeId){
        return array_pop($this->listProposalAcademicCalendarRanges([
            "proposalAcademicCalendarRangeId" => $insertedProposalAcademicCalendarRangeId
        ]));
    }
    
    public function update(ProposalAcademicCalendarRange $proposalAcademicCalendarRange){
        $affectedRows = db::update("propuesta_ca_rango", [
            "tipo"                      => $proposalAcademicCalendarRange->getType(),
            "descripcion"               => $proposalAcademicCalendarRange->getDescription(),
            "fecha_inicio"              => $proposalAcademicCalendarRange->getStartDate(),
            "fecha_final"               => $proposalAcademicCalendarRange->getFinalDate(),
            "cod_propuesta_ca"          => $proposalAcademicCalendarRange->getProposalAcademicCalendarId()
        ], [
            "cod_propuesta_ca_rango"    => $proposalAcademicCalendarRange->getProposalAcademicCalendarRangeId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($insertedProposalAcademicCalendarRangeId){
        $affectedRows = db::delete("propuesta_ca_rango", [
            "cod_propuesta_ca_rango" => $insertedProposalAcademicCalendarRangeId
        ]);
        
        return $affectedRows;
    }
}