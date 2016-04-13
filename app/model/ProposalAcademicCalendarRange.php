<?php

require_once __PATH__ . "/app/model/Model.php";

class ProposalAcademicCalendarRange extends Model {
    //constants
    const TYPE_SCHOOL_DAY       = "DIA_LECTIVO";
    const TYPE_NO_SCHOOL_DAY    = "DIA_NO_LECTIVO";
    const TYPE_VACATION         = "VACACION";
    const TYPE_EXAM_PERIOD      = "PERIODO_EXAMEN";
    const TYPE_CLOSING_ACT      = "PERIODO_CIERRE_ACTA";
    
    /*
     * Private
     */
    private $colors = [
      self::TYPE_SCHOOL_DAY     => "#F44336",
      self::TYPE_NO_SCHOOL_DAY  => "#fbc02d",
      self::TYPE_VACATION       => "#9c27b0",
      self::TYPE_EXAM_PERIOD    => "#4CAF50",
      self::TYPE_CLOSING_ACT    => "#2196F3"
    ];
    
    protected $proposalAcademicCalendarRangeId;
    protected $type;
    protected $description;
    protected $startDate;
    protected $finalDate;
    protected $proposalAcademicCalendarId;
    
    function getProposalAcademicCalendarRangeId() {
        return $this->proposalAcademicCalendarRangeId;
    }

    function getType() {
        return $this->type;
    }

    function getDescription() {
        return $this->description;
    }

    function getStartDate() {
        return $this->startDate;
    }

    function getFinalDate() {
        return $this->finalDate;
    }

    function getProposalAcademicCalendarId() {
        return $this->proposalAcademicCalendarId;
    }

    function setProposalAcademicCalendarRangeId($proposalAcademicCalendarRangeId) {
        $this->proposalAcademicCalendarRangeId = $proposalAcademicCalendarRangeId;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setStartDate($startDate) {
        $this->startDate = $startDate;
    }

    function setFinalDate($finalDate) {
        $this->finalDate = $finalDate;
    }

    function setProposalAcademicCalendarId($proposalAcademicCalendarId) {
        $this->proposalAcademicCalendarId = $proposalAcademicCalendarId;
    }

    /*
     * Other functions
     */
    function toJsonCalendarEvent(){
        return (object) [
            "id"    => $this->getProposalAcademicCalendarRangeId(),
            "title" => $this->getDescription(),
            "start" => $this->getStartDate(),
            "end"   => $this->getFinalDate(),
            "color" => $this->colors[$this->getType()]
        ];
    }
}
