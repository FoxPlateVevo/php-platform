<?php

require_once __PATH__ . "/app/model/Model.php";

class StudentEvaluationDetail extends Model {
    protected $studentEvaluationId;
    protected $questionId;
    protected $answerContent;
    protected $updateDate;
    
    function getStudentEvaluationId() {
        return $this->studentEvaluationId;
    }

    function getQuestionId() {
        return $this->questionId;
    }

    function getAnswerContent() {
        return $this->answerContent;
    }

    function getUpdateDate() {
        return $this->updateDate;
    }

    function setStudentEvaluationId($studentEvaluationId) {
        $this->studentEvaluationId = $studentEvaluationId;
    }

    function setQuestionId($questionId) {
        $this->questionId = $questionId;
    }

    function setAnswerContent($answerContent) {
        $this->answerContent = $answerContent;
    }

    function setUpdateDate($updateDate) {
        $this->updateDate = $updateDate;
    }


}
