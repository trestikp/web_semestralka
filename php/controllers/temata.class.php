<?php
include ("../inc/db_func.php");

class temata {
    private $connection;

    public function get_themes(){
        try{
            $this->connect();
            $sql = "SELECT tema FROM prispevek";
            $stmt = $this->connection->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $this->disconnect();

            $themes = array();

            foreach ($result as $item) {
                if($themes == null){
                    $themes[$item["tema"]] = $item["tema"];
                }
                foreach ($themes as $it){
                    if ($item != $it) {
                        $themes[$item["tema"]] = $item["tema"];
                    }
                }
            }

            return $themes;
        } catch (PDOException $e){
            echo $e->getMessage();
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