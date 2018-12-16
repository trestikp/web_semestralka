<?php
include("db_set.php");

class users
{
    private $connection;
    private $uname, $passwd, $email, $role;

    public function __construct($uname){
        $this->load_usr($uname);
    }

    public function load_usr($uname){
        try{
            $this->connect();
            $sql = "SELECT login, password, email, role FROM uzivatel WHERE login = \"$uname\"";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $this->disconnect();
            if(count($result) < 1)
                return false;
            else if(count($result) > 1)
                return false;
            else {
                /*
                echo "<hr><pre>";
                print_r($result);
                echo "</pre><hr>";
                */
                $this->uname = $result[0]["login"];
                $this->passwd = $result[0]["password"];
                $this->email = $result[0]["email"];
                $this->role = $result[0]["role"];
                return true;
            }

        } catch(PDOException $e){
            echo "Error: " . $e->getMessage();
        }
    }

    public function get_role(){
        return $this->role;
    }

    public function is_user($uname){
        if($this->uname == $uname)
            return true;
        else
            return false;
    }

    public function login($uname,$passwd){
        if($this->is_user($uname)){
            if(password_verify($passwd, $this->passwd)){
                return true;
            }
        } else {
            return false;
        }
    }

    public function add_user($uname, $passwd, $email, $role){
        try{
            $temp_passwd = password_hash($passwd, PASSWORD_BCRYPT);
            $sql = "INSERT INTO uzivatel (login, password, email, role) VALUES (\"$uname\", \"$temp_passwd\", \"$email\", \"$role\")";
            $this->connect();
            $this->connection->exec($sql);

            $this->disconnect();
            echo "Added successfuly";
        } catch(PDOException $e){
            echo "Insert error: " . $e->getMessage();
        }
    }

    function connect(){
        try{
            $this->connection = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE_NAME."", DB_USER_LOGIN, DB_USER_PASSWORD);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e){
            echo "Connection failed: " . $e->getMessage();
            die();
        }
    }
    function disconnect(){
        $this->connection = null;
    }
}