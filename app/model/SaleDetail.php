<?php

require_once __PATH__ . "/app/model/Model.php";

class SaleDetail extends Model {
    protected $storeId;
    protected $saleId;
    protected $quantity;
    protected $price;
    protected $discount;
    
    function getStoreId() {
        return $this->storeId;
    }

    function getSaleId() {
        return $this->saleId;
    }

    function getQuantity() {
        return $this->quantity;
    }

    function getPrice() {
        return $this->price;
    }

    function getDiscount() {
        return $this->discount;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    function setSaleId($saleId) {
        $this->saleId = $saleId;
    }

    function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setDiscount($discount) {
        $this->discount = $discount;
    }


}
