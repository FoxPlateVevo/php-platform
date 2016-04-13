<?php

require_once __PATH__ . "/app/model/Discount.php";
require_once __PATH__ . "/app/model/Interest.php";
require_once __PATH__ . "/app/model/Enrollment.php";
require_once __PATH__ . "/app/model/Sale.php";
require_once __PATH__ . "/app/model/SaleDetail.php";

class Service_Store {
    public $discounts;
    public $interests;
    public $enrollments;
    public $sales;
    public $saleDetails;
    public $concepts;
    public $evaluations;
    public $exams;
    public $questionGroups;
    public $questions;
    public $answers;
    public $studentEvaluationDetails;
    public $studentEvaluations;
    
    public function __construct() {
        $this->discounts    = new Service_Store_Discounts_Resource();
        $this->interests    = new Service_Store_Interests_Resource();
        $this->enrollments  = new Service_Store_Enrollments_Resource();
        $this->sales        = new Service_Store_Sales_Resource();
        $this->saleDetails  = new Service_Store_SaleDetails_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Store_Discounts_Resource {
    public function listDiscounts(array $optionParams = null) {
        /*
         * discountId       : optional String, Array
         * type             : optional String, Array
         * storeId          : optional String, Array
         */
        $alternateAttributes = [
            "discountId"    => "cod_descuento",
            "type"          => "tipo",
            "storeId"       => "cod_almacen",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_descuento,
        tipo,
        valor,
        descripcion,
        cod_almacen
        FROM descuento
        {$CONDITIONALS_PART_STRING}
        ";
        
        $discountsData = db::fetchAll($query);
        
        $discounts = [];
        
        foreach ($discountsData as $discountData){
            $discounts[$discountData->cod_descuento] = new Discount([
                "discountId"        => $discountData->cod_descuento,
                "type"              => $discountData->tipo,
                "value"             => $discountData->valor,
                "description"       => $discountData->descripcion,
                "storeId"           => $discountData->cod_almacen,
            ]);
        }
        
        return $discounts;
    }
    
    public function insert(Discount $discount){
        $insertedDiscountId = db::insert("descuento", [
            "tipo"          => $discount->getType(),
            "valor"         => $discount->getValue(),
            "descripcion"   => $discount->getDescription(),
            "cod_almacen"   => $discount->getStoreId(),
        ]);
        
        return $insertedDiscountId;
    }
    
    public function get($discountId){
        return array_pop($this->listDiscounts([
            "discountId" => $discountId
        ]));
    }
    
    public function update(Discount $discount){
        $affectedRows = db::update("descuento", [
            "tipo"          => $discount->getType(),
            "valor"         => $discount->getValue(),
            "descripcion"   => $discount->getDescription(),
            "cod_almacen"   => $discount->getStoreId(),
        ], [
            "cod_descuento" => $discount->getDiscountId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($discountId){
        $affectedRows = db::delete("descuento", [
            "cod_descuento" => $discountId
        ]);
        
        return $affectedRows;
    }
}

class Service_Store_Interests_Resource {
    public function listInterests(array $optionParams = null) {
        /*
         * interestId   : optional String, Array
         * type         : optional String, Array
         * storeId      : optional String, Array
         */
        $alternateAttributes = [
            "interestId"    => "cod_interes",
            "type"          => "tipo",
            "storeId"       => "cod_almacen",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_interes,
        tipo,
        valor,
        descripcion,
        cod_almacen
        FROM interes
        {$CONDITIONALS_PART_STRING}
        ";
        
        $interestsData = db::fetchAll($query);
        
        $interests = [];
        
        foreach ($interestsData as $interestData){
            $interests[$interestData->cod_interes] = new Interest([
                "interestId"        => $interestData->cod_interes,
                "type"              => $interestData->tipo,
                "value"             => $interestData->valor,
                "description"       => $interestData->descripcion,
                "storeId"           => $interestData->cod_almacen,
            ]);
        }
        
        return $interests;
    }
    
    public function insert(Interest $interest){
        $insertedInterestId = db::insert("interes", [
            "tipo"          => $interest->getType(),
            "valor"         => $interest->getValue(),
            "descripcion"   => $interest->getDescription(),
            "cod_almacen"   => $interest->getStoreId(),
        ]);
        
        return $insertedInterestId;
    }
    
    public function get($interestId){
        return array_pop($this->listInterests([
            "interestId" => $interestId
        ]));
    }
    
    public function update(Interest $interest){
        $affectedRows = db::update("interes", [
            "tipo"          => $interest->getType(),
            "valor"         => $interest->getValue(),
            "descripcion"   => $interest->getDescription(),
            "cod_almacen"   => $interest->getStoreId(),
        ], [
            "cod_interes" => $interest->getInterestId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($interestId){
        $affectedRows = db::delete("interes", [
            "cod_interes" => $interestId
        ]);
        
        return $affectedRows;
    }
}

class Service_Store_Enrollments_Resource {
    public function listEnrollments(array $optionParams = null) {
        /*
         * enrollmentId     : optional String, Array
         * saleId           : optional String, Array
         * studentUserId    : optional String, Array
         * groupId          : optional String, Array
         * status           : optional String, Array
         * responsibleUserId    : optional String, Array
         */
        $alternateAttributes = [
            "enrollmentId"      => "cod_matricula",
            "saleId"            => "cod_venta",
            "studentUserId"     => "cod_usuario_alumno",
            "groupId"           => "cod_grupo",
            "status"            => "estado",
            "responsibleUserId" => "cod_usuario_responsable",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        cod_matricula,
        cod_venta,
        cod_usuario_alumno,
        cod_grupo,
        fecha_registro,
        observacion,
        estado,
        cod_usuario_responsable
        FROM matricula
        {$CONDITIONALS_PART_STRING}
        ";
        
        $enrollmentsData = db::fetchAll($query);
        
        $enrollments = [];
        
        foreach ($enrollmentsData as $enrollmentData){
            $enrollments[$enrollmentData->cod_matricula] = new Enrollment([
                "enrollmentId"      => $enrollmentData->cod_matricula,
                "saleId"            => $enrollmentData->cod_venta,
                "studentUserId"     => $enrollmentData->cod_usuario_alumno,
                "groupId"           => $enrollmentData->cod_grupo,
                "registryDate"      => $enrollmentData->fecha_registro,
                "observation"       => $enrollmentData->observacion,
                "status"            => $enrollmentData->estado,
                "responsibleUserId" => $enrollmentData->cod_usuario_responsable,
            ]);
        }
        
        return $enrollments;
    }
    
    public function insert(Enrollment $enrollment){
        $insertedEnrollmentId = db::insert("matricula", [
            "cod_venta"                 => $enrollment->getSaleId(),
            "cod_usuario_alumno"        => $enrollment->getStudentUserId(),
            "cod_grupo"                 => $enrollment->getGroupId(),
            "fecha_registro"            => $enrollment->getRegistryDate(),
            "observacion"               => $enrollment->getObservation(),
            "estado"                    => $enrollment->getStatus(),
            "cod_usuario_responsable"   => $enrollment->getResponsibleUserId(),
        ]);
        
        return $insertedEnrollmentId;
    }
    
    public function get($enrollmentId){
        return array_pop($this->listEnrollments([
            "enrollmentId" => $enrollmentId
        ]));
    }
    
    public function update(Enrollment $enrollment){
        $affectedRows = db::update("matricula", [
            "cod_venta"                 => $enrollment->getSaleId(),
            "cod_usuario_alumno"        => $enrollment->getStudentUserId(),
            "cod_grupo"                 => $enrollment->getGroupId(),
            "fecha_registro"            => $enrollment->getRegistryDate(),
            "observacion"               => $enrollment->getObservation(),
            "estado"                    => $enrollment->getStatus(),
            "cod_usuario_responsable"   => $enrollment->getResponsibleUserId(),
        ], [
            "cod_matricula" => $enrollment->getEnrollmentId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($enrollmentId){
        $affectedRows = db::delete("matricula", [
            "cod_matricula" => $enrollmentId
        ]);
        
        return $affectedRows;
    }
}

class Service_Store_Sales_Resource {
    public function listSales(array $optionParams = null) {
        /*
         * storeId      : optional String, Array
         */
        $alternateAttributes = [
            "saleId"    => "cod_venta",
            "clientUserId"          => "cod_usuario_cliente",
            "vendorUserId"       => "cod_usuario_vendedor",
            "status"       => "estado",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
            SELECT
            cod_venta,
            fecha_registro,
            cod_usuario_cliente,
            cod_usuario_vendedor,
            fecha_emision,
            fecha_vencimiento,
            tipo_moneda,
            modalidad_pago,
            comentario,
            estado,
            fecha_registro_documento_cuota_pago,
            cantidad_cuota_pago
            FROM venta
            {$CONDITIONALS_PART_STRING}
            ";
        
        $objectsData = db::fetchAll($query);
        
        $objects = [];
        
        foreach ($objectsData as $objectData){
            $objects[$objectData->cod_venta] = new Sale([
                "saleId" => $objectData->cod_venta,
                "vendorUserId" => $objectData->cod_usuario_vendedor,
                "clientUserId" => $objectData->cod_usuario_cliente,
                "registryDate" => $objectData->fecha_registro,
                "emissionDate" => $objectData->fecha_emision,
                "expirationDate" => $objectData->fecha_vencimiento,
                "typeCoin" => $objectData->tipo_moneda,
                "paymentMethod" => $objectData->modalidad_pago,
                "comment" => $objectData->comentario,
                "status" => $objectData->estado,
                "feePaymentDocumentRegistryDate" => $objectData->fecha_registro_documento_cuota_pago,
                "feePaymentQuantity" => $objectData->cantidad_cuota_pago,
            ]);
        }
        
        return $objects;
    }
    
    public function insert(Sale $object){
        $insertedId = db::insert("venta", [
            "fecha_registro" => $object->getRegistryDate(),
            "cod_usuario_cliente" => $object->getClientUserId(),
            "cod_usuario_vendedor" => $object->getVendorUserId(),
            "fecha_emision" => $object->getEmissionDate(),
            "fecha_vencimiento" => $object->getExpirationDate(),
            "tipo_moneda" => $object->getTypeCoin(),
            "modalidad_pago" => $object->getPaymentMethod(),
            "comentario" => $object->getComment(),
            "estado" => $object->getStatus(),
            "fecha_registro_documento_cuota_pago" => $object->getFeePaymentDocumentRegistryDate(),
            "cantidad_cuota_pago" => $object->getFeePaymentQuantity(),
        ]);
        
        return $insertedId;
    }
    
    public function get($id){
        return array_pop($this->listSales([
            "saleId" => $id
        ]));
    }
    
    public function update(Sale $object){
        $affectedRows = db::update("venta", [
            "fecha_registro" => $object->getRegistryDate(),
            "cod_usuario_cliente" => $object->getClientUserId(),
            "cod_usuario_vendedor" => $object->getVendorUserId(),
            "fecha_emision" => $object->getEmissionDate(),
            "fecha_vencimiento" => $object->getExpirationDate(),
            "tipo_moneda" => $object->getTypeCoin(),
            "modalidad_pago" => $object->getPaymentMethod(),
            "comentario" => $object->getComment(),
            "estado" => $object->getStatus(),
            "fecha_registro_documento_cuota_pago" => $object->getFeePaymentDocumentRegistryDate(),
            "cantidad_cuota_pago" => $object->getFeePaymentQuantity(),
        ], [
            "cod_venta" => $object->getSaleId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($id){
        $affectedRows = db::delete("venta", [
            "cod_venta" => $id
        ]);
        
        return $affectedRows;
    }
}

class Service_Store_SaleDetails_Resource {
    public function listSaleDetails(array $optionParams = null) {
        $alternateAttributes = [
            "storeId"       => "cod_almacen",
            "saleId"        => "cod_venta",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
            SELECT
            precio,
            cantidad,
            descuento,
            cod_almacen,
            cod_venta,
            FROM det_venta
            {$CONDITIONALS_PART_STRING}
            ";
        
        $objectsData = db::fetchAll($query);
        
        $objects = [];
        
        foreach ($objectsData as $objectData){
            $objects[] = new SaleDetail([
                "storeId" => $objectData->cod_almacen,
                "saleId" => $objectData->cod_venta,
                "quantity" => $objectData->cantidad,
                "price" => $objectData->precio,
                "discount" => $objectData->descuento,
            ]);
        }
        
        return $objects;
    }
    
    public function insert(SaleDetail $object){
        $insertedId = db::insert("det_venta", [
            "cod_almacen" => $object->getStoreId(),
            "cod_venta" => $object->getSaleId(),
            "cantidad" => $object->getQuantity(),
            "precio" => $object->getPrice(),
            "descuento" => $object->getDiscount(),
        ]);
        
        return $insertedId;
    }
}