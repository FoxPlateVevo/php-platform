
<?php

require_once __PATH__ . "/app/model/Model.php";

class Sale extends Model {
    //constants
    const TYPE_COIN_PEN                 = "PEN";
    const TYPE_COIN_USD                 = "USD";
    const PAYMET_METHOD_CASH_PAYMENT    = "CONTADO";
    const PAYMET_METHOD_FEE_PAYMENT     = "CUOTA_PAGO";
    const STATUS_PENDING                = "PENDIENTE";
    const STATUS_CANCELED               = "CANCELADO";
    
    protected $saleId;
    protected $vendorUserId;
    protected $clientUserId;
    protected $registryDate;
    protected $emissionDate;
    protected $expirationDate;
    protected $typeCoin;
    protected $paymentMethod;
    protected $comment;
    protected $status;
    protected $feePaymentDocumentRegistryDate;
    protected $feePaymentQuantity;

    function getSaleId() {
        return $this->saleId;
    }

    function getVendorUserId() {
        return $this->vendorUserId;
    }

    function getClientUserId() {
        return $this->clientUserId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getEmissionDate() {
        return $this->emissionDate;
    }

    function getExpirationDate() {
        return $this->expirationDate;
    }

    function getTypeCoin() {
        return $this->typeCoin;
    }

    function getPaymentMethod() {
        return $this->paymentMethod;
    }

    function getComment() {
        return $this->comment;
    }

    function getStatus() {
        return $this->status;
    }

    function getFeePaymentDocumentRegistryDate() {
        return $this->feePaymentDocumentRegistryDate;
    }

    function getFeePaymentQuantity() {
        return $this->feePaymentQuantity;
    }

    function setSaleId($saleId) {
        $this->saleId = $saleId;
    }

    function setVendorUserId($vendorUserId) {
        $this->vendorUserId = $vendorUserId;
    }

    function setClientUserId($clientUserId) {
        $this->clientUserId = $clientUserId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setEmissionDate($emissionDate) {
        $this->emissionDate = $emissionDate;
    }

    function setExpirationDate($expirationDate) {
        $this->expirationDate = $expirationDate;
    }

    function setTypeCoin($typeCoin) {
        $this->typeCoin = $typeCoin;
    }

    function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    function setComment($comment) {
        $this->comment = $comment;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setFeePaymentDocumentRegistryDate($feePaymentDocumentRegistryDate) {
        $this->feePaymentDocumentRegistryDate = $feePaymentDocumentRegistryDate;
    }

    function setFeePaymentQuantity($feePaymentQuantity) {
        $this->feePaymentQuantity = $feePaymentQuantity;
    }


}
