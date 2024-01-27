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
        $stmt = $this->db->prepare("SELECT * from $usertype where username = ?");
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
        $query = $this->db->prepare("SELECT email FROM students WHERE email = ?
                UNION
                SELECT email FROM teachers WHERE email = ?
                UNION
                SELECT email FROM admins WHERE email = ?;");
        $query->bind_param("sss", $email, $email, $email);
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
        $password = password_hash($new_pass, PASSWORD_DEFAULT);

        $query = "SELECT 'admins' AS user_type
        FROM admins
        WHERE email = ?
        UNION
        SELECT 'teachers' AS user_type
        FROM teachers
        WHERE email = ?
        UNION
        SELECT 'students' AS user_type
        FROM students
        WHERE email = ?;";
        $statement = $this->db->prepare($query);
        $statement->bind_param("sss", $email, $email, $email);
        $statement->execute();
        $res = $statement->get_result();
        $row = $res->fetch_assoc();
        $table_name = $row['user_type'];

        $sql2 = "UPDATE $table_name SET `password` = ? where email = ?";
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
    public function AddStudent($username, $email, $fname, $lname, $gender, $number, $section, $g_level, $pic)
    {

        $default_password = password_hash("12345", PASSWORD_DEFAULT);

        $sql2 = "INSERT INTO students (f_name, l_name, gender, contact_number, section, grade_level, profile_pic,username,`password`,email) 
        VALUES(?,?,?,?,?,?,?,?,?,?)";
        $stmt2 = $this->db->prepare($sql2);
        $stmt2->bind_param("ssssssssss", $fname, $lname, $gender, $number, $section, $g_level, $pic, $username, $default_password, $email);

        try {
            if ($stmt2->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("Error adding student");
            }
        } catch (mysqli_sql_exception $e) {

            if ($e->getCode() == 1062) {
                return 'duplicate';
            } else {
                return 'error';
            }
        }
    }

    //Select Profile Pic
    public function SelectProfile($id, $table, $column)
    {
        $query = "SELECT profile_pic from $table where $column = ?";
        $statement = $this->db->prepare($query);
        $statement->bind_param("i", $id);
        $statement->execute();
        $result = $statement->get_result();
        return $result;
    }

    //Delete Account
    public function DeleteUser($id, $table, $column)
    {

        $sql = "DELETE FROM $table where $column = ?";
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
        $query = "SELECT * from students where student_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $acc_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Edit student
    public function EditStudent($fname, $lname, $gender, $number, $section, $g_level, $pic, $id, $username, $email)
    {
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $picture = $path . $pic['name'];

        if ($pic['size'] > 0) {
            $sql = "UPDATE students SET f_name = ?, l_name = ?,gender=?, contact_number = ?, 
            section = ?, grade_level = ?, profile_pic = ?, username = ? , email = ?  where student_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssssssssi", $fname, $lname, $gender, $number, $section, $g_level, $picture, $username, $email, $id);
        } else {
            $sql = "UPDATE students SET f_name = ?, l_name = ?,gender=?, contact_number = ?, 
            section = ?, grade_level = ?, username = ? , email = ? where student_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssssssi", $fname, $lname, $gender, $number, $section, $g_level, $username, $email, $id);
        }

        try {
            if ($stmt->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("fail");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 'duplicate';
            } else {
                return "error";
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
        $query = "SELECT student_id,f_name,l_name,student_id from students where section = ? and grade_level= ? 
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

    //Retrieve grades
    public function GetGrades($id)
    {
        $query = "SELECT grades.grade_id, subjects.subject, grades.grade
        from grades 
        join subjects on grades.subject_id = subjects.subject_id
        where grades.student_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Retrieve grades
    public function AddGrade($student_id, $grade, $subject_id)
    {
        $sql = "INSERT INTO grades (grade, student_id, subject_id) VALUES(?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("iii", $grade, $student_id, $subject_id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Error");
            }
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    //Delete Grade
    public function Deletegrade($subject_id)
    {
        $sql = "DELETE FROM grades where grade_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("i", $subject_id);

        try {
            if ($stmt->execute()) {
                return true;
            } else {
                throw new mysqli_sql_exception("Fail");
            }
        } catch (mysqli_sql_exception $e) {
            return false;
        }
    }

    //Add teacher
    public function AddTeacher($username, $email, $fname, $lname, $gender, $pic)
    {

        $default_password = password_hash("12345", PASSWORD_BCRYPT);
        $sql = "INSERT INTO teachers(f_name, l_name, gender, profile_pic, username,email,`password`) 
        VALUES(?,?,?,?,?,?,?)";
        $stmt = $this->db->prepare($sql);
        $stmt->bind_param("sssssss", $fname, $lname, $gender, $pic, $username, $email, $default_password);

        try {
            if ($stmt->execute()) {
                return 'success';
            } else {
                throw new mysqli_sql_exception("Error adding student");
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return 'duplicate';
            } else {
                return 'error';
            }
        }
    }

    //Select 5 teachers
    public function SelectTeachers($num_perpage, $offset)
    {
        $query = "SELECT * from teachers LIMIT $num_perpage OFFSET $offset";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Get teacher
    public function SelectTeacher($teacher_id)
    {
        $query = "SELECT * from teachers where teacher_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("i", $teacher_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Edit Teacher
    public function EditTeacher($fname, $lname, $gender, $pic, $username, $email, $id)
    {
        $path = ".." . DIRECTORY_SEPARATOR . 'profile_pics' . DIRECTORY_SEPARATOR;
        $picture = $path . $pic['name'];

        if ($pic['size'] > 0) {
            $sql = "UPDATE teachers SET f_name = ?, l_name = ?,gender=?, profile_pic = ?, username = ?, email = ? where teacher_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("ssssssi", $fname, $lname, $gender, $picture, $username, $email, $id);
        } else {
            $sql = "UPDATE teachers SET f_name = ?, l_name = ?, gender = ?, username = ?, email = ? where teacher_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->bind_param("sssssi", $fname, $lname, $gender, $username, $email, $id);
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

    //search teacher
    public function SearchTeacher($teacher)
    {
        $teacher = "%" . $teacher . "%";
        $query = "SELECT * from teachers WHERE f_name LIKE ? or l_name LIKE ? LIMIT 5";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param("ss", $teacher, $teacher);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //teachers count
    public function teacherscount()
    {
        $query = "SELECT COUNT(*) as total from teachers";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //students count
    public function studentscount()
    {
        $query = "SELECT COUNT(*) as total from students";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //subjects count
    public function subjectscount()
    {
        $query = "SELECT COUNT(*) as total from subjects";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //total student based on gender
    public function TotalStudents()
    {
        $query = "SELECT COUNT(*) as total,gender from students GROUP BY gender;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //total teacher based on gender
    public function TotalTeachers()
    {
        $query = "SELECT COUNT(*) as total,gender from teachers GROUP BY gender;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }

    //Average grade every year level
    public function AverageGrade()
    {
        $query = "SELECT AVG(grades.grade) as average, students.grade_level
        from grades
        join students on grades.student_id = students.student_id
        GROUP by students.grade_level
        ORDER BY
    CASE students.grade_level
        WHEN 'Grade 7' THEN 1
        WHEN 'Grade 8' THEN 2
        WHEN 'Grade 9' THEN 3
        WHEN 'Grade 10' THEN 4
        WHEN 'Grade 11' THEN 5
        WHEN 'Grade 12' THEN 6
        ELSE 7
    END;;";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result;
    }
}
