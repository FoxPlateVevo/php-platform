<?php
require_once __PATH__ . "/app/service/Localization.php";
require_once __PATH__ . "/app/service/Product.php";
require_once __PATH__ . "/app/service/Project.php";
require_once __PATH__ . "/app/service/Store.php";
require_once __PATH__ . "/app/service/User.php";

/*
 * Set service and resource data
 */

$localization                               = new Service_Localization();
$headquartersResource                       = $localization->headquarters;

$product                                    = new Service_Product();
$groupsResource                             = $product->groups;
$productsResource                           = $product->products;
$storeItemsResource                         = $product->storeItems;

$project                                    = new Service_Project();
$projectsResource                           = $project->projects;
$proposalsAcademicCalendarResource          = $project->proposalsAcademicCalendar;

$store                                      = new Service_Store();
$discountsResource                          = $store->discounts;
$enrollmentsResource                        = $store->enrollments;
$interestsResource                          = $store->interests;

$user                                       = new Service_User();
$usersResource                              = $user->users;

/*
 * Store Items
 */
$this->respond("GET", "/[i:storeId]/register", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $projectsResource) {
    $storeId = $request->param("storeId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    $product = $productsResource->get($storeItem->getProductId());
    $project = array_pop($projectsResource->listProjects([
        "storeId" => $storeItem->getStoreId()
    ]));
    
    //header params
    $service->pageTitle = "Registrar Producto";
    
    //content params
    $service->storeItem = $storeItem;
    $service->product = $product;
    $service->project = $project;
    
    //render
    $service->render(__PATH__ . "/app/view/store/register.phtml");
});

$this->respond("POST", "/[i:storeId]/register", function ($request, $response, $service) 
use ($storeItemsResource) {
    $storeId = $request->param("storeId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    
    $storeItem->setStatus(Store::STATUS_STRUCTURED);
    
    $storeItemsResource->update($storeItem);
    
    $response->redirect("/product")->send();
});

$this->respond("GET", "/[i:storeId]", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $projectsResource, $discountsResource, $interestsResource) {
    $storeId = $request->param("storeId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    $product = $productsResource->get($storeItem->getProductId());
    $project = array_pop($projectsResource->listProjects([
        "storeId" => $storeItem->getStoreId()
    ]));
    
    $discounts = $discountsResource->listDiscounts([
        "storeId" => $storeItem->getStoreId()
    ]);
    
    $interests = $interestsResource->listInterests([
        "storeId" => $storeItem->getStoreId()
    ]);
    
    //header params
    $service->pageTitle = "Registrar Producto";
    
    //content params
    $service->storeItem = $storeItem;
    $service->product = $product;
    $service->project = $project;
    $service->discounts = $discounts;
    $service->interests = $interests;
    
    //render
    $service->render(__PATH__ . "/app/view/store/edit.phtml");
});

$this->respond("POST", "/[i:storeId]", function ($request, $response, $service) use ($storeItemsResource) {
    $storeId  = $request->param("storeId");
    
    $typeCoin       = $request->param("type-coin");
    $minimunPrice   = $request->param("minimun-price");
    $price          = $request->param("price");
    $stock          = $request->param("stock");
    $description    = $request->param("description");
    $multimediaFile = $request->param("multimedia-file");
    
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store();
    
    $storeItem->setTypeCoin($typeCoin);
    $storeItem->setMinimunPrice($minimunPrice);
    $storeItem->setPrice($price);
    $storeItem->setStock($stock);
    $storeItem->setSaleInformation($description);
    $storeItem->setSaleTypePresentation(Store::SALE_TYPE_PRESENTATION_MULTIMEDIA_FILE);
    $storeItem->setSaleContentPresentation($multimediaFile);
    
    $storeItemsResource->update($storeItem);
    
    $response->redirect("/store/{$storeId}");
});

$this->respond("GET", "/[i:storeId]/create-discount", function ($request, $response, $service) use ($storeItemsResource) {
    //header params
    $service->pageTitle = "Crear Descuento";
    
    //render
    $service->render(__PATH__ . "/app/view/store/create-discount.phtml");
});

$this->respond("POST", "/[i:storeId]/create-discount", function ($request, $response, $service) use ($storeItemsResource, $discountsResource) {
    $storeId  = $request->param("storeId");
    
    $type           = $request->param("type-discount");
    $value          = $request->param("value");
    $description    = $request->param("description");
    
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store();
    
    $discountsResource->insert(new Discount([
        "type"          => $type,
        "value"         => $value,
        "description"   => $description,
        "storeId"       => $storeItem->getStoreId()
    ]));
    
    $response->redirect("/store/{$storeId}");
});

$this->respond("GET", "/[i:storeId]/create-interest", function ($request, $response, $service) use ($storeItemsResource) {
    //header params
    $service->pageTitle = "Crear Interés";
    
    //render
    $service->render(__PATH__ . "/app/view/store/create-interest.phtml");
});

$this->respond("POST", "/[i:storeId]/create-interest", function ($request, $response, $service) use ($storeItemsResource, $interestsResource) {
    $storeId  = $request->param("storeId");
    
    $type           = $request->param("type-interest");
    $value          = $request->param("value");
    $description    = $request->param("description");
    
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store();
    
    $interestsResource->insert(new Interest([
        "type"          => $type,
        "value"         => $value,
        "description"   => $description,
        "storeId"       => $storeItem->getStoreId()
    ]));
    
    $response->redirect("/store/{$storeId}");
});

$this->respond("POST", "/[i:storeId]/delete-discounts", function ($request, $response, $service) use ($discountsResource) {
    $discountIds    = $request->param("discountIds");
    
    $service->validate($discountIds, "Parámetro discountIds no establecido")->notEmpty();
    
    foreach ($discountIds as $discountId){
        $discountsResource->delete($discountId);
    }
    
    $response->json([
        "success" => true
    ]);
});

$this->respond("POST", "/[i:storeId]/delete-interests", function ($request, $response, $service) use ($interestsResource) {
    $interestIds    = $request->param("interestIds");
    
    $service->validate($interestIds, "Parámetro interestIds no establecido")->notEmpty();
    
    foreach ($interestIds as $interestId){
        $interestsResource->delete($interestId);
    }
    
    $response->json([
        "success" => true
    ]);
});

$this->respond("GET", "/[i:storeId]/enrollment/[i:groupId]?", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $projectsResource, $groupsResource, $enrollmentsResource, $usersResource) {
    $storeId = $request->param("storeId");
    $groupId = $request->param("groupId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    $product = $productsResource->get($storeItem->getProductId());
    $project = array_pop($projectsResource->listProjects([
        "storeId" => $storeItem->getStoreId()
    ]));
    
    $groups = $groupsResource->listGroups([
        "storeId" => $storeItem->getStoreId()
    ]);
    
    if(!$groupId){
        /*
         * Get default Group to redirect
         */
        $defaultGroup = array_pop(array_filter($groups, function(Group $group){
            return $group->getTypeEntry() === Group::TYPE_ENTRY_BY_DEFULT;
        }));
        
        $response
        ->redirect("/store/{$storeItem->getStoreId()}/enrollment/{$defaultGroup->getGroupId()}")
        ->send();
    }
    
    $groupSelected = array_pop(array_filter($groups, function(Group $group) use ($groupId){
        return $group->getGroupId() === $groupId;
    }));
//    $groupSelected = new Group;
    
    $enrollments = $enrollmentsResource->listEnrollments([
        "groupId" => $groupSelected->getGroupId()
    ]);
    
    $studentUserIds = array_map(function(Enrollment $enrollment){
        return $enrollment->getStudentUserId();
    }, $enrollments);
    
    $students = $usersResource->listUsers([
        "userId" => $studentUserIds
    ]);
    
    //header params
    $service->pageTitle = "Registrar Producto";
    
    //content params
    $service->storeItem     = $storeItem;
    $service->product       = $product;
    $service->project       = $project;
    $service->groups        = $groups;
    $service->groupSelected = $groupSelected;
    $service->enrollments   = $enrollments;
    $service->students      = $students;
    
    //render
    $service->render(__PATH__ . "/app/view/store/edit.enrollment.phtml");
});

$this->respond("GET", "/[i:storeId]/enrollment/[i:groupId]/create-enrollment", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $groupsResource, $enrollmentsResource) {
    $storeId = $request->param("storeId");
    $groupId = $request->param("groupId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    $product = $productsResource->get($storeItem->getProductId());
    $group = $groupsResource->get($groupId);
    
    $enrollments = $enrollmentsResource->listEnrollments([
        "groupId" => $group->getGroupId()
    ]);
    
    $currentStudentUserIds = array_map(function(Enrollment $enrollment){
        return $enrollment->getStudentUserId();
    }, $enrollments);
    
    
    //header params
    $service->pageTitle = "Crear Matrícula";
    
    //content params
    $service->storeItem = $storeItem;
    $service->product   = $product;
    $service->group     = $group;
    $service->currentStudentUserIds     = $currentStudentUserIds;
    
    //render
    $service->render(__PATH__ . "/app/view/store/edit.enrollment.create.phtml");
});

$this->respond("POST", "/[i:storeId]/enrollment/[i:groupId]/create-enrollment", function ($request, $response, $service) 
use ($storeItemsResource, $discountsResource, $enrollmentsResource) {
    $storeId  = $request->param("storeId");
    $groupId  = $request->param("groupId");
    
    $saleId         = $request->param("sale-id");
    $studentUserId  = $request->param("student-user-id");
    $observation    = $request->param("observation");
    
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store();
    
    $user = get_user_session();
    
    $enrollmentsResource->insert(new Enrollment([
        "saleId"        => $saleId ? $saleId : null,
        "studentUserId" => $studentUserId,
        "groupId"       => $groupId,
        "registryDate"  => get_datetime(),
        "observation"   => $observation,
        "status"        => Enrollment::STATUS_ENABLED,
        "responsibleUserId" => $user->getUserId(),
    ]));
    
    $response->redirect("/store/{$storeItem->getStoreId()}/enrollment/{$groupId}");
});

$this->respond("POST", "/[i:storeId]/enrollment/[i:groupId]/action/[enable|disable|delete:action]", function ($request, $response, $service) 
use ($storeItemsResource, $discountsResource, $enrollmentsResource) {
    $storeId  = $request->param("storeId");
    $groupId  = $request->param("groupId");
    $action  = $request->param("action");
    
    $enrollmentIds  = $request->param("enrollmentIds");
    
    /*
     * Initial validators
     */
    $service->validate($enrollmentIds, "Parámetro enrollmentIds no establecido")->notEmpty();
    
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store();
    
    if($action === "delete"){
        foreach ($enrollmentIds as $enrollmentId){
            $enrollmentsResource->delete($enrollmentId);
        }
    }else{
        $statusToUpdate = $action === "enable" ? Enrollment::STATUS_ENABLED : Enrollment::STATUS_DISABLED;
        
        foreach ($enrollmentIds as $enrollmentId){
            $enrollment = $enrollmentsResource->get($enrollmentId);
//            $enrollment = new Enrollment();
            
            $enrollment->setStatus($statusToUpdate);
            
            $enrollmentsResource->update($enrollment);
        }
    }
    
    $response->json([
        "success" => true
    ]);
});

/*
 * Evaluations
 */
$this->respond("GET", "/[i:storeId]/evaluation", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $projectsResource, $discountsResource, $interestsResource) {
    $storeId = $request->param("storeId");
    
    /*
     * Get store item
     */
    $storeItem = $storeItemsResource->get($storeId);
//    $storeItem = new Store;
    $product = $productsResource->get($storeItem->getProductId());
    $project = array_pop($projectsResource->listProjects([
        "storeId" => $storeItem->getStoreId()
    ]));
    
    $discounts = $discountsResource->listDiscounts([
        "storeId" => $storeItem->getStoreId()
    ]);
    
    //header params
    $service->pageTitle = "Registrar Producto";
    
    //content params
    $service->storeItem = $storeItem;
    $service->product = $product;
    $service->project = $project;
    $service->discounts = $discounts;
    
    //render
    $service->render(__PATH__ . "/app/view/store/evaluation.phtml");
});

$this->respond("GET", "/[i:storeId]/evaluation/concept/create", function ($request, $response, $service) 
use ($storeItemsResource, $productsResource, $projectsResource, $discountsResource, $interestsResource) {
    //header params
    $service->pageTitle = "Registrar Concepto de Evaluación";
    
    //content params
//    $service->discounts = $discounts;
    
    //render
    $service->render(__PATH__ . "/app/view/store/concept.create.phtml");
});

$this->respond("POST", "/[i:storeId]/evaluation/concept/create", function ($request, $response, $service) 
use ($dfdfdff) {
    $storeId = $request->param("storeId");
    
    
});