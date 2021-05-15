<?php
    session_start();

    $loginfailed = '';
    
    // Connect to the server and select database
    $db = new PDO("mysql:dbname=u_190232957_test;host=localhost","u-190232957","Sf2dk9N2Jt1IDbQ");

    if(isset($_POST['name'], $_POST['password'])) {
        // Get values passed from form
        $username = $_POST['name'];
        $password = $_POST['password'];

        $username = stripcslashes($username);
        $password = stripcslashes($password);
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        
        $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
        
        $stmt->bindParam(':username',$username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if(!$row) {
            echo "1";
            exit();
        }

        if(password_verify($password, $row['password'])) {
            $userid = $row['user_id'];

            $stmt = $db->prepare("SELECT * FROM user_group_map WHERE user_id = :userid");
            $stmt->bindParam(':userid',$userid);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            $usergroupid = $row['group_id'];

            $stmt = $db->prepare("SELECT * FROM groups WHERE group_id = :groupid");
            $stmt->bindParam(':groupid',$usergroupid);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "0";
            echo "\t";
            echo $username;
            echo "\t";
            echo $row['group_name'];
        } else {
            echo "3";
        }
    } else {
        echo "2";
    }
?>