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

    public function SelectSubject($num_perpage, $offset)
    {
        $query = "SELECT * from subjects LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
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
        $query->execute();
    }
}
