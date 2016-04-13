<?php
require_once __PATH__ . "/app/service/Store.php";

/*
 * Set service and resource data
 */
$store                                      = new Service_Store();
$discountsResource                          = $store->discounts;
$enrollmentsResource                        = $store->enrollments;
$interestsResource                          = $store->interests;
$salesResource                          = $store->sales;
$saleDetailsResource                          = $store->saleDetails;

/*
 * Countries
 */
$this->respond("GET", "/?", function ($request, $response, $service) use ($salesResource) {
    /*
     * List all sales 
     */
    $sales = $salesResource->listSales();
    
    //header params
    $service->pageTitle = "Ventas";
    
    //content params
    $service->sales = $sales;
    
    //render
    $service->render(__PATH__ . "/app/view/sale/list.phtml");
});

$this->respond("GET", "/create/?", function ($request, $response, $service) use ($salesResource) {
    //header params
    $service->pageTitle = "Crear País";
    
    //render
    $service->render(__PATH__ . "/app/view/sale/create.phtml");
});

$this->respond("POST", "/create/?", function ($request, $response, $service) use ($salesResource, $saleDetailsResource) {
    $vendorUserId = $request->param("vendorUserId");
    $emissionDate = $request->param("emissionDate");
    $expirationDate = $request->param("expirationDate");
    $clientUserId = $request->param("clientUserId");
    $typeCoin = $request->param("typeCoin");
    $comment = $request->param("comment");
    $detail = $request->param("detail");
    
    /*
     * Register the sale
     */
    $insertedSaleId = $salesResource->insert(new Sale([
        "vendorUserId" => $vendorUserId,
        "clientUserId" => $clientUserId,
        "registryDate" => get_datetime(),
        "emissionDate" => $emissionDate,
        "expirationDate" => $expirationDate,
        "typeCoin" => $typeCoin,
        "paymentMethod" => Sale::PAYMET_METHOD_CASH_PAYMENT,
        "comment" => $comment,
        "status" => Sale::STATUS_PENDING,
        "feePaymentDocumentRegistryDate" => null,
        "feePaymentQuantity" => null,
    ]));
    
    if((bool) $insertedSaleId){
        /*
        * Register Detail
        */
//        vd($detail);
        foreach ($detail as $item){
            $item = (object) $item;
            
            $saleDetailsResource->insert(new SaleDetail([
                "storeId" => $item->storeId,
                "saleId" => $insertedSaleId,
                "quantity" => $item->quantity,
                "price" => $item->price,
                "discount" => $item->discount,
            ]));
        }
    }
    
    $response->json((bool) $insertedSaleId);
});

$this->respond("GET", "/confirm-enrollment/?", function ($request, $response, $service) use ($salesResource) {
    $saleId = $request->param("saleId");
    $storeId = $request->param("storeId");
    
    $item = db::fetchOne("SELECT 
        cod_almacen, 
        cod_venta,
        cantidad
        FROM det_venta
        WHERE cod_almacen = '{$storeId}'
        AND cod_venta = '{$saleId}'");
    
    $cantidad = (int) $item->cantidad;
    
    /*
     * Obtener matrículas
     */
    $matriculas = db::fetchAll("SELECT
    M.cod_matricula
    FROM matricula M
    INNER JOIN grupo G ON M.cod_grupo = G.cod_grupo
    INNER JOIN almacen A ON A.cod_almacen = G.cod_almacen
    WHERE M.cod_venta = '{$saleId}'
    AND A.cod_almacen = '{$storeId}'");
    
//    vd($cantidad);
//    vd(count($matriculas));
    $success =  count($matriculas) < $cantidad;
    
    $response->json($success);
});

/* Searchers */
$this->respond("GET", "/sales-searcher/?[:filterSaleIds]?", function ($request, $response, $service)
use ($salesResource) {
    $filterSaleIds = $request->param("filterSaleIds");
    
    /*
     * List all sales
     */
    $sales = $salesResource->listSales();
    
    //header params
    $service->pageTitle = "Buscar Venta";
    
    //content params
    $service->sales          = $sales;
    $service->filterSaleIds    = ($filterSaleIds)? explode(",", $filterSaleIds) : null;
    
    //render
    $service->layout(__PATH__ . "/app/view/layouts/default.to-search.phtml");
    $service->render(__PATH__ . "/app/view/sale/sales-searcher.phtml");
});




$this->respond("GET", "/sample?", function ($request, $response, $service) use ($countriesResource) {
    $tablesData = db::fetchAll("SHOW TABLES");
    
    $tables = array_map(function($tableData){
        return $tableData->Tables_in_platform;
    }, $tablesData);
    
//    vd($tables);
    
    foreach($tables as $table){
        $columns = db::fetchAll("SHOW COLUMNS FROM {$table}");
        
        $SQLString = "
            SELECT";
        
        foreach($columns as $column){
            $SQLString .= "
            {$column->Field},";
        }
        
        $SQLString .= "
            FROM {$table}
            {\$CONDITIONALS_PART_STRING}
            ";
        
        vd($SQLString);
    }
});
$this->respond("GET", "/sample2?", function ($request, $response, $service) use ($countriesResource) {
    require_once __PATH__ . "/app/model/SaleDetail.php";
    
    __class(new SaleDetail());
});