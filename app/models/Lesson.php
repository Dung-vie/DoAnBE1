<?php
class Lesson extends Database
{

    public function getLessonsByCourse($course_id)
    {
        $sql = parent::$connection->prepare("SELECT * FROM lessons WHERE course_id = ? ");
        $sql->bind_param("i", $course_id);
        return parent::select($sql);
    }

    public function createLesson($course_id, $title, $content, $order_number)
    {
        $sql = parent::$connection->prepare("INSERT INTO lessons (course_id, title, content, order_number) VALUES (?, ?, ?, ?)");
        $sql->bind_param("issi", $course_id, $title, $content, $order_number);
        return parent::select($sql);
    }

    public function getLessonsByInstructor($instructor_id) {
        $sql = parent::$connection->prepare("
            SELECT lessons.*, courses.title AS course_title
            FROM lessons 
            JOIN courses ON lessons.course_id = courses.id 
            WHERE courses.instructor_id = ?");
        $sql->bind_param("i", $instructor_id);
        return parent::select($sql); 
    }
    
}
