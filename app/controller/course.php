<?php
require_once __PATH__ . "/app/service/Administration.php";
require_once __PATH__ . "/app/service/Course.php";
require_once __PATH__ . "/app/service/Product.php";
require_once __PATH__ . "/app/service/Store.php";
require_once __PATH__ . "/app/service/User.php";

/*
 * Set service and resource data
 */
$administration                             = new Service_Administration();
$categoriesResource                         = $administration->categories;

$course                                     = new Service_Course();
$professorDetailsResource                   = $course->professorDetails;
$unitsResource                              = $course->units;
$sessionsResource                           = $course->sessions;
$materialsResource                          = $course->materials;

$product                                    = new Service_Product();
$groupsResource                             = $product->groups;
$productsResource                           = $product->products;
$storeItemsResource                         = $product->storeItems;

$store                                      = new Service_Store();
$discountsResource                          = $store->discounts;
$enrollmentsResource                        = $store->enrollments;
$interestsResource                          = $store->interests;

$user = new Service_User();
$usersResource = $user->users;
$userProfilesResource       = $user->userProfiles;
$userProfileDetailsResource = $user->userProfileDetails;

$this->respond("GET", "/[i:storeId]", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $categoriesResource, 
$unitsResource, $sessionsResource, $materialsResource){
    $storeId = $request->param("storeId");
    
    /*
     * Get course
     */
    $storeItem  = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($storeItem->getProductId());
    $category   = $categoriesResource->get($product->getCategoryId());
    
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course();
    
    $units = $unitsResource->listUnits([
        "courseId" => $finalProduct->getCourseId()
    ]);
    
    $materialIds = [];
    
    $unitsWithSessions = array_map(function(Unity $unit) use ($sessionsResource, &$materialIds){
        $sessions = $sessionsResource->listSessions([
            "unityId" => $unit->getUnityId()
        ]);
        
        $unit->sessions = $sessions;
        
        foreach ($sessions as $session){
//            $session = new Session;
            
            array_push($materialIds, $session->getMaterialId());
        }
        
        return $unit;
    }, $units);
    
    $materials = $materialsResource->listMaterials([
        "materialId" => $materialIds 
    ]);
    
//    $storeItem = new Store();
//    $product = new Product;
//    $category = new Category();
    
    //header params
    $service->pageTitle = $product->getName();
        
    //content params
    $service->storeItem = $storeItem;
    $service->product   = $product;
    $service->category  = $category;
    $service->units     = $unitsWithSessions;
    $service->materials = $materials;
    
    //render
    $service->render(__PATH__ . "/app/view/course/main.phtml");
});

$this->respond("GET", "/[i:storeId]/unit/[i:unitId]/session/[i:sessionId]", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $categoriesResource, 
$unitsResource, $sessionsResource, $materialsResource){
    $storeId    = $request->param("storeId");
    $unitId     = $request->param("unitId");
    $sessionId  = $request->param("sessionId");
    
    /*
     * Get course
     */
    $storeItem  = $storeItemsResource->get($storeId);
    $unit = $unitsResource->get($unitId);
    $session = $sessionsResource->get($sessionId);
    $material = $materialsResource->get($session->getMaterialId());
    
//    $storeItem = new Store();
//    $sesion = new Session;
    
    //content params
    $service->unit      = $unit;
    $service->session   = $session;
    $service->material  = $material;
    
    //render
    $service->layout(__PATH__ . "/app/view/layouts/empty.phtml");
    $service->render(__PATH__ . "/app/view/course/session-preview.phtml");
});