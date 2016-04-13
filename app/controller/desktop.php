<?php
require_once __PATH__ . "/app/service/Product.php";
require_once __PATH__ . "/app/service/Store.php";
require_once __PATH__ . "/app/service/User.php";

/*
 * Set service and resource data
 */
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

$this->respond("GET", "/?", function ($request, $response, $service) 
use ($enrollmentsResource, $storeItemsResource, $groupsResource, $productsResource){
    /*
     * Get enable enrollments
     * Get courses
     */
    $user = get_user_session();
//    $user = new User;
    
    $enrollments = $enrollmentsResource->listEnrollments([
        "studentUserId" => $user->getUserId()
    ]);
    
    $groupIds = array_map(function(Enrollment $enrollment){
        return $enrollment->getGroupId();
    }, $enrollments);
    
    $groups = $groupsResource->listGroups([
        "groupId" =>$groupIds
    ]);
    
    $storeIds = array_map(function(Group $group){
        return $group->getStoreId();
    }, $groups);
    
    $storeItems = $storeItemsResource->listStoreItems([
        "storeId" => $storeIds
    ]);
    
    $productIds = array_map(function(Store $storeItem){
        return $storeItem->getProductId();
    }, $storeItems);
    
    $products = $productsResource->listProducts([
        "productId" => $productIds
    ]);
    
    //header params
    $service->pageTitle = "Mi Escritorio";
        
    //content params
    $service->products = $products;
    $service->storeItems = $storeItems;
    
    //render
    $service->render(__PATH__ . "/app/view/desktop/main.phtml");
});