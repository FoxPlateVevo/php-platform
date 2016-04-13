<?php

require_once __PATH__ . "/app/model/Category.php";

class Service_Administration {
    public $categories;
    
    public function __construct() {
        $this->categories = new Service_Product_Categories_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Product_Categories_Resource {
    public function listCategories(array $optionParams = null){
        /*
         * categoryId    : optional String, Array
         */
        $alternateAttributes = [
            "categoryId"    => "C.cod_categoria"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        C.cod_categoria,
        C.nombre,
        C.descripcion,
        C.path_imagen,
        C.color
        FROM categoria C
        {$CONDITIONALS_PART_STRING}
        ";
        
        $categoriesData = db::fetchAll($query);
        
        $categories = [];
        
        foreach ($categoriesData as $categoryData){
            $categories[$categoryData->cod_categoria] = new Category([
                "categoryId"    => $categoryData->cod_categoria,
                "name"          => $categoryData->nombre,
                "description"   => $categoryData->descripcion,
                "imagePath"     => $categoryData->path_imagen,
                "color"         => $categoryData->color
            ]);
        }
        
        return $categories;
    }
    
    public function insert(Category $category){
        $insertedHeadquarterId = db::insert("categoria", [
            "nombre"        => $category->getName(),
            "descripcion"   => $category->getDescription(),
            "path_imagen"   => $category->getImagePath(),
            "color"         => $category->getColor()
        ]);
        
        return $insertedHeadquarterId;
    }
    
    public function get($categoryId){
        return array_pop($this->listCategories([
            "categoryId" => $categoryId
        ]));
    }
    
    public function update(Category $category){
        $affectedRows = db::update("categoria", [
            "nombre"        => $category->getName(),
            "descripcion"   => $category->getDescription(),
            "path_imagen"   => $category->getImagePath(),
            "color"         => $category->getColor()
        ], [
            "cod_categoria" => $category->getCategoryId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($categoryId){
        $affectedRows = db::delete("categoria", [
            "cod_sede" => $categoryId
        ]);
        
        return $affectedRows;
    }
}

