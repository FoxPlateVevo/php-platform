<?php

require_once __PATH__ . "/app/model/Model.php";

class FeePayment extends Model {
    //constants
    const STATUS_CANCELED       = "CANCELADO";
    const STATUS_PENDING        = "PENDIENTE";
    const STATUS_OUT_OF_DATE    = "FUERA_FECHA";
    
    protected $feePaymentId;
    protected $saleId;
    protected $paymentDate;
    protected $amount;
    protected $status;
    
    function getFeePaymentId() {
        return $this->feePaymentId;
    }

    function getSaleId() {
        return $this->saleId;
    }

    function getPaymentDate() {
        return $this->paymentDate;
    }

    function getAmount() {
        return $this->amount;
    }

    function getStatus() {
        return $this->status;
    }

    function setFeePaymentId($feePaymentId) {
        $this->feePaymentId = $feePaymentId;
    }

    function setSaleId($saleId) {
        $this->saleId = $saleId;
    }

    function setPaymentDate($paymentDate) {
        $this->paymentDate = $paymentDate;
    }

    function setAmount($amount) {
        $this->amount = $amount;
    }

    function setStatus($status) {
        $this->status = $status;
    }


}
