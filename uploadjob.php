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

        // Get values passed from form
        $username = $_POST['username'];
        $client = $_POST['client'];
        $jobNumber = $_POST['jobNumber'];
        $jobReference = $_POST['jobReference'];
        $coworkerID = $_POST['coworkerID'];
        $vanReg = $_POST['vanReg'];
        $date = $_POST['dayDate'];
        $startTime = $_POST['startTime'];
        $endTime = $_POST['endTime'];
        $totalHours = $_POST['totalHours'];

        // To prevent injection
        $username = stripcslashes($username);
        $client = stripcslashes($client);
        $jobNumber = stripcslashes($jobNumber);
        $jobReference = stripcslashes($jobReference);
        $coworkerID = stripcslashes($coworkerID);
        $vanReg = stripcslashes($vanReg);
        $date = stripcslashes($date);
        $startTime = stripcslashes($startTime);
        $endTime = stripcslashes($endTime);
        $totalHours = stripcslashes($totalHours);
        $username = htmlspecialchars($username);
        $client = htmlspecialchars($client);
        $jobNumber = htmlspecialchars($jobNumber);
        $jobReference = htmlspecialchars($jobReference);
        $coworkerID = htmlspecialchars($coworkerID);
        $vanReg = htmlspecialchars($vanReg);
        $date = htmlspecialchars($date);
        $startTime = htmlspecialchars($startTime);
        $endTime = htmlspecialchars($endTime);
        $totalHours = htmlspecialchars($totalHours);

        //$date = strtotime($date);

        // Add data to the database
        $sql = "INSERT INTO timesheet (username, client, job_number, job_reference, coworker_id, van_reg, day_date, start_time, end_time, total_hours) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $db->prepare($sql);
        $stmt->execute([$username, $client, $jobNumber, $jobReference, $coworkerID, $vanReg, $date, $startTime, $endTime, $totalHours]);

        echo "0";
?>
