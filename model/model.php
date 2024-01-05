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
    public function TotalPages($num_perpage)
    {
        $sql = "SELECT count(subject_id) as total_pages from subjects";
        $statement = $this->db->prepare($sql);
        $statement->execute();
        $count_result = $statement->get_result();
        $count = $count_result->fetch_assoc();
        $total_pages = ceil($count['total_pages'] / $num_perpage);

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
        $query = "SELECT * from students LIMIT $num_perpage OFFSET $offset";
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
}
