<?php
    session_start();

    $registerfailed = '';

    // Connect to the server and select database
        $db = new PDO("mysql:dbname=testingdadwork;host=localhost","root","");
        $db -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $sql = "SELECT count(*) FROM users WHERE username = :username";
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':username', $_POST['name']);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if(!empty($_POST['name'])) {
            if(!empty($_POST['password'])) {
                if($result == 0) {
                    if(!empty($_POST['division'])) {
                        if(!empty($_POST['group'])) {
                            // Get values passed from form
                            $username = $_POST['name'];
                            $password = $_POST['password'];
                            $division = $_POST['division'];
                            $group = $_POST['group'];

                            // To prevent injection
                            $username = stripcslashes($username);
                            $password = stripcslashes($password);
                            $division = stripcslashes($division);
                            $group = stripcslashes($group);
                            $username = htmlspecialchars($username);
                            $password = htmlspecialchars($password);
                            $division = htmlspecialchars($division);
                            $group = htmlspecialchars($group);

                            $password = password_hash($password, PASSWORD_DEFAULT);

                            // Add data to the database
                            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$username, $password]);

                            $stmt = $db->prepare("SELECT * FROM groups WHERE group_name = :groupname");
                            $stmt->bindParam(':groupname',$group);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $groupid = $row['group_id'];

                            $stmt = $db->prepare("SELECT * FROM divisions WHERE division_name = :divisionname");
                            $stmt->bindParam(':divisionname',$division);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $divisionid = $row['id'];

                            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username");
                            $stmt->bindParam(':username',$username);
                            $stmt->execute();
                            $row = $stmt->fetch(PDO::FETCH_ASSOC);
                            $userid = $row['user_id'];

                            $sql = "INSERT INTO user_division_map (user_id, division_id) VALUES (?, ?)";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$userid, $divisionid]);

                            $sql = "INSERT INTO user_group_map (user_id, group_id) VALUES (?, ?)";
                            $stmt = $db->prepare($sql);
                            $stmt->execute([$userid, $groupid]);

                            echo "0";
                        } else {
                            echo "6: Group missing";
                            exit();
                        }
                    } else {
                        echo "5: Division missing";
                        exit();
                    }
                } else {
                    echo "2: Name already exists";
                    exit();
                }
            } else {
                echo "4: Password missing";
                exit();
            }
            
        } else {
            echo "3: Username missing";
            exit();
        }
?>
