<?php

require_once __PATH__ . "/app/model/Model.php";

class Collection extends Model {
    //constants
    const PAYMET_METHOD_CASH    = "EFECTIVO";
    const PAYMET_METHOD_CARD    = "TARJETA";
    const DOCUMENT_TYPE_INVOICE = "FACTURA";
    const DOCUMENT_TYPE_TICKET  = "BOLETA";
    
    protected $collectionId;
    protected $emissionDate;
    protected $responsibleUserId;
    protected $paymentMethod;
    protected $documentType;
    protected $comment;
    
    function getCollectionId() {
        return $this->collectionId;
    }

    function getEmissionDate() {
        return $this->emissionDate;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getPaymentMethod() {
        return $this->paymentMethod;
    }

    function getDocumentType() {
        return $this->documentType;
    }

    function getComment() {
        return $this->comment;
    }

    function setCollectionId($collectionId) {
        $this->collectionId = $collectionId;
    }

    function setEmissionDate($emissionDate) {
        $this->emissionDate = $emissionDate;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    function setDocumentType($documentType) {
        $this->documentType = $documentType;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }


}
