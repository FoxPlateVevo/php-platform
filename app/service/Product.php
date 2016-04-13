<?php

require_once __PATH__ . "/app/model/AcademicStaffManagementDetail.php";
require_once __PATH__ . "/app/model/Course.php";
require_once __PATH__ . "/app/model/Group.php";
require_once __PATH__ . "/app/model/EducativeResource.php";
require_once __PATH__ . "/app/model/Product.php";
require_once __PATH__ . "/app/model/Program.php";
require_once __PATH__ . "/app/model/Store.php";

class Service_Product {
    public $products;
    public $storeItems;
    public $groups;
    public $educativeResources;
    public $academicStaffManagementMembers;

    public function __construct() {
        $this->products             = new Service_Product_Products_Resource();
        $this->storeItems           = new Service_Product_StoreItems_Resource();
        $this->groups               = new Service_Product_Groups_Resource();
        $this->educativeResources   = new Service_Product_EducativeResources_Resource();
        $this->academicStaffManagementMembers = new Service_Product_AcademicStaffManagementMembers_Resource();
    }
}

/*
 * Resource SCRUD Management
 */
class Service_Product_Products_Resource {
    public function listProducts(array $optionParams = null){
        /*
         * productId           : optional String, Array
         */
        $alternateAttributes = [
            "productId"    => "P.cod_producto"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        P.cod_producto,
        P.nombre,
        P.tipo,
        P.cod_categoria,
        P.informacion,
        P.informacion_objetivo,
        P.informacion_publico_objetivo,
        P.fecha_registro,
        P.cod_usuario_responsable,
        P.presentacion_tipo,
        P.presentacion_contenido,
        P.nota_minima,
        P.nota_maxima,
        P.informacion_sistema_evaluativo,
        P.informacion_diploma,
        P.carpeta,
        PRO.cod_programa,
        PRO.tipo as pro_tipo,
        PRO.cod_producto as pro_cod_producto,
        PRO.cod_usuario_certificante,
        CUR.cod_curso,
        CUR.informacion_perfil_docente,
        CUR.cod_producto as cur_cod_producto
        FROM producto P
        LEFT JOIN programa PRO ON PRO.cod_producto = P.cod_producto
        LEFT JOIN curso CUR ON CUR.cod_producto = P.cod_producto
        {$CONDITIONALS_PART_STRING}
        ";
        
        $productsData = db::fetchAll($query);
        
        $products = [];
        
        foreach ($productsData as $productData){
            $product = new Product([
                "productId"                     => $productData->cod_producto,
                "name"                          => $productData->nombre,
                "type"                          => $productData->tipo,
                "categoryId"                    => $productData->cod_categoria,
                "information"                   => $productData->informacion,
                "objectiveInformation"          => $productData->informacion_objetivo,
                "objectivePublicInformation"    => $productData->informacion_publico_objetivo,
                "registryDate"                  => $productData->fecha_registro,
                "responsibleUserId"             => $productData->cod_usuario_responsable,
                "typePresentation"              => $productData->presentacion_tipo,
                "contentPresentation"           => $productData->presentacion_contenido,
                "minimunGrade"                  => $productData->nota_minima,
                "maximunGrade"                  => $productData->nota_maxima,
                "evaluativeSystemInformation"   => $productData->informacion_sistema_evaluativo,
                "diplomaInformation"            => $productData->informacion_diploma,
                "directory"                     => $productData->carpeta
            ]);
            
            if($product->getType() === Product::TYPE_PRODUCT_PROGRAM){
                $finalProduct = new Program([
                    "programId"         => $productData->cod_programa,
                    "type"              => $productData->pro_tipo,
                    "productId"         => $productData->pro_cod_producto,
                    "certifierUserId"   => $productData->cod_usuario_certificante
                ]);
            }else if($product->getType() === Product::TYPE_PRODUCT_COURSE){
                $finalProduct = new Course([
                    "courseId"                      => $productData->cod_curso,
                    "professorProfileInformation"   => $productData->informacion_perfil_docente,
                    "productId"                     => $productData->cur_cod_producto
                ]);
            }
            
            $product->finalProduct = $finalProduct;
            
            $products[$productData->cod_producto] = $product;
        }
        
        return $products;
    }
    
    public function insert(Product $product, $finalProduct){
        $insertedProductId = null;
        
        if($finalProduct instanceof Program || $finalProduct instanceof Course){
            $insertedProductId = db::insert("producto", [
                "cod_producto"                      => $product->getProductId(),
                "nombre"                            => $product->getName(),
                "tipo"                              => $product->getType(),
                "cod_categoria"                     => $product->getCategoryId(),
                "informacion"                       => $product->getInformation(),
                "informacion_objetivo"              => $product->getObjectiveInformation(),
                "informacion_publico_objetivo"      => $product->getObjectivePublicInformation(),
                "fecha_registro"                    => $product->getRegistryDate(),
                "cod_usuario_responsable"           => $product->getResponsibleUserId(),
                "presentacion_tipo"                 => $product->getTypePresentation(),
                "presentacion_contenido"            => $product->getContentPresentation(),
                "nota_minima"                       => $product->getMinimunGrade(),
                "nota_maxima"                       => $product->getMaximunGrade(),
                "informacion_sistema_evaluativo"    => $product->getEvaluativeSystemInformation(),
                "informacion_diploma"               => $product->getDiplomaInformation(),
                "carpeta"                           => $product->getDirectory()
            ]);

            if($insertedProductId){
                $insertedFinalProductId = false;
                
                if($product->getType() === Product::TYPE_PRODUCT_PROGRAM && $finalProduct instanceof Program){
                    $insertedFinalProductId = db::insert("programa", [
                        "tipo"                      => $finalProduct->getType(),
                        "cod_producto"              => $insertedProductId,
                        "cod_usuario_certificante"  => $finalProduct->getCertifierUserId()
                    ]);
                }else if($product->getType() === Product::TYPE_PRODUCT_COURSE && $finalProduct instanceof Course){
                    $insertedFinalProductId = db::insert("curso", [
                        "informacion_perfil_docente"    => $finalProduct->getProfessorProfileInformation(),
                        "cod_producto"                  => $insertedProductId
                    ]);
                }
                
                if(!$insertedFinalProductId){
                    db::delete("producto", [
                        "cod_producto"   => $insertedProductId
                    ]);
                    
                    $insertedProductId = null;
                }
            }
        }
        
        return $insertedProductId;
    }
    
    public function get($productId){
        return array_pop($this->listProducts([
            "productId" => $productId
        ]));
    }
    
    public function update(Product $product, $finalProduct = null){
        $success = false;
        
        $successUpdatedProduct = db::update("producto", [
            "nombre"                            => $product->getName(),
            "tipo"                              => $product->getType(),
            "cod_categoria"                     => $product->getCategoryId(),
            "informacion"                       => $product->getInformation(),
            "informacion_objetivo"              => $product->getObjectiveInformation(),
            "informacion_publico_objetivo"      => $product->getObjectivePublicInformation(),
            "fecha_registro"                    => $product->getRegistryDate(),
            "cod_usuario_responsable"           => $product->getResponsibleUserId(),
            "presentacion_tipo"                 => $product->getTypePresentation(),
            "presentacion_contenido"            => $product->getContentPresentation(),
            "nota_minima"                       => $product->getMinimunGrade(),
            "nota_maxima"                       => $product->getMaximunGrade(),
            "informacion_sistema_evaluativo"    => $product->getEvaluativeSystemInformation(),
            "informacion_diploma"               => $product->getDiplomaInformation(),
            "carpeta"                           => $product->getDirectory()
        ], [
            "cod_producto"                      => $product->getProductId()
        ]);
        
        if($finalProduct && ($finalProduct instanceof Program || $finalProduct instanceof Course) && $successUpdatedProduct){
            if($product->getType() === Product::TYPE_PRODUCT_PROGRAM && $finalProduct instanceof Program){
                $success = db::update("programa", [
                    "tipo"                      => $finalProduct->getType(),
                    "cod_producto"              => $product->getProductId(),
                    "cod_usuario_certificante"  => $finalProduct->getCertifierUserId()
                ], [
                    "cod_programa"              => $finalProduct->getProgramId()
                ]);
            }else if($product->getType() === Product::TYPE_PRODUCT_COURSE && $finalProduct instanceof Course){
                $success = db::update("curso", [
                    "informacion_perfil_docente"    => $finalProduct->getProfessorProfileInformation(),
                    "cod_producto"                  => $product->getProductId(),
                ], [
                    "cod_curso"                     => $finalProduct->getCourseId()
                ]);
            }
        }else{
            $success = $successUpdatedProduct;
        }
        
        return $success;
    }
    
    public function delete($productId){
        $success = $successDeletedFinalProduct = false;
        
        $product   = $this->get($productId);
        $finalProduct = $product->finalProduct;
//        $product = new Product();
        
        if($product->getType() === Product::TYPE_PRODUCT_PROGRAM){
//            $finalProduct = new Program();
            
            $successDeletedFinalProduct = db::delete("programa", [
                "cod_programa" => $finalProduct->getProgramId()
            ]);
        }elseif($product->getType() === Product::TYPE_PRODUCT_COURSE){
//            $finalProduct = new Course();

            $successDeletedFinalProduct = db::delete("curso", [
                "cod_curso" => $finalProduct->getCourseId()
            ]);
        }
        
        if($successDeletedFinalProduct){
            $success = db::delete("producto", [
                "cod_producto" => $product->getProductId()
            ]);
        }
        
        return $success;
    }
}

class Service_Product_StoreItems_Resource {
    public function listStoreItems(array $optionParams = null){
        /*
         * storeId    : optional String, Array
         * typeEntry    : optional String, Array
         */
        $alternateAttributes = [
            "storeId"   => "A.cod_almacen",
            "typeEntry"   => "A.tipo_ingreso",
            "status"   => "A.estado",
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        A.cod_almacen,
        A.tipo_ingreso,
        A.fecha_registro,
        A.estado,
        A.cod_producto,
        A.tipo_moneda,
        A.precio,
        A.precio_minimo,
        A.stock,
        A.informacion_venta,
        A.venta_presentacion_tipo,
        A.venta_presentacion_contenido,
        A.es_grupo_configurado
        FROM almacen A
        {$CONDITIONALS_PART_STRING}
        ";
        
        $storeItemsData = db::fetchAll($query);
        
        $storeItems = [];
        
        foreach ($storeItemsData as $storeItemData){
            $storeItems[$storeItemData->cod_almacen] = new Store([
                "storeId"                   => $storeItemData->cod_almacen,
                "typeEntry"                 => $storeItemData->tipo_ingreso,
                "registryDate"              => $storeItemData->fecha_registro,
                "status"                    => $storeItemData->estado,
                "productId"                 => $storeItemData->cod_producto,
                "typeCoin"                  => $storeItemData->tipo_moneda,
                "price"                     => $storeItemData->precio,
                "minimunPrice"              => $storeItemData->precio_minimo,
                "stock"                     => $storeItemData->stock,
                "saleInformation"           => $storeItemData->informacion_venta,
                "saleTypePresentation"      => $storeItemData->venta_presentacion_tipo,
                "saleContentPresentation"   => $storeItemData->venta_presentacion_contenido,
                "isConfiguredGroup"         => $storeItemData->es_grupo_configurado
            ]);
        }
        
        return $storeItems;
    }
    
    public function insert(Store $storeItem){
        $insertedStoreItemId = db::insert("almacen", [
            "tipo_ingreso"                  => $storeItem->getTypeEntry(),
            "fecha_registro"                => $storeItem->getRegistryDate(),
            "estado"                        => $storeItem->getStatus(),
            "cod_producto"                  => $storeItem->getProductId(),
            "tipo_moneda"                   => $storeItem->getTypeCoin(),
            "precio"                        => $storeItem->getPrice(),
            "precio_minimo"                 => $storeItem->getMinimunPrice(),
            "stock"                         => $storeItem->getStock(),
            "informacion_venta"             => $storeItem->getSaleInformation(),
            "venta_presentacion_tipo"       => $storeItem->getSaleTypePresentation(),
            "venta_presentacion_contenido"  => $storeItem->getSaleContentPresentation(),
            "es_grupo_configurado"          => $storeItem->getIsConfiguredGroup()
        ]);
        
        return $insertedStoreItemId;
    }
    
    public function get($storeItemId){
        return array_pop($this->listStoreItems([
            "storeId" => $storeItemId
        ]));
    }
    
    public function update(Store $storeItem){
        $affectedRows = db::update("almacen", [
            "tipo_ingreso"                  => $storeItem->getTypeEntry(),
            "fecha_registro"                => $storeItem->getRegistryDate(),
            "estado"                        => $storeItem->getStatus(),
            "cod_producto"                  => $storeItem->getProductId(),
            "tipo_moneda"                   => $storeItem->getTypeCoin(),
            "precio"                        => $storeItem->getPrice(),
            "precio_minimo"                 => $storeItem->getMinimunPrice(),
            "stock"                         => $storeItem->getStock(),
            "informacion_venta"             => $storeItem->getSaleInformation(),
            "venta_presentacion_tipo"       => $storeItem->getSaleTypePresentation(),
            "venta_presentacion_contenido"  => $storeItem->getSaleContentPresentation(),
            "es_grupo_configurado"          => $storeItem->getIsConfiguredGroup()
        ], [
            "cod_almacen"                   => $storeItem->getStoreId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($storeItemId){
        $affectedRows = db::delete("almacen", [
            "cod_almacen" => $storeItemId
        ]);
        
        return $affectedRows;
    }
}

class Service_Product_Groups_Resource {
    public function listGroups(array $optionParams = null){
        /*
         * groupId      : optional String, Array
         * typeEntry    : optional String, Array
         * storeId      : optional String, Array
         */
        $alternateAttributes = [
            "groupId"   => "G.cod_grupo",
            "typeEntry" => "G.tipo_ingreso",
            "storeId"   => "G.cod_almacen"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        G.cod_grupo,
        G.descripcion,
        G.cod_sede,
        G.fecha_inicio,
        G.fecha_final,
        G.tipo_ingreso,
        G.cod_almacen
        FROM grupo G
        {$CONDITIONALS_PART_STRING}
        ORDER BY G.cod_grupo ASC
        ";
        
        $groupsData = db::fetchAll($query);
        
        $groups = [];
        
        foreach ($groupsData as $groupData){
            $groups[$groupData->cod_grupo] = new Group([
                "groupId"           => $groupData->cod_grupo,
                "description"       => $groupData->descripcion,
                "headquarterId"     => $groupData->cod_sede,
                "starDate"          => $groupData->fecha_inicio,
                "finalDate"         => $groupData->fecha_final,
                "typeEntry"         => $groupData->tipo_ingreso,
                "storeId"           => $groupData->cod_almacen
            ]);
        }
        
        return $groups;
    }
    
    public function insert(Group $group){
        $insertedGroupId = db::insert("grupo", [
            "descripcion"       => $group->getDescription(),
            "cod_sede"          => $group->getHeadquarterId(),
            "fecha_inicio"      => $group->getStarDate(),
            "fecha_final"       => $group->getFinalDate(),
            "tipo_ingreso"      => $group->getTypeEntry(),
            "cod_almacen"       => $group->getStoreId()
        ]);
        
        return $insertedGroupId;
    }
    
    public function get($groupId){
        return array_pop($this->listGroups([
            "groupId" => $groupId
        ]));
    }
    
    public function update(Group $group){
        $affectedRows = db::update("grupo", [
            "descripcion"       => $group->getDescription(),
            "cod_sede"          => $group->getHeadquarterId(),
            "fecha_inicio"      => $group->getStarDate(),
            "fecha_final"       => $group->getFinalDate(),
            "tipo_ingreso"      => $group->getTypeEntry(),
            "cod_almacen"       => $group->getStoreId()
        ], [
            "cod_grupo"         => $group->getGroupId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($groupId){
        $affectedRows = db::delete("grupo", [
            "cod_grupo" => $groupId
        ]);
        
        return $affectedRows;
    }
}

class Service_Product_EducativeResources_Resource {
    public function listEducativeResources(array $optionParams = null){
        /*
         * groupId    : optional String, Array
         */
        $alternateAttributes = [
            "educativeResourceId"   => "RE.cod_recurso_educativo",
            "courseId"              => "RE.cod_curso"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        RE.cod_recurso_educativo,
        RE.nombre,
        RE.descripcion,
        RE.cantidad,
        RE.cod_curso
        FROM recurso_educativo RE
        {$CONDITIONALS_PART_STRING}
        ORDER BY RE.cod_recurso_educativo ASC
        ";
        
        $educativeResourcesData = db::fetchAll($query);
        
        $educativeResources = [];
        
        foreach ($educativeResourcesData as $educativeResourceData){
            $educativeResources[$educativeResourceData->cod_recurso_educativo] = new EducativeResource([
                "educativeResourceId"   => $educativeResourceData->cod_recurso_educativo,
                "name"                  => $educativeResourceData->nombre,
                "description"           => $educativeResourceData->descripcion,
                "quantity"              => $educativeResourceData->cantidad,
                "courseId"              => $educativeResourceData->cod_curso
            ]);
        }
        
        return $educativeResources;
    }
    
    public function insert(EducativeResource $educativeResource){
        $insertedEducativeResourceId = db::insert("recurso_educativo", [
            "nombre"        => $educativeResource->getName(),
            "descripcion"   => $educativeResource->getDescription(),
            "cantidad"      => $educativeResource->getQuantity(),
            "cod_curso"     => $educativeResource->getCourseId()
        ]);
        
        return $insertedEducativeResourceId;
    }
    
    public function get($educativeResourceId){
        return array_pop($this->listEducativeResources([
            "educativeResourceId" => $educativeResourceId
        ]));
    }
    
    public function update(EducativeResource $educativeResource){
        $affectedRows = db::update("recurso_educativo", [
            "nombre"        => $educativeResource->getName(),
            "descripcion"   => $educativeResource->getDescription(),
            "cantidad"      => $educativeResource->getQuantity(),
            "cod_curso"     => $educativeResource->getCourseId()
        ], [
            "cod_recurso_educativo" => $educativeResource->getEducativeResourceId()
        ]);
        
        return $affectedRows;
    }
    
    public function delete($educativeResourceId){
        $affectedRows = db::delete("recurso_educativo", [
            "cod_recurso_educativo" => $educativeResourceId
        ]);
        
        return $affectedRows;
    }
}

class Service_Product_AcademicStaffManagementMembers_Resource{
    public function listAcademicStaffManagementMembers(array $optionParams = null) {
        /*
         * personalUserId   : optional String, Array
         * profileUserId    : optional String, Array
         * storeId          : optional String, Array
         */
        $alternateAttributes = [
            "personalUserId"            => "PGAD.cod_usuario_personal",
            "profileUserId"     => "PGAD.cod_perfil_usuario",
            "storeId"     => "PGAD.cod_almacen"
        ];
        
        $CONDITIONALS_PART_STRING = get_conditional_string($alternateAttributes, $optionParams);
        
        $query = "
        SELECT 
        PGAD.cod_usuario_personal,
        PGAD.cod_perfil_usuario,
        PGAD.cod_almacen,
        PGAD.fecha_registro,
        PGAD.estado
        FROM personal_gestion_academica_det PGAD
        {$CONDITIONALS_PART_STRING}
        ";
        
        $academicStaffManagementMembersData = db::fetchAll($query);
        
        $academicStaffManagementMembers = [];
        
        foreach ($academicStaffManagementMembersData as $academicStaffManagementMemberData){
            $academicStaffManagementMembers[] = new AcademicStaffManagementDetail([
                "personalUserId"    => $academicStaffManagementMemberData->cod_usuario_personal,
                "profileUserId"     => $academicStaffManagementMemberData->cod_perfil_usuario,
                "storeId"           => $academicStaffManagementMemberData->cod_almacen,
                "registryDate"      => $academicStaffManagementMemberData->fecha_registro,
                "status"            => $academicStaffManagementMemberData->estado
            ]);
        }
        
        return $academicStaffManagementMembers;
    }
    
    public function insert(AcademicStaffManagementDetail $academicStaffManagementMember){
        $insertedAcademicStaffManagementMemberId = db::insert("personal_gestion_academica_det", [
          "cod_usuario_personal"    => $academicStaffManagementMember->getPersonalUserId(),
          "cod_perfil_usuario"      => $academicStaffManagementMember->getProfileUserId(),
          "cod_almacen"             => $academicStaffManagementMember->getStoreId(),
          "fecha_registro"          => get_datetime(),
          "estado"                  => $academicStaffManagementMember->getStatus()
        ]);
        
        return $insertedAcademicStaffManagementMemberId;
    }
    
    public function update(AcademicStaffManagementDetail $academicStaffManagementMember){
        $affectedRows = db::update("personal_gestion_academica_det", [
            "fecha_registro"        => $academicStaffManagementMember->getRegistryDate(),
            "estado"                => $academicStaffManagementMember->getStatus()
        ], [
            "cod_usuario_personal"    => $academicStaffManagementMember->getPersonalUserId(),
            "cod_perfil_usuario"      => $academicStaffManagementMember->getProfileUserId(),
            "cod_almacen"             => $academicStaffManagementMember->getStoreId(),
        ]);
        
        return $affectedRows;
    }
    
    public function delete(AcademicStaffManagementDetail $academicStaffManagementMember){
        $affectedRows = db::delete("personal_gestion_academica_det", [
            "cod_usuario_personal"    => $academicStaffManagementMember->getPersonalUserId(),
            "cod_perfil_usuario"      => $academicStaffManagementMember->getProfileUserId(),
            "cod_almacen"             => $academicStaffManagementMember->getStoreId(),
        ]);
        
        return $affectedRows;
    }
}