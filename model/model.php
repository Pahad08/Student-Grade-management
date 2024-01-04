<?php

class Model
{
    private $db;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->db = new mysqli($host, $username, $password, $dbname);

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getDb()
    {
        return $this->db;
    }

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

    public function SelectSubjects($num_perpage, $offset)
    {
        $query = "SELECT * from subjects LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    public function SelectSubject($sub_id)
    {
        $query = "SELECT * from subjects where subject_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $sub_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }


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

    public function SelectEmail($email)
    {
        $query = $this->db->prepare("SELECT email from accounts where email = ?;");
        $query->bind_param("s", $email);
        $query->execute();
        $result = $query->get_result();

        return $result->num_rows;
    }

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

    public function SelectStudents($num_perpage, $offset)
    {
        $query = "SELECT * from students LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
