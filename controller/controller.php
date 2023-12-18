<?php

require_once '../model/model.php';

class controller
{
    private $model;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->model = new Model($host, $username, $password, $dbname);
    }

    public function CleanData($conn, $data)
    {
        $data = stripslashes($data);
        $data = trim($data);
        $data = htmlspecialchars($data);
        $data = mysqli_real_escape_string($conn, $data);
        return $data;
    }

    public function redirect($conn, $location, $user_id, $usertype)
    {
        $_SESSION[$usertype . "_id"] = $user_id;
        $conn->close();
        header("location: ../view/" . $location);
        exit();
    }

    public function login($usertype, $username, $password)
    {
        $conn = $this->model->getDb();

        switch ($usertype) {
            case 'admins':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify($this->CleanData($conn, $password), $hashed_pass)) {
                    $this->redirect($conn, "admin.php", $user_id, $usertype);
                } else {
                    $conn->close();
                    return $mess_failed = "Incorrect Username or Password";
                }

                break;

            case 'students':

                $user =  $this->model->SelectUser($usertype, $username);
                $user_info = $user->fetch_assoc();
                $user_id = ($user->num_rows > 0) ? $user_info['admin_id'] : "";
                $username = ($user->num_rows > 0) ? $user_info['username'] : "";
                $hashed_pass = ($user->num_rows > 0) ? $user_info['password'] : "";

                if ($user->num_rows > 0 && password_verify($this->CleanData($conn, $password), $hashed_pass)) {

                    $this->redirect($conn, "student.php", $user_id, $usertype);
                } else {
                    $conn->close();
                    return $mess_failed = "Incorrect Username or Password";
                }

                break;
        }
    }
}
