<?php
namespace App\models;

use App\models\Course;
use PDO;

class CourseVideo {
    private $pdo;
    private $title;
    private $description;
    private $categoryId;
    private $teacherId;
    private $videoUrl;
    private $status;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function setCourseData($title, $description, $categoryId, $teacherId, $videoUrl, $status = 'soumis') {
        $this->title = $title;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->teacherId = $teacherId;
        $this->videoUrl = $videoUrl;
        $this->status = $status;
    }

    public function insertCourse() {
        $query = "INSERT INTO courses (titre, description, contenu, category_id, enseignant_id, status, video_url) 
                  VALUES (:title, :description, 'video', :categoryId, :teacherId, :status, :videoUrl)";
        
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':title', $this->title);
        $stmt->bindParam(':description', $this->description);
        $stmt->bindParam(':categoryId', $this->categoryId, PDO::PARAM_INT);
        $stmt->bindParam(':teacherId', $this->teacherId, PDO::PARAM_INT);
        $stmt->bindParam(':status', $this->status);
        $stmt->bindParam(':videoUrl', $this->videoUrl);

        if ($stmt->execute()) {
            return $this->pdo->lastInsertId();
        } else {
            throw new \Exception("Erreur dans l'ajout de video");
        }
    }

   
}


/*
<?php
namespace App\models;

abstract class Course {
    protected $title;
    protected $description;
    protected $categoryId;
    protected $teacherId;
    protected $status;

    public function __construct($title, $description, $categoryId, $teacherId, $status = 'soumis') {
        $this->title = $title;
        $this->description = $description;
        $this->categoryId = $categoryId;
        $this->teacherId = $teacherId;
        $this->status = $status;
    }

    abstract public function insertCourse();

    public function updateStatus($newStatus) {
        $this->status = $newStatus;
    }
    
}

*/