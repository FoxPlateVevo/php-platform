<?php

require_once __PATH__ . "/app/model/Model.php";

class Store extends Model {
    //constants
    const TYPE_ENTRY_BY_DEFULT                          = "POR_DEFECTO";
    const TYPE_ENTRY_BY_REOPENING                       = "POR_REAPERTURA";
    const TYPE_ENTRY_BY_PRODUCT_STRUCTURE               = "POR_ESTRUCTURA_PRODUCTO";
    const STATUS_STRUCTURING                            = "ESTRUCTURANDO";
    const STATUS_STRUCTURED                             = "ESTRUCTURADO";
    const STATUS_DEVELOPING                             = "DESARROLLANDO";
    const STATUS_CANCELED                               = "CANCELADO";
    const STATUS_CLOSED                                 = "CERRADO";
    const TYPE_COIN_PEN                                 = "PEN";
    const TYPE_COIN_USD                                 = "USD";
    const SALE_TYPE_PRESENTATION_MULTIMEDIA_FILE        = "ARCHIVO_MULTIMEDIA";
    const SALE_TYPE_PRESENTATION_EMBEDDED_RESOURCE      = "RECURSO_EMBEBIDO";
    
    protected $storeId;
    protected $typeEntry;
    protected $registryDate;
    protected $status;
    protected $productId;
    protected $typeCoin;
    protected $price;
    protected $minimunPrice;
    protected $stock;
    protected $saleInformation;
    protected $saleTypePresentation;
    protected $saleContentPresentation;
    protected $isConfiguredGroup;
    
    function getStoreId() {
        return $this->storeId;
    }

    function getTypeEntry() {
        return $this->typeEntry;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getStatus() {
        return $this->status;
    }

    function getProductId() {
        return $this->productId;
    }

    function getTypeCoin() {
        return $this->typeCoin;
    }

    function getPrice() {
        return $this->price;
    }

    function getMinimunPrice() {
        return $this->minimunPrice;
    }

    function getStock() {
        return $this->stock;
    }

    function getSaleInformation() {
        return $this->saleInformation;
    }

    function getSaleTypePresentation() {
        return $this->saleTypePresentation;
    }

    function getSaleContentPresentation() {
        return $this->saleContentPresentation;
    }

    function getIsConfiguredGroup() {
        return $this->isConfiguredGroup;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }

    function setTypeEntry($typeEntry) {
        $this->typeEntry = $typeEntry;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setStatus($status) {
        $this->status = $status;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setTypeCoin($typeCoin) {
        $this->typeCoin = $typeCoin;
    }

    function setPrice($price) {
        $this->price = $price;
    }

    function setMinimunPrice($minimunPrice) {
        $this->minimunPrice = $minimunPrice;
    }

    function setStock($stock) {
        $this->stock = $stock;
    }

    function setSaleInformation($saleInformation) {
        $this->saleInformation = $saleInformation;
    }

    function setSaleTypePresentation($saleTypePresentation) {
        $this->saleTypePresentation = $saleTypePresentation;
    }

    function setSaleContentPresentation($saleContentPresentation) {
        $this->saleContentPresentation = $saleContentPresentation;
    }

    function setIsConfiguredGroup($isConfiguredGroup) {
        $this->isConfiguredGroup = $isConfiguredGroup;
    }


}
