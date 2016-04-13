<?php

require_once __PATH__ . "/app/model/Model.php";

class QuoteList extends Model {
    protected $quoteListId;
    protected $registryDate;
    protected $responsibleUserId;
    protected $storeId;

    function getQuoteListId() {
        return $this->quoteListId;
    }

    function getRegistryDate() {
        return $this->registryDate;
    }

    function getResponsibleUserId() {
        return $this->responsibleUserId;
    }

    function getStoreId() {
        return $this->storeId;
    }

    function setQuoteListId($quoteListId) {
        $this->quoteListId = $quoteListId;
    }

    function setRegistryDate($registryDate) {
        $this->registryDate = $registryDate;
    }

    function setResponsibleUserId($responsibleUserId) {
        $this->responsibleUserId = $responsibleUserId;
    }

    function setStoreId($storeId) {
        $this->storeId = $storeId;
    }


}
