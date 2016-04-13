<?php
require_once __PATH__ . "/app/service/Administration.php";

/*
 * Set service and resource data
 */
$administration = new Service_Administration();
$categoriesResource = $administration->categories;

/*
 * Categories
 */
$this->respond("GET", "/category", function ($request, $response, $service) use ($categoriesResource) {
    /*
     * List all categories
     */
    $categories = $categoriesResource->listCategories();
    
    //header params
    $service->pageTitle = "Categorías";
    
    //content params
    $service->categories = $categories;
    
    //render
    $service->render(__PATH__ . "/app/view/administration/category.list.phtml");
});

$this->respond("GET", "/category/create", function ($request, $response, $service) {
    //header params
    $service->pageTitle = "Crear Categoría";
    
    //render
    $service->render(__PATH__ . "/app/view/administration/category.create.phtml");
});

$this->respond("POST", "/category/create", function ($request, $response, $service) use ($categoriesResource) {
    $name           = $request->param("category-name");
    $description    = $request->param("category-description");
    
    /*
     * Validators
     */
    $service->validate($name, "Se debe establecer un nombre para la categoría")->notEmpty();
    
    $insertedCategoryId = $categoriesResource->insert(new Category([
        "name"          => strtoupper($name),
        "description"   => $description,
        "imagePath"     => null,
        "color"         => null
    ]));
    
    $response->redirect("/administration/category");
});

$this->respond("GET", "/category/[i:categoryId]", function ($request, $response, $service) use ($categoriesResource) {
    $categoryId  = $request->param("categoryId");
    
    /*
     * Get category
     */
    $category = $categoriesResource->get($categoryId);
    
    //header params
    $service->pageTitle = "Ver Categoría";
    
    //content params
    $service->category = $category;
    
    //render
    $service->render(__PATH__ . "/app/view/administration/category.edit.phtml");
});

$this->respond("POST", "/category/[i:categoryId]", function ($request, $response, $service) use ($categoriesResource) {
    $categoryId     = $request->param("categoryId");
    $name           = $request->param("category-name");
    $description    = $request->param("category-description");
    
    /*
     * Validators
     */
    $service->validate($name, "Se debe establecer un nombre para la categoría")->notEmpty();
    
    /*
     * Get category
     */
    $category = $categoriesResource->get($categoryId);
//    $category = new Category;
    
    $category->setName($name);
    $category->setDescription($description);
    
    $success = $categoriesResource->update($category);
    
    $response->redirect("/administration/category/{$categoryId}");
});

