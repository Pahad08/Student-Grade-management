<?php

class Model
{
    private $db;

    public function __construct($host, $username, $password, $dbname)
    {
        $this->db = new mysqli("localhost", "root", "", "school");

        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    function SelectUser($usertype, $username)
    {
        $stmt = $this->db->prepare("SELECT * from accounts
        join $usertype on accounts.account_id = $usertype.account_id
        where accounts.username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    function getDb()
    {
        return $this->db;
    }
}
