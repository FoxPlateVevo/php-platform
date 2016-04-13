<?php
require_once __PATH__ . "/app/service/Administration.php";
require_once __PATH__ . "/app/service/Course.php";
require_once __PATH__ . "/app/service/Localization.php";
require_once __PATH__ . "/app/service/Product.php";
require_once __PATH__ . "/app/service/Project.php";
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

$localization                               = new Service_Localization();
$headquartersResource                       = $localization->headquarters;

$product                                    = new Service_Product();
$productsResource                           = $product->products;
$storeItemsResource                         = $product->storeItems;
$groupsResource                             = $product->groups;
$educativeResourcesResource                 = $product->educativeResources;
$academicStaffManagementMembersResource     = $product->academicStaffManagementMembers;

$project                                    = new Service_Project();
$projectsResource                           = $project->projects;
$proposalsAcademicCalendarResource          = $project->proposalsAcademicCalendar;
$proposalAcademicCalendarRangesResource     = $project->proposalAcademicCalendarRanges;

$user                                       = new Service_User();
$usersResource                              = $user->users;
$userProfilesResource                       = $user->userProfiles;

/*
 * Projects
 */
$this->respond("GET", "/?", function ($request, $response, $service) use ($projectsResource) {
    /*
     * List all projects 
     */
    $projects = $projectsResource->listProjects();
    
    //header params
    $service->pageTitle = "Mis Proyectos";
    
    //content params
    $service->projects = $projects;
    
    //render
    $service->render(__PATH__ . "/app/view/project/project.list.phtml");
});

$this->respond("GET", "/create", function ($request, $response, $service) use ($projectsResource) {
    $insertedProjectId = $projectsResource->insert(new Project([
        "registryDate"      => get_datetime(),
        "status"            => Project::STATUS_ACTIVATED,
        "ACproposalId"      => null,
        "storeId"           => null
    ]));
    
    $response->redirect("/project/{$insertedProjectId}/academic-calendar/create");
});

/*
 * Proposals Academic Calendar
 */

$this->respond("GET", "/[i:id]/academic-calendar/create", function ($request, $response, $service) use ($projectsResource) {
    $projectId  = $request->param("id");
    
    $project = $projectsResource->get($projectId);
    
    !$project && $response->redirect("/error")->send();
    
    //header params
    $service->pageTitle = "Crear Propuesta de Calendario Académico";
    
    //content params
    $service->projectId = $projectId;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-academic-calendar.create.phtml");
});

$this->respond("POST", "/[i:id]/academic-calendar/create", 
function ($request, $response, $service) use ($projectsResource, $proposalsAcademicCalendarResource) {
    $projectId  = $request->param("id");
    $ac_description = $request->param("ac-description");
        
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    !$project && $response->redirect("/error")->send();
    
    $user = get_user_session();
//    $user = new User();
    
    $insertedProposalAcademicCalendarId = $proposalsAcademicCalendarResource->insert(new ProposalAcademicCalendar([
        "description"                   => $ac_description,
        "registryDate"                  => get_datetime(),
        "status"                        => ProposalAcademicCalendar::STATUS_PENDING,
        "responsibleUserId"             => $user->getUserId()
    ]));
    
    $project->setACproposalId($insertedProposalAcademicCalendarId);
    
    $success = $projectsResource->update($project);
    
    $response->redirect("/project/{$project->getProjectId()}/academic-calendar/{$insertedProposalAcademicCalendarId}");
});

$this->respond("GET", "/[i:id]/academic-calendar/[i:proposalAcademicCalendarId]", 
function ($request, $response, $service) 
use ($projectsResource, $proposalsAcademicCalendarResource, 
$proposalAcademicCalendarRangesResource, $usersResource) {
    $projectId  = $request->param("id");
    $proposalAcademicCalendarId  = $request->param("proposalAcademicCalendarId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    (!$project || $project->getACproposalId() !== $proposalAcademicCalendarId) && $response->redirect("/error")->send();
    
    /*
     * Get proposalAcademicCalendar
     */
    $proposalAcademicCalendar       = $proposalsAcademicCalendarResource->get($proposalAcademicCalendarId);
    $responsibleUser                = $usersResource->get($proposalAcademicCalendar->getResponsibleUserId());
    $proposalAcademicCalendarRanges = $proposalAcademicCalendarRangesResource->listProposalAcademicCalendarRanges([
        "proposalAcademicCalendarId" => $proposalAcademicCalendarId
    ]);
    
    $events = array_map(function(ProposalAcademicCalendarRange $proposalAcademicCalendarRange){
        return $proposalAcademicCalendarRange->toJsonCalendarEvent();
    }, $proposalAcademicCalendarRanges);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Calendario Académico";
    
    //content params
    $service->project                           = $project;
    $service->proposalAcademicCalendar          = $proposalAcademicCalendar;
    $service->responsibleUser                   = $responsibleUser;
    $service->proposalAcademicCalendarRanges    = $proposalAcademicCalendarRanges;
    $service->events                            = array_values($events);
    
    $user = get_user_session();
    
    //render
    if($user->activeAdministrationUserProfile === UserProfile::TYPE_ACADEMIC_COORDINATION_BOSS){
        $service->render(__PATH__ . "/app/view/project/proposal-academic-calendar.edit.acb.phtml");
    }else{
        $service->render(__PATH__ . "/app/view/project/proposal-academic-calendar.edit.phtml");
    }
});

$this->respond("POST", "/[i:id]/academic-calendar/[i:proposalAcademicCalendarId]", 
function ($request, $response, $service) use ($projectsResource, $proposalsAcademicCalendarResource) {
    $projectId                      = $request->param("id");
    $proposalAcademicCalendarId     = $request->param("proposalAcademicCalendarId");
    $ac_description                 = $request->param("ac-description");
    
    $project = $projectsResource->get($projectId);
    
    (!$project || $project->getACproposalId() !== $proposalAcademicCalendarId) && $response->redirect("/error")->send();
    
    $proposalAcademicCalendar = $proposalsAcademicCalendarResource->get($proposalAcademicCalendarId);
//    $proposalAcademicCalendar = new ProposalAcademicCalendar();
    
    $proposalAcademicCalendar->setDescription($ac_description);
    
    $success = $proposalsAcademicCalendarResource->update($proposalAcademicCalendar);
    
    $response->json($success);
});

$this->respond("POST", "/[i:id]/academic-calendar/[i:proposalAcademicCalendarId]/update-status", 
function ($request, $response, $service) use ($projectsResource, $proposalsAcademicCalendarResource) {
    $projectId                      = $request->param("id");
    $proposalAcademicCalendarId     = $request->param("proposalAcademicCalendarId");
    $status                         = $request->param("ac-status");
    
    $proposalAcademicCalendar = $proposalsAcademicCalendarResource->get($proposalAcademicCalendarId);
//    $proposalAcademicCalendar = new ProposalAcademicCalendar();
    
    $proposalAcademicCalendar->setStatus($status);
    
    $success = $proposalsAcademicCalendarResource->update($proposalAcademicCalendar);
    
    $response->redirect("/project/{$projectId}/academic-calendar/{$proposalAcademicCalendarId}");
});

/*
 * Proposals Academic Calendar Range
 */

$this->respond("POST", "/[i:id]/academic-calendar/[i:proposalAcademicCalendarId]/academic-calendar-range/create", 
function ($request, $response, $service) use ($projectsResource, $proposalAcademicCalendarRangesResource) {
    $projectId                      = $request->param("id");
    $proposalAcademicCalendarId     = $request->param("proposalAcademicCalendarId");
    
    $project = $projectsResource->get($projectId);
    
    (!$project || $project->getACproposalId() !== $proposalAcademicCalendarId) && $response->redirect("/error")->send();
    
    $type           = $request->param("range-type");
    $startDate      = $request->param("range-start-date");
    $finalDate      = $request->param("range-final-date");
    $description    = $request->param("range-description");
    $segmentOption  = $request->param("range-segment-option");
    $segmentQuantity = $request->param("range-segment-quantity");
    
    /*
     * Initial Validators
     */
    $service->validate($startDate && $finalDate, "La fecha de inicio y fecha final son necesarios")->isTrue();
    $service->validate($description, "Es necesario una descripción")->notEmpty();
    
    $startDateString = get_date_from_format($startDate);
    $finalDateString = get_date_from_format($finalDate);
    
    $service->validate($startDateString && $finalDateString, "La fecha de inicio y fecha final no tienen un formato correcto")->isTrue();
    
    $startDateTime  = date_create($startDateString);
    $finalDateTime  = date_create($finalDateString);
    
    $service->validate($finalDateTime >= $startDateTime, "La fecha de inicio tiene que ser menor o igual a la fecha final")->isTrue();

    /*
     * Declare segment quantity values 
     */
    $segments = [
        "monday"            => [1],
        "tuesday"           => [2],
        "wednesday"         => [3],
        "thursday"          => [4],
        "friday"            => [5],
        "saturday"          => [6],
        "sunday"            => [7],
        "tuesday-thursday"  => [2,4],
        "monday-wednesday-friday"   => [1, 3, 5],
        "saturday-sunday"           => true,
        "monday-to-friday"          => true
    ];

    $ranges = [];

    if(filter_var($segmentOption, FILTER_VALIDATE_BOOLEAN) && array_key_exists($segmentQuantity, $segments)){
        $interval       = new DateInterval('P1D');
        $period         = new DatePeriod($startDateTime, $interval, $finalDateTime->modify('+1 day'));

        $segmentValue   = $segments[$segmentQuantity];

        if(is_array($segmentValue)){
            foreach ($period as $periodDateTime) {
                $numDay = (int) $periodDateTime->format('N');

                if(in_array($numDay, $segmentValue)){
                    $ranges []= (object) [
                        "start" => $periodDateTime,
                        "final" => $periodDateTime
                    ];
                }
            }
        }else if(is_bool($segmentValue)){
            foreach ($period as $periodDateTime) {
                $numDay = (int) $periodDateTime->format('N');

                if($segmentValue === "monday-to-friday" && $numDay === 1){ //Monday
                    $ranges []= (object) [
                        "start" => $periodDateTime,
                        "final" => $periodDateTime->modify('+4 day')
                    ];
                }else if($segmentValue === "saturday-sunday" && $numDay === 6){ //Saturday
                    $ranges []= (object) [
                        "start" => $periodDateTime,
                        "final" => $periodDateTime->modify('+1 day')
                    ];
                }
            }
        }
    }else{
        $ranges []= (object) [
            "start" => $startDateTime,
            "final" => $finalDateTime
        ];
    }

    $ProposalAcademicCalendarRangeIds = [];

    foreach ($ranges as $range){
        $insertedProposalAcademicCalendarRangeId = $proposalAcademicCalendarRangesResource->insert(new ProposalAcademicCalendarRange([
            "type"                          => $type,
            "description"                   => $description,
            "startDate"                     => $range->start->format("Y-m-d"),
            "finalDate"                     => $range->final->format("Y-m-d"),
            "proposalAcademicCalendarId"    => $proposalAcademicCalendarId
        ]));

        $ProposalAcademicCalendarRangeIds []= $insertedProposalAcademicCalendarRangeId;
    }
    
    $proposalAcademicCalendarRanges = $proposalAcademicCalendarRangesResource->listProposalAcademicCalendarRanges([
        "proposalAcademicCalendarRangeId" => $ProposalAcademicCalendarRangeIds
    ]);
    
    $events = array_map(function(ProposalAcademicCalendarRange $proposalAcademicCalendarRange){
        return $proposalAcademicCalendarRange->toJsonCalendarEvent();
    }, $proposalAcademicCalendarRanges);
    
    $response->json([
        "events" => $events
    ]);
});

/*
 * Proposals Product Structure
 */

$this->respond("GET", "/[i:id]/product-structure/create", 
function ($request, $response, $service) use ($projectsResource, $headquartersResource, $categoriesResource) {
    $projectId  = $request->param("id");
    
    $project = $projectsResource->get($projectId);
    
    !$project && $response->redirect("/error")->send();
    
    //header params
    $service->pageTitle = "Crear Propuesta de Estructura de Producto";
    
    //content params
    $service->project       = $project;
    $service->headquarters  = $headquartersResource->listHeadquarters();
    $service->categories    = $categoriesResource->listCategories();
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.create.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/create", 
function ($request, $response, $service) use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource) {
    $projectId              = $request->param("id");
    $name                   = $request->param("product-name");
    $startDate              = $request->param("product-start-date");
    $finalDate              = $request->param("product-final-date");
    $type                   = $request->param("product-type");
    $headquarterId          = $request->param("product-headquarter-id");
    $programType            = $request->param("product-program-type");
    $categoryId             = $request->param("product-category-id");
    $information            = $request->param("product-information");
    $objective              = $request->param("product-objective");
    $publicObjective        = $request->param("product-public-objective");
    $courseProfessorProfileInformation  = $request->param("product-course-professor-profile-information");
    
    $project = $projectsResource->get($projectId);
//    __class(new Group);
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId(), "Ha ocurrido un error")->isInt();
    $service->validate($name, "Es necesario un nombre")->notEmpty();
    $service->validate($startDate && $finalDate, "La fecha de inicio y fecha final son necesarios")->isTrue();
    
    $startDateString = get_date_from_format($startDate);
    $finalDateString = get_date_from_format($finalDate);
    
    $service->validate($startDateString && $finalDateString, "La fecha de inicio y fecha final no tienen un formato correcto")->isTrue();
    
    $user = get_user_session();
//    $user = new User();
    
    $productToInsert = new Product([
        "name"                          => $name,
        "type"                          => $type,
        "categoryId"                    => $categoryId,
        "information"                   => $information,
        "objectiveInformation"          => $objective,
        "objectivePublicInformation"    => $publicObjective,
        "registryDate"                  => get_datetime(),
        "responsibleUserId"             => $user->getUserId()
    ]);
    
    if($type === Product::TYPE_PRODUCT_PROGRAM){
        $insertedProductId = $productsResource->insert($productToInsert, 
        new Program([
            "type" => $programType
        ]));
    }else if($type === Product::TYPE_PRODUCT_COURSE){
        $insertedProductId = $productsResource->insert($productToInsert,
        new Course([
            "professorProfileInformation" => $courseProfessorProfileInformation
        ]));
    }
    
    $URIredirect = false;
    
    if($insertedProductId){
        $insertedStoreItemId = $storeItemsResource->insert(new Store([
            "typeEntry"                 => Store::TYPE_ENTRY_BY_DEFULT,
            "registryDate"              => get_datetime(),
            "status"                    => Store::STATUS_STRUCTURING,
            "productId"                 => $insertedProductId,
            "typeCoin"                  => null,
            "price"                     => null,
            "minimunPrice"              => null,
            "stock"                     => null,
            "saleInformation"           => null,
            "saleTypePresentation"      => null,
            "saleContentPresentation"   => null,
            "isConfiguredGroup"         => false
        ]));
        
        if($insertedStoreItemId){
            $insertedGroupId = $groupsResource->insert(new Group([
                "description"   => "Grupo insertado por defecto",
                "headquarterId" => $headquarterId,
                "starDate"      => $startDateString,
                "finalDate"     => $finalDateString,
                "typeEntry"     => Group::TYPE_ENTRY_BY_DEFULT,
                "storeId"       => $insertedStoreItemId
            ]));

            if($insertedGroupId){
                $project->setStoreId($insertedStoreItemId);
                
                $projectsResource->update($project);
                
                $URIredirect = "/project/{$project->getProjectId()}/product-structure/{$insertedStoreItemId}";
            }else{
                $storeItemsResource->delete($insertedStoreItemId);
                $productsResource->delete($insertedProductId);
            }
        }else{
            $productsResource->delete($insertedProductId);
        }
    }
    
    $response->json([
        "URIredirect" => $URIredirect
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $headquartersResource, $categoriesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get group
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $group      = array_shift($groupsResource->listGroups([
        "typeEntry" => Group::TYPE_ENTRY_BY_DEFULT,
        "storeId"   => $store->getStoreId()
    ]));
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto";
    
    //content params
    $service->project       = $project;
    $service->store         = $store;
    $service->group         = $group;
    $service->product       = $product;
    $service->headquarters  = $headquartersResource->listHeadquarters();
    $service->categories    = $categoriesResource->listCategories();
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $name                   = $request->param("product-name");
    $startDate              = $request->param("product-start-date");
    $finalDate              = $request->param("product-final-date");
    $headquarterId          = $request->param("product-headquarter-id");
    $programType            = $request->param("product-program-type");
    $categoryId             = $request->param("product-category-id");
    $information            = $request->param("product-information");
    $objective              = $request->param("product-objective");
    $publicObjective        = $request->param("product-public-objective");
    $courseProfessorProfileInformation  = $request->param("product-course-professor-profile-information");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    $startDateString = get_date_from_format($startDate);
    $finalDateString = get_date_from_format($finalDate);
    
    $service->validate($startDateString && $finalDateString, "La fecha de inicio y fecha final no tienen un formato correcto")->isTrue();
    
    /*
     * Get store
     * Get group
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $group      = array_shift($groupsResource->listGroups([
        "typeEntry" => Group::TYPE_ENTRY_BY_DEFULT,
        "storeId"   => $store->getStoreId()
    ]));
    $product    = $productsResource->get($store->getProductId());
    $finalProduct = $product->finalProduct;
    
//    $store = new Store();
//    $group = new Group();
//    $product = new Product;
    
    $product->setName($name);
    $product->setCategoryId($categoryId);
    $product->setInformation($information);
    $product->setObjectiveInformation($objective);
    $product->setObjectivePublicInformation($publicObjective);
    
    if($finalProduct instanceof Program){
        $finalProduct->setType($programType);
    }else if($finalProduct instanceof Course){
        $finalProduct->setProfessorProfileInformation($courseProfessorProfileInformation);
    }
    
    $group->setStarDate($startDateString);
    $group->setFinalDate($finalDateString);
    $group->setHeadquarterId($headquarterId);
    
    $storeUpdatedSuccess = $storeItemsResource->update($store);
    $groupUpdatedSuccess = $groupsResource->update($group);
    $productUpdatedSuccess = $productsResource->update($product, $finalProduct);
    
    $response->json($storeUpdatedSuccess && $groupUpdatedSuccess && $productUpdatedSuccess);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/presentation", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Presentación";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.presentation.phtml");
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/presentation/multimedia-file", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Presentación";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.presentation.multimedia-file.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/presentation/multimedia-file", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $multimediaFile = $request->param("multimedia-file");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($multimediaFile, "Es necesario subir un archivo")->notEmpty();
    
    /*
     * Update product
     */
    
    $store = $storeItemsResource->get($storeId);
//    $store = new Store();
    
    $product = $productsResource->get($store->getProductId());
//    $product = new Product;
    $product->setTypePresentation(Product::TYPE_PRESENTATION_MULTIMEDIA_FILE);
    $product->setContentPresentation($multimediaFile);
    
    $updateProductSuccess = $productsResource->update($product);
    
    $response->json([
        "success"       => $updateProductSuccess,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/presentation"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/presentation/embedded-resource", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Presentación";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.presentation.embedded-resource.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/presentation/embedded-resource", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $embeddedContent = $request->param("embedded-content");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($embeddedContent, "Es necesario insertar un contenido para el recurso embebido")->notEmpty();
    
    $url = get_url_from_string($embeddedContent);
    
    $service->validate((bool) $url, "Contenido no permitido")->isTrue();
    
    /*
     * Update product
     */
    
    $store = $storeItemsResource->get($storeId);
//    $store = new Store();
    
    $product = $productsResource->get($store->getProductId());
//    $product = new Product;
    $product->setTypePresentation(Product::TYPE_PRESENTATION_EMBEDDED_RESOURCE);
    $product->setContentPresentation($embeddedContent);
    
    $updateProductSuccess = $productsResource->update($product);
    
    $response->json([
        "success"       => $updateProductSuccess,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/presentation"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $unitsResource, $sessionsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $finalProduct = $product->finalProduct;
    $units      = $unitsResource->listUnits([
        "courseId" => $finalProduct->getCourseId()
    ]);
    
    $sessionGroups = array_map(function(Unity $unit) use ($sessionsResource){
        return $sessionsResource->listSessions([
            "unityId" => $unit->getUnityId()
        ]);
    }, $units);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Syllabus";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->units         = $units;
    $service->sessionGroups = $sessionGroups;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.phtml");
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus/create-unity", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $professorDetailsResource, $usersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Crear Unidad";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.create-unity.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/create-unity", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $unitsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $name                   = $request->param("name");
    $description            = $request->param("description");
    $availabilityStartDate  = $request->param("availability-start-date");
    $availabilityFinalDate  = $request->param("availability-final-date");
    $position               = $request->param("position");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($name, "Es necesario un nombre para registrar la unidad")->notEmpty();
    $service->validate($position, "Se debe establecer una posición para la unidad a crear")->isInt();
    
    /*
     * Get store
     * Get product
     */
    $store = $storeItemsResource->get($storeId);
    $product = $productsResource->get($store->getProductId());
    $finalProduct = $product->finalProduct;
    $user = get_user_session();
//    $finalProduct = new Course();
//    $user = new User;
    
    /*
     * Insert new unity
     */
    $unitId = $unitsResource->insert(new Unity([
        "name"                  => $name,
        "description"           => $description,
        "availabilityStartDate" => get_date_from_format($availabilityStartDate),
        "availabilityFinalDate" => get_date_from_format($availabilityFinalDate),
        "position"              => $position,
        "registryDate"          => get_datetime(),
        "courseId"              => $finalProduct->getCourseId(),
        "responsibleUserId"     => $user->getUserId()
    ]));
    
    $response->json([
        "success"       => (bool) $unitId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/syllabus"
    ]);
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/session/action/[delete:action]", 
function ($request, $response, $service) 
use ($sessionsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $action                 = $request->param("action");
    $selectedSessionIds        = $request->param("selectedSessionIds");
    
    /*
     * Initial Validators
     */
    $service->validate($selectedSessionIds, "Ha ocurrido un error")->notEmpty();
    
    /*
     * Delete sessions
     */
    foreach($selectedSessionIds as $sessionId){
        $sessionsResource->delete($sessionId);
    }
    
    $response->json([
        "success" => true
    ]);
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/action/[delete:action]", 
function ($request, $response, $service) 
use ($unitsResource, $sessionsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $action                 = $request->param("action");
    $selectedUnitIds        = $request->param("selectedUnitIds");
    
    /*
     * Initial Validators
     */
    $service->validate($selectedUnitIds, "Ha ocurrido un error")->notEmpty();
    
    /*
     * Get sessions
     * Delete sessions
     * Delete units
     */
    $sessions = $sessionsResource->listSessions([
        "unityId" => $selectedUnitIds
    ]);
    
    foreach($sessions as $session){
//        $session = new Session;
        
        $sessionsResource->delete($session->getSessionId());
    }
    
    foreach($selectedUnitIds as $unitId){
        $unitsResource->delete($unitId);
    }
    
    $response->json([
        "success" => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/create-session", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $unitsResource, $groupsResource, $professorDetailsResource, $usersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId || !$unitId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course();
    
    $units = $unitsResource->listUnits([
        "courseId" => $finalProduct->getCourseId()
    ]);
    
    /*
     * Validate if unit is belongs to course
     */
    $service->validate(in_array($unitId, array_keys($units)), "Ha ocurrido un error")->isTrue();
    
    $groups     = $groupsResource->listGroups([
        "storeId" => $storeId
    ]);
    
    /*
     * Get default Group to list professors
     */
    $defaultGroup = array_pop(array_filter($groups, function(Group $group){
        return $group->getTypeEntry() === Group::TYPE_ENTRY_BY_DEFULT;
    }));
    
//    $defaultGroup = new Group();
    
    $professorDetails = $professorDetailsResource->listProfessorDetails([
        "groupId" => $defaultGroup->getGroupId()
    ]);
    
    $professorUserIds = array_map(function(ProfessorDetail $professorDetail){
        return $professorDetail->getProfessorUserId();
    }, $professorDetails);
    
    $professors = $usersResource->listUsers([
        "userId" => $professorUserIds
    ]);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Crear Sesión";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->professors    = $professors;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.unity.create-session.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/create-session", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $sessionsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    
    $name                       = $request->param("name");
    $professorExhibitorUserId   = $request->param("professor-exhibitor-user-id");
    $availabilityStartDate      = $request->param("availability-start-date");
    $availabilityFinalDate      = $request->param("availability-final-date");
    $position                   = $request->param("position");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($name, "Es necesario un nombre para registrar la sesión")->notEmpty();
    $service->validate($professorExhibitorUserId, "Se debe establecer un profesor para la sesión")->isInt();
    $service->validate($position, "Se debe establecer una posición para la unidad a crear")->isInt();
    
    /*
     * Get store
     * Get product
     */
    $user   = get_user_session();
//    $finalProduct = new Course();
//    $user = new User;
    
    /*
     * Insert new unity
     */
    $sessionId = $sessionsResource->insert(new Session([
        "name"                      => $name,
        "availabilityStartDate"     => get_date_from_format($availabilityStartDate),
        "availabilityFinalDate"     => get_date_from_format($availabilityFinalDate),
        "position"                  => $position,
        "materialId"                => null,
        "registryDate"              => get_datetime(),
        "unityId"                   => $unitId,
        "responsibleUserId"         => $user->getUserId(),
        "professorExhibitorUserId"  => $professorExhibitorUserId,
    ]));
    
    $response->json([
        "success"       => (bool) $sessionId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/syllabus/unit/{$unitId}/session/{$sessionId}"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/session/[i:sessionId]", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, 
  $unitsResource, $groupsResource, $professorDetailsResource, 
  $usersResource, $sessionsResource, $materialsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    $sessionId              = $request->param("sessionId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId || !$unitId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course();
    
    $units = $unitsResource->listUnits([
        "courseId" => $finalProduct->getCourseId()
    ]);
    
    /*
     * Validate if unit is belongs to course
     */
    $service->validate(in_array($unitId, array_keys($units)), "Ha ocurrido un error")->isTrue();
    
    $groups     = $groupsResource->listGroups([
        "storeId" => $storeId
    ]);
    
    /*
     * Get default Group to list professors
     */
    $defaultGroup = array_pop(array_filter($groups, function(Group $group){
        return $group->getTypeEntry() === Group::TYPE_ENTRY_BY_DEFULT;
    }));
    
//    $defaultGroup = new Group();
    
    $professorDetails = $professorDetailsResource->listProfessorDetails([
        "groupId" => $defaultGroup->getGroupId()
    ]);
    
    $professorUserIds = array_map(function(ProfessorDetail $professorDetail){
        return $professorDetail->getProfessorUserId();
    }, $professorDetails);
    
    $professors = $usersResource->listUsers([
        "userId" => $professorUserIds
    ]);
    
    $session = $sessionsResource->get($sessionId);
//    $session = new Session;
    
    $material = ($session->getMaterialId()) ? $materialsResource->get($session->getMaterialId()) : null;
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Ver Sesión";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->professors    = $professors;
    $service->session       = $session;
    $service->material      = $material;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.unity.edit-session.phtml");
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/session/[i:sessionId]/create-multimedia-material", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $sessionsResource, $categoriesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    $sessionId              = $request->param("sessionId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId || !$unitId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $session    = $sessionsResource->get($sessionId);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Crear Material";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->session       = $session;
    $service->categories    = $categoriesResource->listCategories();
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.unity.session.create-multimedia-material.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/session/[i:sessionId]/create-multimedia-material", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $sessionsResource, $materialsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    $sessionId              = $request->param("sessionId");
    $name                   = $request->param("name");
    $categoryId             = $request->param("category-id");
    $multimediaFile         = $request->param("multimedia-file");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($name, "Es necesario un nombre para registrar el material")->notEmpty();
    $service->validate($categoryId, "Se debe establecer una categoria para el material")->isInt();
    $service->validate($multimediaFile, "Debes subir un archivo")->notEmpty();
    
    /*
     * Insert new material
     */
    $materialId = $materialsResource->insert(new Material([
        "name"          => $name,
        "type"          => Material::TYPE_MULTIMEDIA_FILE,
        "content"       => $multimediaFile,
        "categoryId"    => $categoryId,
        "registryDate"  => get_datetime()
    ]));
    
    $fileExtension = pathinfo($multimediaFile, PATHINFO_EXTENSION);
    
    rename(
        __PATH__ . "/public/resources/upload/materials/temp/{$sessionId}.{$fileExtension}", 
        __PATH__ . "/public/resources/upload/materials/{$materialId}.{$fileExtension}"
    );
        
    /*
     * Update session
     */
    $session = $sessionsResource->get($sessionId);
//    $session = new Session;
    
    $session->setMaterialId($materialId);
    
    $sessionsResource->update($session);
    
    $response->json([
        "success"       => (bool) $materialId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/syllabus/unit/{$unitId}/session/{$sessionId}"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/session/[i:sessionId]/create-embedded-material", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $sessionsResource, $categoriesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    $sessionId              = $request->param("sessionId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId || !$unitId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Crear Material";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->categories    = $categoriesResource->listCategories();
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.syllabus.unity.session.create-embedded-material.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/syllabus/unit/[i:unitId]/session/[i:sessionId]/create-embedded-material", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $sessionsResource, $materialsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $unitId                 = $request->param("unitId");
    $sessionId              = $request->param("sessionId");
    $name                   = $request->param("name");
    $categoryId             = $request->param("category-id");
    $embeddedContent        = $request->param("embedded-content");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($name, "Es necesario un nombre para registrar el material")->notEmpty();
    $service->validate($categoryId, "Se debe establecer una categoria para el material")->isInt();
    
    $url = get_url_from_string($embeddedContent);
    
    $service->validate((bool) $url, "Contenido no permitido")->isTrue();
    
    /*
     * Insert new material
     */
    $materialId = $materialsResource->insert(new Material([
        "name"          => $name,
        "type"          => Material::TYPE_EMBEDDED_RESOURCE,
        "content"       => $embeddedContent,
        "categoryId"    => $categoryId,
        "registryDate"  => get_datetime(),
    ]));
    
    /*
     * Update session
     */
    $session = $sessionsResource->get($sessionId);
//    $session = new Session;
    
    $session->setMaterialId($materialId);
    
    $sessionsResource->update($session);
    
    $response->json([
        "success"       => (bool) $materialId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/syllabus/unit/{$unitId}/session/{$sessionId}"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/educative-resource", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $educativeResourcesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course();
    
    $educativeResources = $educativeResourcesResource->listEducativeResources([
        "courseId" => $finalProduct->getCourseId()
    ]);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Recursos Educativos";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->educativeResources = $educativeResources;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.educative-resource.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/educative-resource/action", 
function ($request, $response, $service) 
use ($projectsResource, $educativeResourcesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $action                 = $request->param("action");
    $educativeResourceIds   = $request->param("educativeResourceIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($educativeResourceIds, "Parámetro educativeResourceIds no establecido")->notEmpty();
    
    /*
     * Get store
     * Get product
     */
    
    $educativeResources = $educativeResourcesResource->listEducativeResources([
        "educativeResourceId" => $educativeResourceIds
    ]);
    
    foreach ($educativeResources as $educativeResource){
//        $educativeResource = new EducativeResource();
        
        $educativeResourcesResource->delete($educativeResource->getEducativeResourceId());
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/educative-resource/create", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $headquartersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Recursos Educativos";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.educative-resource.create.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/educative-resource/create", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $educativeResourcesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $name           = $request->param("name");
    $description    = $request->param("description");
    $quantity       = $request->param("quantity");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($name, "Es necesario un nombre para registrar el recurso educativo")->notEmpty();
    $service->validate($description, "Es necesario una descripción para registrar el recurso educativo")->notEmpty();
    $service->validate($quantity, "La cantidad debe ser un valor entero")->isInt();
    
    /*
     * Insert new educative resource
     */
    $store = $storeItemsResource->get($storeId);
//    $store = new Store();
    
    $product = $productsResource->get($store->getProductId());
//    $product = new Product;
    
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course();
    
    $educativeResourceId = $educativeResourcesResource->insert(new EducativeResource([
        "name"          => $name,
        "description"   => $description,
        "quantity"      => $quantity,
        "courseId"      => $finalProduct->getCourseId(),
    ]));
    
    $response->json([
        "success"       => (bool) $educativeResourceId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/educative-resource"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/group", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $headquartersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $groups     = $groupsResource->listGroups([
        "storeId" => $storeId
    ]);
    
    $headquarterIds = array_map(function(Group $group){
        return $group->getHeadquarterId();
    }, $groups);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Grupos";
    
    //content params
    $service->project               = $project;
    $service->product               = $product;
    $service->groups                = $groups;
    $service->headquarters          = $headquartersResource->listHeadquarters([
        "headquarterId" => $headquarterIds
    ]);
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.group.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/group/action", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $headquartersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $action     = $request->param("action");
    $groupIds   = $request->param("groupIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($groupIds, "Parámetro groupIds no establecido")->notEmpty();
    
    /*
     * Get store
     * Get product
     */
    
    $groups = $groupsResource->listGroups([
        "groupId" => $groupIds
    ]);
    
    foreach ($groups as $group){
//        $group = new Group;
        
        if($action === "delete"){
            if($group->getTypeEntry() !== Group::TYPE_ENTRY_BY_DEFULT){
                $groupsResource->delete($group->getGroupId());
            }
        }else if($action === "enable" || $action === "disable"){
            
        }
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/group/create", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $headquartersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Grupos";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->headquarters       = $headquartersResource->listHeadquarters();
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.group.create.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/group/create", 
function ($request, $response, $service) 
use ($projectsResource, $groupsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $description            = $request->param("description");
    $headquarterId          = $request->param("headquarter-id");
    $startDate              = $request->param("start-date");
    $finalDate              = $request->param("final-date");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($description, "Es necesario una descripción para registrar el grupo")->notEmpty();
    
    $startDateString = get_date_from_format($startDate);
    $finalDateString = get_date_from_format($finalDate);

    $service->validate($startDateString && $finalDateString, "Es necesario establecer las dos fecha de forma correcta")->isTrue();

    $startDateTime  = date_create($startDateString);
    $finalDateTime  = date_create($finalDateString);

    $service->validate($finalDateTime > $startDateTime, "La fecha de inicio tiene que ser menor la fecha final")->isTrue();
    
    /*
     * Insert new group
     */
    
    $insertedGroupId = $groupsResource->insert(new Group([
        "description"       => $description,
        "headquarterId"     => $headquarterId,
        "starDate"          => $startDateString,
        "finalDate"         => $finalDateString,
        "typeEntry"         => Group::TYPE_ENTRY_BY_CREATION,
        "storeId"           => $storeId
    ]));
    
    $response->json([
        "success"       => (bool) $insertedGroupId,
        "URIredirect"   => "/project/{$project->getProjectId()}/product-structure/{$project->getStoreId()}/group"
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/professor-detail/[i:groupId]?", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $professorDetailsResource, $usersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $groupId                = $request->param("groupId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     * Get groups
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $groups     = $groupsResource->listGroups([
        "storeId" => $storeId
    ]);
    
    if(!$groupId){
        /*
         * Get default Group to redirect
         */
        $defaultGroup = array_pop(array_filter($groups, function(Group $group){
            return $group->getTypeEntry() === Group::TYPE_ENTRY_BY_DEFULT;
        }));
        
        $response
        ->redirect("/project/{$projectId}/product-structure/{$storeId}/professor-detail/{$defaultGroup->getGroupId()}")
        ->send();
    }
    
    $groupSelected = array_pop(array_filter($groups, function(Group $group) use ($groupId){
        return $group->getGroupId() === $groupId;
    }));
//    $groupSelected = new Group;
    
    $professorDetails   = $professorDetailsResource->listProfessorDetails([
        "groupId" => $groupSelected->getGroupId()
    ]);
    
    $professorUserIds   = array_map(function(ProfessorDetail $professorDetail){
        return $professorDetail->getProfessorUserId();
    }, $professorDetails);
    
    $professors         = $usersResource->listUsers([
        "userId" => $professorUserIds
    ]);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Plantel Docente";
    
    //content params
    $service->project           = $project;
    $service->product           = $product;
    $service->groups            = $groups;
    $service->groupSelected     = $groupSelected;
    $service->professorDetails  = $professorDetails;
    $service->professors        = $professors;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.professor-detail.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/professor-detail/[i:groupId]/action", 
function ($request, $response, $service) 
use ($projectsResource, $professorDetailsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $groupId                = $request->param("groupId");
    
    $action                 = $request->param("action");
    $selectedUserIds        = $request->param("selectedUserIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($selectedUserIds, "Parámetro selectedUserIds no establecido")->notEmpty();
    
    /*
     * Delete selected professorUserIds 
     */
    
    $professorDetails = $professorDetailsResource->listProfessorDetails([
        "groupId"               => $groupId,
        "professorUserId"       => $selectedUserIds
    ]);
    
    foreach ($professorDetails as $professorDetail){
//        $professorDetail = new ProfessorDetail();
        
        $professorDetailsResource->delete($professorDetail);
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/professor-detail/[i:groupId]/add-professor", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $groupsResource, $professorDetailsResource, $usersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $groupId                = $request->param("groupId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     * Get current proffesorUserIds
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    $professorDetails           = $professorDetailsResource->listProfessorDetails([
        "groupId" => $groupId
    ]);
    
    $currentProfessorUserIds    = array_map(function(ProfessorDetail $professorDetail){
        return $professorDetail->getProfessorUserId();
    }, $professorDetails);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Añadir Docente";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->users         = $usersResource->listUsers([
        "UserProfileType" => UserProfile::TYPE_PROFESSOR
    ]);
    $service->currentProfessorUserIds = $currentProfessorUserIds;
    $service->groupId       = $groupId;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.professor-detail.add-professor.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/professor-detail/[i:groupId]/add-professor", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $professorDetailsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    $groupId                = $request->param("groupId");
    
    $userIds   = $request->param("userIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($userIds, "Parámetro userIds no establecido")->notEmpty();
    
    /*
     * Get store
     * Get product
     * Get current proffesorUserIds
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $finalProduct = $product->finalProduct;
//    $finalProduct = new Course;
    
    foreach ($userIds as $userId){
        $professorDetailsResource->insert(new ProfessorDetail([
            "courseId"          => $finalProduct->getCourseId(),
            "groupId"           => $groupId,
            "professorUserId"   => $userId,
            "status"            => ProfessorDetail::STATUS_PENDING
        ]));
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/academic-staff-management-detail", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $academicStaffManagementMembersResource, $usersResource, $userProfilesResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $academicStaffManagementMembers = $academicStaffManagementMembersResource->listAcademicStaffManagementMembers([
        "storeId" => $storeId
    ]);
    
    $memberIds = array_map(function(AcademicStaffManagementDetail $academicStaffManagementMember){
        return $academicStaffManagementMember->getPersonalUserId();
    }, $academicStaffManagementMembers);
    
    $userProfileIds = array_map(function(AcademicStaffManagementDetail $academicStaffManagementMember){
        return $academicStaffManagementMember->getProfileUserId();
    }, $academicStaffManagementMembers);
    
    $members    = $usersResource->listUsers([
        "userId" => $memberIds
    ]);
    
    $userProfiles = $userProfilesResource->listUserProfiles([
        "userProfileId" => $userProfileIds
    ]);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Plantel de Gestión Académica";
    
    //content params
    $service->project               = $project;
    $service->product               = $product;
    $service->staffMemberDetails    = $academicStaffManagementMembers;
    $service->members               = $members;
    $service->userProfiles          = $userProfiles;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.academic-staff-management-detail.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/academic-staff-management-detail/action", 
function ($request, $response, $service) 
use ($projectsResource, $academicStaffManagementMembersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $action                     = $request->param("action");
    $selectedStaffMemberUserIds = $request->param("selectedStaffMemberUserIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($selectedStaffMemberUserIds, "Parámetro selectedStaffMemberUserIds no establecido")->notEmpty();
    
    /*
     * Get staffMembers
     */
    
    $academicStaffManagementMembers = $academicStaffManagementMembersResource->listAcademicStaffManagementMembers([
        "storeId"           => $storeId,
        "personalUserId"    => $selectedStaffMemberUserIds
    ]);
    
    foreach ($academicStaffManagementMembers as $academicStaffManagementMember){
//        $academicStaffManagementMember = new AcademicStaffManagementDetail;
        
        if($action === "delete"){
            $academicStaffManagementMembersResource->delete($academicStaffManagementMember);
        }else if($action === "enable" || $action === "disable"){
            if($action === "enable"){
                $academicStaffManagementMember->setStatus(AcademicStaffManagementDetail::STATUS_ACTIVATED);
            }else{
                $academicStaffManagementMember->setStatus(AcademicStaffManagementDetail::STATUS_DEACTIVATED);
            }
            
            $academicStaffManagementMembersResource->update($academicStaffManagementMember);
        }
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/academic-staff-management-detail/add-staff-member", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource, $usersResource, $academicStaffManagementMembersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     * Get current staffMembers
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    $currentStaffMembers = $academicStaffManagementMembersResource->listAcademicStaffManagementMembers([
        "storeId" => $storeId
    ]);
    
    $currentStaffMemberUserIds = array_map(function(AcademicStaffManagementDetail $currentStaffMember){
        return $currentStaffMember->getPersonalUserId();
    }, $currentStaffMembers);
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Añadir Personal de Gestión Académica";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    $service->users         = $usersResource->listUsers([
        "UserProfileType" => UserProfile::TYPE_ACADEMIC_MANAGEMENT_ASISSTANT
    ]);
    $service->currentStaffMemberUserIds = $currentStaffMemberUserIds;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.academic-staff-management-detail.add-staff-member.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/academic-staff-management-detail/add-staff-member", 
function ($request, $response, $service) 
use ($projectsResource, $academicStaffManagementMembersResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $userIds   = $request->param("userIds");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($userIds, "Parámetro userIds no establecido")->notEmpty();
    
    /*
     * Get store
     * Get product
     */
    
    foreach ($userIds as $userId){
        $academicStaffManagementMembersResource->insert(new AcademicStaffManagementDetail([
            "personalUserId"    => $userId,
            "profileUserId"     => 8,
            "storeId"           => $storeId,
            "status"            => AcademicStaffManagementDetail::STATUS_ACTIVATED,
        ]));
    }
    
    $response->json([
        "success"       => true
    ]);
});

$this->respond("GET", "/[i:id]/product-structure/[i:storeId]/certification", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
    //header params
    $service->pageTitle = "Ver Propuesta de Estructura de Producto - Certificación";
    
    //content params
    $service->project       = $project;
    $service->product       = $product;
    
    //render
    $service->render(__PATH__ . "/app/view/project/proposal-product-structure.edit.certification.phtml");
});

$this->respond("POST", "/[i:id]/product-structure/[i:storeId]/certification", 
function ($request, $response, $service) 
use ($projectsResource, $productsResource, $storeItemsResource) {
    $projectId              = $request->param("id");
    $storeId                = $request->param("storeId");
    
    $minimunGrade                   = $request->param("minimun-grade");
    $maximunGrade                   = $request->param("maximun-grade");
    $evaluativeSystemInformation    = $request->param("evaluative-system-information");
    $diplomaInformation             = $request->param("diploma-information");
    
    $project = $projectsResource->get($projectId);
//    $project = new Project();
    
    /*
     * Initial Validators
     */
    $service->validate($project->getProjectId() || $project->getStoreId() !== $storeId, "Ha ocurrido un error")->isTrue();
    $service->validate($minimunGrade, "La nota mínima debe ser un número entero")->isInt();
    $service->validate($maximunGrade, "La nota máxima debe ser un número entero")->isInt();
    
    /*
     * Get store
     * Get product
     */
    $store      = $storeItemsResource->get($storeId);
    $product    = $productsResource->get($store->getProductId());
    
//    $product = new Product;
    
    $product->setMinimunGrade($minimunGrade);
    $product->setMaximunGrade($maximunGrade);
    $product->setEvaluativeSystemInformation($evaluativeSystemInformation);
    $product->setDiplomaInformation($diplomaInformation);
    
    $productUpdatedSuccess = $productsResource->update($product);
    
    $response->json([
        "success" => $productUpdatedSuccess
    ]);
});



$this->respond("GET", "/sample", function($request, $response, $service){
    require_once __PATH__ . "/app/model/Material.php";
    __class(new Material());
});