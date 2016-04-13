<?php

require_once __PATH__ . "/app/model/Model.php";

class Answer extends Model {
    protected $answerId;
    protected $description;
    protected $order;
    protected $isCorrectAnswer;
    protected $questionId;

    function getAnswerId() {
        return $this->answerId;
    }

    function getDescription() {
        return $this->description;
    }

    function getOrder() {
        return $this->order;
    }

    function getIsCorrectAnswer() {
        return $this->isCorrectAnswer;
    }

    function getQuestionId() {
        return $this->questionId;
    }

    function setAnswerId($answerId) {
        $this->answerId = $answerId;
    }

    function setDescription($description) {
        $this->description = $description;
    }

    function setOrder($order) {
        $this->order = $order;
    }

    function setIsCorrectAnswer($isCorrectAnswer) {
        $this->isCorrectAnswer = $isCorrectAnswer;
    }

    function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }


}
