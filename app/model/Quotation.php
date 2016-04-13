<?php

require_once __PATH__ . "/app/model/Model.php";

class Quotation extends Model {
    //constants
    const PAYMET_METHOD_CASH            = "EFECTIVO";
    const PAYMET_METHOD_CARD            = "TARJETA";
    const DELIVERY_METHOD_IMMEDIATE     = "INMEDIATO";
    const DELIVERY_METHOD_POSTPONED     = "APLAZADO";
    const TYPE_COIN_PEN                 = "PEN";
    const TYPE_COIN_USD                 = "USD";
    const STATUS_PENDING                = "PENDIENTE";
    const STATUS_ENABLED                = "HABILITADO";
    const STATUS_DISABLED               = "DESHABILITADO";
    
    protected $quotationId;
    protected $providerUserId;
    protected $emissionDate;
    protected $paymentMethod;
    protected $deliveryMethod;
    protected $typeCoin;
    protected $description;
    protected $observation;
    protected $price;
    protected $minimunPrice;
    protected $isIGVincluded;
    protected $presentationContent;
    protected $status;
    protected $quoteListId;
    protected $responsibleUserId;
    
    function getQuotationId() {
        return $this->quotationId;
    }

    function getProviderUserId() {
        return $this->providerUserId;
    }

    function getEmissionDate() {
        return $this->emissionDate;
    }

    function getPaymentMethod() {
        return $this->paymentMethod;
    }

    function getDeliveryMethod() {
        return $this->deliveryMethod;
    }

    function getTypeCoin() {
        return $this->typeCoin;
    }

    function getDescription() {
        return $this->description;
    }

    function getObservation() {
        return $this->observation;
    }

    function getPrice() {
        return $this->price;
    }

    function getMinimunPrice() {
        return $this->minimunPrice;
    }

    function getIsIGVincluded() {
        return $this->isIGVincluded;
    }

    function getPresentationContent() {
        return $this->presentationContent;
    }

    function getStatus() {
        return $this->status;
    }

    function getQuoteListId() {
        return $this->quoteListId;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function setQuotationId($quotationId) {
        $this->quotationId = $quotationId;
    }

    function setProviderUserId($providerUserId) {
        $this->providerUserId = $providerUserId;
    }

    function setEmissionDate($emissionDate) {
        $this->emissionDate = $emissionDate;
    }

    function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    function setDeliveryMethod($deliveryMethod) {
        $this->deliveryMethod = $deliveryMethod;
    }

    function setTypeCoin($typeCoin) {
        $this->typeCoin = $typeCoin;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setObservation($observation) {
        $this->observation = $observation;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setMinimunPrice($minimunPrice) {
        $this->minimunPrice = $minimunPrice;
    }

    function setIsIGVincluded($isIGVincluded) {
        $this->isIGVincluded = $isIGVincluded;
    }

    function setPresentationContent($presentationContent) {
        $this->presentationContent = $presentationContent;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setQuoteListId($quoteListId) {
        $this->quoteListId = $quoteListId;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }


}
