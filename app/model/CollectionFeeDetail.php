<?php

require_once __PATH__ . "/app/model/Model.php";

class CollectionFeeDetail extends Model {
    protected $feePaymentId;
    protected $collectionId;
    protected $concept;
    
    function getFeePaymentId() {
        return $this->feePaymentId;
    }

    function getCollectionId() {
        return $this->collectionId;
    }

    function getConcept() {
        return $this->concept;
    }

    function setFeePaymentId($feePaymentId) {
        $this->feePaymentId = $feePaymentId;
    }

    function setCollectionId($collectionId) {
        $this->collectionId = $collectionId;
    }

    function setConcept($concept) {
        $this->concept = $concept;
    }


}
