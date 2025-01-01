<?php
class Instructor extends Database
{
    public function getAllInstructors()
    {
        $sql = parent::$connection->prepare("SELECT * FROM instructors");
        return parent::select($sql);
    }

    public function findById($id)
    {
        $sql = parent::$connection->prepare("SELECT * FROM `instructors` WHERE `id` = ?");
        $sql->bind_param('i', $id);
        return parent::select($sql)[0];
    }

    public function findByKeyWord($keyword)
    {
        $sql = parent::$connection->prepare("SELECT * FROM `instructors` WHERE `name` LIKE ?");
        $keyword = "%{$keyword}%";
        $sql->bind_param('s', $keyword);
        return parent::select($sql);
    }

    public function getInstructorIdByUserId($user_id)
    {
        $sql = parent::$connection->prepare("SELECT id FROM instructors WHERE user_id = ?");
        $sql->bind_param("i", $user_id);
        return parent::select($sql)[0];
    }

    public function getInstructorByUser($user_id)
    {
        $sql = parent::$connection->prepare("SELECT *
                                            FROM users
                                            JOIN instructors ON instructors.user_id = ?
                                        ");
        $sql->bind_param("i", $user_id);
        // var_dump(parent::select($sql)[0]);
        return parent::select($sql)[0];
    }
    // public function add($name, $major, $image)
    // {
    //     $query = self::$connection->prepare("INSERT INTO instructors (name, major, image) VALUES (?, ?, ?)");
    //     $query->bind_param("sss", $name, $major, $image);
    //     return $query->execute();
    // }

    // public function update($id, $name, $major, $image)
    // {
    //     $query = self::$connection->prepare("UPDATE instructors SET name = ?, major = ?, image = ? WHERE id = ?");
    //     $query->bind_param("sssi", $name, $major, $image, $id);
    //     return $query->execute();
    // }

    // public function delete($id)
    // {
    //     $query = self::$connection->prepare("DELETE FROM instructors WHERE id = ?");
    //     $query->bind_param("i", $id);
    //     return $query->execute();
    // }
}
