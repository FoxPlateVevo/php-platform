<?php
require_once __PATH__ . "/app/service/Product.php";

/*
 * Set service and resource data
 */
$product            = new Service_Product();
$productsResource   = $product->products;
$storeItemsResource = $product->storeItems;

$this->respond("GET", "/?", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource) {
    /*
     * List all products
     */
    $storeItems = $storeItemsResource->listStoreItems([
        "status" => Store::STATUS_STRUCTURED
    ]);
    
    $productIds = array_map(function(Store $storeItem){
        return $storeItem->getProductId();
    }, $storeItems);
    
    $products = $productsResource->listProducts([
        "productId" => $productIds
    ]);
    
    //header params
    $service->pageTitle = "Mis Productos";
    
    //content params
    $service->storeItems    = $storeItems;
    $service->products      = $products;
    
    //render
    $service->render(__PATH__ . "/app/view/product/product.list.phtml");
});

