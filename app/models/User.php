<?php
class User extends Database
{
    public function login($email, $password)
    {
        $sql = parent::$connection->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $user = parent::select($sql);
        if (count($user) > 0) {
            if (password_verify($password, $user[0]['password'])) {
                return $user[0];
            }
        }
        return false;
    }

    public function register($name, $email, $password, $role)
    {
        $sql = parent::$connection->prepare("INSERT INTO `users`( `name`, `email`, `password`, `role`) VALUES (?, ?, ?, ?)");
        $password = password_hash($password, PASSWORD_DEFAULT);
        $sql->bind_param("ssss", $name, $email, $password, $role);

        return $sql->execute();
    }

    public function getUserById($id)
    {
        $sql = parent::$connection->prepare("SELECT * FROM users WHERE id = ?");
        $sql->bind_param("i", $id);
        // var_dump(parent::select($sql)[0]);
        return parent::select($sql)[0];
    }

    public function all()
    {
        $sql = parent::$connection->prepare("SELECT * FROM users WHERE `role` != 'admin' ");
        return parent::select($sql);
    }

    public function updateProfile($id, $name, $email, $image)
    {
        $sql = parent::$connection->prepare("UPDATE users SET name = ?, email = ?, image = ? WHERE id = ?");
        $sql->bind_param("sssi", $name, $email, $image, $id);
        return $sql->execute();
    }

    public function getUserByEmail($email)
    {
        $sql = parent::$connection->prepare("SELECT * FROM users WHERE email = ?");
        $sql->bind_param("s", $email);
        $result = parent::select($sql);
        if (count($result) > 0) {
            return $result[0];
        }
        return false;
    }

    public function updatePassword($email, $new_password)
    {
        $sql = parent::$connection->prepare("UPDATE users SET password = ? WHERE email = ?");
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $sql->bind_param("ss", $hashed_password, $email);
        return $sql->execute();
    }

    public function generateVerificationCode($email)
    {
        $code = sprintf("%06d", rand(0, 999999));
        $sql = parent::$connection->prepare("UPDATE users SET verification_code = ?, code_expire = DATE_ADD(NOW(), INTERVAL 15 MINUTE) WHERE email = ?");
        $sql->bind_param("ss", $code, $email);
        if ($sql->execute()) {
            return $code;
        }
        return false;
    }

    public function delete($id)
    {
        $sql = parent::$connection->prepare("DELETE FROM `users` WHERE `id`= ?");
        $sql->bind_param('i', $id);
        // 3 & 4
        return $sql->execute();
    }
}
