<?php

require_once __PATH__ . "/app/model/Model.php";

class CollectionInterestDetail extends Model {
    protected $interestId;
    protected $collectionId;
    protected $concept;
    
    function getInterestId() {
        return $this->interestId;
    }

    function getCollectionId() {
        return $this->collectionId;
    }

    function getConcept() {
        return $this->concept;
    }

    function setInterestId($interestId) {
        $this->interestId = $interestId;
    }

    function setCollectionId($collectionId) {
        $this->collectionId = $collectionId;
    }

    function setConcept($concept) {
        $this->concept = $concept;
    }


}
