<?php

require_once __PATH__ . "/app/model/Model.php";

class Program extends Model {
    //constants
    const TYPE_SEMINARY     = "SEMINARIO";
    const TYPE_DIPLOMAED    = "DIPLOMADO";
    
    protected $programId;
    protected $type;
    protected $productId;
    protected $certifierUserId;
    
    function getProgramId() {
        return $this->programId;
    }

    function getType() {
        return $this->type;
    }

    function getProductId() {
        return $this->productId;
    }

    function getCertifierUserId() {
        return $this->certifierUserId;
    }

    function setProgramId($programId) {
        $this->programId = $programId;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setProductId($productId) {
        $this->productId = $productId;
    }

    function setCertifierUserId($certifierUserId) {
        $this->certifierUserId = $certifierUserId;
    }


}
