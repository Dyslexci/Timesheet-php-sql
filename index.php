<?php
    session_start();

    function generate_token() {
        // Check if a token is present for the current session
        if(!isset($_SESSION["csrf_token"])) {
            // No token present, generate a new one
            $token = random_bytes(64);
            $_SESSION["csrf_token"] = $token;
        } else {
            // Reuse the token
            $token = $_SESSION["csrf_token"];
        }
        return $token;
    }

    $loginfailed = '';
    
    // Connect to the server and select database
    $db = new PDO("mysql:dbname=testingdadwork;host=localhost","root","");

    if(isset($_POST['user'], $_POST['pass'])) {
        // Get values passed from form
        $username = $_POST['user'];
        $password = $_POST['pass'];
        $dbr = 'users';

        $username = stripcslashes($username);
        $password = stripcslashes($password);
        $username = htmlspecialchars($username);
        $password = htmlspecialchars($password);
        
        if(strcmp($username, 'admin') == 0) {
            $stmt = $db->prepare("SELECT * FROM admins WHERE username = :user");
        } else {
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :user");
        }
        
        $stmt->bindParam(':user',$username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if(password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;

            if(strcmp($username, 'admin') == 0) {
                header('location: admin.php');
            } else {
                header('location: account.php');
            }
            exit;
        }
    } 

    if(!empty($_POST['login'])) {
        $loginfailed = 'Login Failed';
    }
?>


<html lang='en'>

<head>
    <title>Login</title>
    <meta charset='utf-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1'>
    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css'>
    <link rel="stylesheet" href="css/cray.css">
    <style>
        html,
        body {
            height: 80%;
        }
        
        .centerContainer {
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
    <script src='https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js'></script>
    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js'></script>
</head>

<body>

    <div class="centerContainer container">
        <div class="row">
            <div class="box">
                <div class="login-form">
                    <form action="" method="post" name="login">
                        <h1 class="text-center">Login</h1>
                        <div class="form-group">
                            <input type="text" class="inp rounded" placeholder="Username" id="user" name="user" title="Enter your username here" />
                            <i class="mdi mdi-account"></i>
                        </div>
                        <div class="form-group log-status">
                            <input type="password" class="inp rounded" placeholder="Password" id="pass" name="pass" title="Enter your password here" />
                            <i class="mdi mdi-lock"></i>
                        </div>
                        <?php
                            if(!empty($_POST['login'])) {
                                ?>
                                <div class="alert alert-bad"><?php echo $loginfailed; ?></div>
                                <?php
                            } else {
                                ?>
                                <br>
                                <?php
                            }
                        ?>
                        <a class="link" href="register.php">Register</a>
                        <input type="hidden" name="csrf_token" value="<?php echo generate_token();?>" />
                        <button type="submit" class="btn btn-block" id="login" name="login" value="Login" title="Login to your account">Login</button>
                    </form>
                </div>
            </div>

        </div>

    </div>

    </div>

</body>

</html>