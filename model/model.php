<?php

class Model
{
    private $db;

    //Database Connection
    public function __construct($host, $username, $password, $dbname)
    {
        $this->db = new mysqli($host, $username, $password, $dbname);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    //return the connection
    public function getDb()
    {
        return $this->db;
    }

    //Retrieve User
    public function SelectUser($usertype, $username)
    {
        $stmt = $this->db->prepare("SELECT * from accounts
        join $usertype on accounts.account_id = $usertype.account_id
        where accounts.username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Retrieve 5 Subjects
    public function SelectSubjects($num_perpage, $offset)
    {
        $query = "SELECT * from subjects LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Retrieve Subject
    public function SelectSubject($sub_id)
    {
        $query = "SELECT * from subjects where subject_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $sub_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Get All total Pages
    public function TotalPages($column, $num_perpage, $table, $section = "", $glvl = "")
    {
        if (empty($section) && empty($glvl)) {
            $sql = "SELECT count($column) as total_pages from $table";
            $statement = $this->db->prepare($sql);
            $statement->execute();
            $count_result = $statement->get_result();
            $count = $count_result->fetch_assoc();
            $total_pages = ceil($count['total_pages'] / $num_perpage);
        } else {
            $sql = "SELECT count($column) as total_pages from $table where section = ? and grade_level = ?";
            $statement = $this->db->prepare($sql);
            $statement->bind_param("ss", $section, $glvl);
            $statement->execute();
            $count_result = $statement->get_result();
            $count = $count_result->fetch_assoc();
            $total_pages = ceil($count['total_pages'] / $num_perpage);
        }
        return $total_pages;
    }

    //Select User Email
    public function SelectEmail($email)
    {
        $query = $this->db->prepare("SELECT email from accounts where email = ?;");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

    //Insert Token to the Token Table
    public function InsertToken($token, $expiration_date, $email)
    {
        $query = $this->db->prepare("INSERT into token(token, expiration_date, email) values(?,?,?);");
        $query->bind_param("sss", $token, $expiration_date, $email);

        try {
            if ($query->execute()) {
                return;
            } else {
                throw new mysqli_sql_exception("Inserting token failed");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() == "Inserting token failed") {
                return false;
            }
        }
    }

    //Select Token
    public function SelectToken()
    {
        $sql = "SELECT * from token where token = ?;";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("s", $_GET["token"]);
        $stmt->execute();
        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        return $data;
    }

    //Reset user password
    public function ResetPass($token, $new_pass)
    {
        $sql1 = "SELECT email from token where token = ?";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param("s", $token);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $row = $result1->fetch_assoc();
        $email = $row['email'];
        $password = password_hash($new_pass, PASSWORD_BCRYPT);

        $sql2 = "UPDATE accounts SET `password` = ? where email = ?";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("ss", $password, $email);

        $sql3 = "DELETE from token where token = ?;";
        $stmt3 = $this->db->prepare($sql3);
        $stmt3->bind_param("s", $token);

        try {
            if ($stmt3->execute() && $stmt2->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Error Query");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() == "Error Query") {
                return false;
            }
        }
    }

    //Add Subject
    public function AddSubject($code, $subject, $description)
    {

        $sql = "INSERT INTO subjects (code, `subject`, `description`) VALUES(?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sss", $code, $subject, $description);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Duplicate subject");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return false;
            }
        }
    }

    //Delete Subject
    public function DeleteSubject($id)
    {
        $sql = "DELETE FROM subjects where subject_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Deleting Subject Failed");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() == "Deleting Subject Failed") {
                return false;
            }
        }
    }

    //Edit subject
    public function EditSubject($code, $subject, $description, $sub_id)
    {
        $sql = "UPDATE subjects SET code = ?, `subject` = ?, `description` = ? where subject_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssi", $code, $subject, $description, $sub_id);

        try {
            if ($stmt->execute()) {
                return true;
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return false;
            }
        }
    }

    //Search subject
    public function SearchSubjects($subject)
    {
        $sub = "%" . $subject . "%";
        $query = "SELECT * from subjects WHERE `subject` LIKE ? LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("s", $sub);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Select 5 students
    public function SelectStudents($num_perpage, $offset)
    {
        $query = "SELECT * from students order by grade_level,l_name,f_name LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Add student
    public function AddStudent($username, $email, $fname, $lname, $number, $section, $g_level, $pic)
    {

        $default_password = password_hash("12345", PASSWORD_BCRYPT);
        $sql1 = "INSERT INTO accounts (username, email, `password`) VALUES(?,?,?)";
        $stmt1 = $this->db->prepare($sql1);
        $stmt1->bind_param("sss", $username, $email, $default_password);

        try {
            if ($stmt1->execute()) {
                $acc_id = $this->db->insert_id;
            } else {
                throw new mysqli_sql_exception("Duplicate Email");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 'duplicate email';
            }
        }

        $sql2 = "INSERT INTO students (f_name, l_name, contact_number, section, grade_level, profile_pic, account_id) 
        VALUES(?,?,?,?,?,?,?)";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("ssssssi", $fname, $lname, $number, $section, $g_level, $pic, $acc_id);

        try {
            if ($stmt2->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("Error adding student");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() === "Error adding student") {
                return 'error';
            }
        }
    }

    //Select Profile Pic
    public function SelectProfile($id)
    {
        $query = "SELECT profile_pic from students where account_id = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    //Delete Student
    public function Deletestudent($id)
    {

        $sql = "DELETE FROM accounts where account_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Deleting Student Failed");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() == "Deleting Student Failed") {
                return false;
            }
        }
    }

    //Get Student
    public function SelectStudent($acc_id)
    {
        $query = "SELECT * from students where account_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $acc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Get Account
    public function SelectAccount($acc_id)
    {
        $query = "SELECT * from accounts where account_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $acc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Edit Account
    public function EditAcc($username, $email, $password, $acc_id)
    {
        if (empty($password)) {
            $sql = "UPDATE accounts SET username = ?, `email` = ? where account_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssi", $username, $email, $acc_id);
        } else {
            $sql = "UPDATE accounts SET username = ?, `email` = ?, `password` = ? where account_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssi", $username, $email, $password, $acc_id);
        }

        try {
            if ($stmt->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("duplicate");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 'duplicate';
            }
        }
    }

    //Edit student
    public function EditStudent($fname, $lname, $number, $section, $g_level, $pic, $id)
    {
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $picture = $path . $pic['name'];

        if ($pic['size'] > 0) {
            $sql = "UPDATE students SET f_name = ?, l_name = ?, contact_number = ?, 
            section = ?, grade_level = ?, profile_pic = ? where account_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssssi", $fname, $lname, $number, $section, $g_level, $picture, $id);
        } else {
            $sql = "UPDATE students SET f_name = ?, l_name = ?, contact_number = ?, 
            section = ?, grade_level = ? where account_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssssi", $fname, $lname, $number, $section, $g_level, $id);
        }

        try {
            if ($stmt->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("fail");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getMessage() === "fail") {
                return 'fail';
            }
        }
    }

    //Search subject
    public function SearchStudents($student)
    {
        $student = "%" . $student . "%";
        $query = "SELECT * from students WHERE f_name LIKE ? or l_name LIKE ? LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $student, $student);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Select students based on the section
    public function GetStudents($num_perpage, $offset, $section, $glvl)
    {
        $query = "SELECT account_id,f_name,l_name from students where section = ? and grade_level= ? 
        order by grade_level,l_name,f_name LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $section, $glvl);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Retrieve all subjects
    public function GetSubjects()
    {
        $query = "SELECT subject_id, `subject` from subjects";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
