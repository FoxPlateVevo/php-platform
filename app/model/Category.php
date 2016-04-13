<?php

require_once __PATH__ . "/app/model/Model.php";

class Category extends Model {
    protected $categoryId;
    protected $name;
    protected $description;
    protected $imagePath;
    protected $color;
    
    function getCategoryId() {
        return $this->categoryId;
    }

    function getName() {
        return $this->name;
    }

    function getDescription() {
        return $this->description;
    }

    function getImagePath() {
        return $this->imagePath;
    }

    function getColor() {
        return $this->color;
    }

    function setCategoryId($categoryId) {
        $this->categoryId = $categoryId;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setImagePath($imagePath) {
        $this->imagePath = $imagePath;
    }

    function setColor($color) {
        $this->color = $color;
    }


}
