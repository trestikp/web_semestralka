<?php

    include ("db_func.php");
    include ("db_set.php");
    include ("users.class.php");
    /*
        $login_error = "";

        if(isset($_POST["login"])){
            if(isset($_POST["password"]))
                $username = $_POST["username"];
            else $login_error = "Please enter username.";

            if(isset($_POST["password"]))
                $password = $_POST["password"];
            else $login_error = "Please enter password.";
        }
    */
    $jmeno = "alibaba";

    try{
        $conn = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_DATABASE_NAME."", DB_USER_LOGIN, DB_USER_PASSWORD);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $stmt = $conn->prepare("SELECT login FROM uzivatel WHERE login = \"$jmeno\"");
        $stmt->execute();
        $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $neco = $stmt->fetchAll();
        //var_dump($neco);
        var_dump(count($neco));
        if(count($neco) < 1){
            echo "user doesnt exist";
        } else if (count($neco) > 1)
            echo "somethings wrong";
        else
            print_r($neco);
    } catch(PDOException $e){
        echo "Connection failed: " . $e->getMessage();
    }


    // echo "$username a heslo $password";