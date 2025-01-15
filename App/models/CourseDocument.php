<?php
namespace App\models;
use App\models\Course;

class CourseDocument extends Course {
    private $documentPath;

    public function __construct($title, $description, $categoryId, $teacherId, $documentPath, $status = 'soumis') {
        parent::__construct($title, $description, $categoryId, $teacherId, $status);
        $this->documentPath = $documentPath;
    }

    public function insertCourse() {
        
    }
 
}