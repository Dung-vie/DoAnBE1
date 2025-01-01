    <?php
    class Course extends Database
    {
        public function getAllCourses()
        {
            $sql = parent::$connection->prepare("SELECT * FROM courses WHERE status = '1' ");
            return parent::select($sql);
        }

        public function getCourseById($id)
        {
            $sql = parent::$connection->prepare("SELECT * FROM courses WHERE id = ?");
            $sql->bind_param("i", $id);
            return parent::select($sql)[0];
        }

        public function findById($id)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("SELECT * FROM `courses` WHERE `id` = ?");
            $sql->bind_param('i', $id);
            // 3 & 4
            return parent::select($sql)[0];
        }


        public function findByKeyWord($keyword)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("SELECT * FROM `courses` WHERE `title` LIKE ?");
            $keyword = "%{$keyword}%";
            $sql->bind_param('s', $keyword);
            // 3 & 4
            return parent::select($sql);
        }


        public function add($title, $price, $description, $instructor_id)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("INSERT INTO `courses`(`title`, `price`, `description`, `instructor_id`) VALUES (?, ?, ?, ?)");
            $sql->bind_param('sisi', $title, $price, $description, $instructor_id);
            // 3 & 4
            return $sql->execute();
        }

        public function update($id, $title, $description, $price, $instructor_id)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("UPDATE `courses` SET `title`=?,`description`=?,`price`=?,`instructor_id`=? WHERE `id`=?");
            $sql->bind_param('ssiii',  $title, $description, $price, $instructor_id, $id);
            // 3 & 4
            return $sql->execute();
        }

        public function delete($courseId)
        {
            $sql = parent::$connection->prepare("DELETE FROM `courses` WHERE `id`=?");
            $sql->bind_param('i', $courseId);
            // 3 & 4
            return $sql->execute();
        }

        public function deleteAll($courseId)
        {
            // Tạo chuỗi kiểu ?,?,?
            $insertPlace = str_repeat("?,", count($courseId) - 1) . "?";
            // Tạo chuỗi iiiiiiii
            $insertType = str_repeat('i', count($courseId));


            // 2. Tạo câu query
            $sql = parent::$connection->prepare("DELETE FROM `courses` WHERE `id` IN ($insertPlace)");
            $sql->bind_param($insertType, ...$courseId);

            // 3 & 4
            return $sql->execute();
        }

        public function allBin()
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare('SELECT `courses`.*
                                                FROM `courses`
                                                WHERE `courses`.`status`=0');
            // 3 & 4
            return parent::select($sql);
        }

        public function bin($courseId)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("UPDATE `courses` SET `status`= 0 WHERE `id`=?");
            $sql->bind_param('i', $courseId);
            // 3 & 4
            return $sql->execute();
        }

        public function restore($courseId)
        {
            // 2. Tạo câu query
            $sql = parent::$connection->prepare("UPDATE `courses` SET `status`= 1 WHERE `id`=?");
            $sql->bind_param('i', $courseId);
            // 3 & 4
            return $sql->execute();
        }

        // Lấy tên giảng viên
        public function getName($instructor_id)
        {
            $sql = parent::$connection->prepare("SELECT users.id, users.name
                                                FROM courses
                                                JOIN instructors ON courses.instructor_id = instructors.id
                                                JOIN users ON instructors.user_id = users.id
                                                WHERE courses.id = ?;
                                    ");
            $sql->bind_param("i", $instructor_id);
            // 3 & 4
            return parent::select($sql)[0];
        }
        public function getCourse($instructor_id)
        {
            $sql = parent::$connection->prepare("SELECT *
                                                FROM courses
                                                JOIN instructors ON courses.instructor_id = instructors.id
                                                JOIN users ON instructors.user_id = users.id
                                                WHERE courses.id = ?;
                                    ");
            $sql->bind_param("i", $instructor_id);
            // 3 & 4
            // var_dump(parent::select($sql)[0]);
            return parent::select($sql)[0];
        }
        public function getCourseByInstructor($user_id)
        {
            // var_dump($user_id);
            $sql = parent::$connection->prepare("SELECT *
                                                FROM courses
                                                JOIN instructors ON courses.instructor_id = instructors.id
                                                JOIN users ON instructors.user_id = users.id
                                                WHERE users.id = ?
                                    ");
            $sql->bind_param("i", $user_id);
            // 3 & 4
            // var_dump(parent::select($sql));
            return parent::select($sql);
        }

        public function getLessonsByCourse($courseId)
        {
            $sql = parent::$connection->prepare("SELECT courses.title AS course_title, lessons.id AS lesson_id, lessons.title AS lesson_title, lessons.content, lessons.video
                                                FROM courses
                                                JOIN lessons ON lessons.course_id = courses.id
                                                WHERE courses.id = ? ");
            $sql->bind_param("i", $courseId);
            // 3 & 4
            return parent::select($sql);
        }

        public function enrollCourse($userId, $courseId)
        {
            $sql = parent::$connection->prepare("SELECT statusCourse FROM enrollments WHERE user_id = ? AND course_id = ?");
            $sql->bind_param("ii", $userId, $courseId);

            $result = parent::select($sql);

            if (empty($result)) {
                $sql = parent::$connection->prepare("INSERT INTO enrollments (user_id, course_id, statusCourse) VALUES (?, ?, 1)");
                $sql->bind_param("ii", $userId, $courseId);
                return $sql->execute();
            }

            if ($result[0]['statusCourse'] == 1) {
                return false;
            }

            $sql = parent::$connection->prepare("UPDATE enrollments SET statusCourse = 1 WHERE user_id = ? AND course_id = ?");
            $sql->bind_param("ii", $userId, $courseId);
            return $sql->execute();
        }

        public function confirmEnrollment($userId, $courseId)
        {
            $sql = parent::$connection->prepare("
            UPDATE enrollments SET statusCourse = 1 WHERE user_id = ? AND course_id = ?
            ");
            $sql->bind_param("ii", $userId, $courseId);
            return $sql->execute();
        }
    }
