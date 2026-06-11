<?php

session_start();
include '../dblink.php';

/*
|--------------------------------------------------------------------------
| If already logged in → go dashboard
|--------------------------------------------------------------------------
*/

if (isset($_SESSION['user_id'])) {
    header("Location: ../pages/html/dashboard.php");
    exit();
}

$message = "";

if (isset($_POST['login'])) {

    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $conn->prepare(
        "SELECT id, name, email, password FROM users WHERE email = ?"
    );

    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];   //
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];

            header("Location: ../pages/html/dashboard.php");
            exit();

        } else {
            $message = "Invalid password.";
        }

    } else {
        $message = "Email not found.";
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Login</title>

<style>
*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Arial, Helvetica, sans-serif;
}

body{
    background:#f4f4f4;
    display:flex;
    justify-content:center;
    align-items:center;
    min-height:100vh;
}

.container{
    width:450px;
    background:#fff;
    padding:40px;
    border-radius:10px;
    box-shadow:0 5px 20px rgba(0,0,0,.1);
}

.title{
    text-align:center;
    font-size:36px;
    font-weight:bold;
    margin-bottom:35px;
    background:linear-gradient(90deg,#0d84ff,#1dd8c0);
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
}

.input-group{
    margin-bottom:25px;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    color:#666;
    font-size:15px;
}

.input-group input{
    width:100%;
    border:none;
    border-bottom:2px solid #58e0d2;
    outline:none;
    padding:10px 0;
    font-size:16px;
    background:transparent;
}

.input-group input:focus{
    border-bottom:2px solid #0d84ff;
}

.btn{
    width:100%;
    border:none;
    padding:16px;
    cursor:pointer;
    color:#fff;
    font-size:18px;
    font-weight:bold;
    letter-spacing:2px;
    border-radius:5px;
    background:linear-gradient(90deg,#0d84ff,#1dd8c0);
    transition:.3s;
}

.btn:hover{
    opacity:.9;
}

.message{
    text-align:center;
    color:red;
    margin-bottom:20px;
}

.register-link{
    text-align:center;
    margin-top:20px;
    color:#666;
}

.register-link a{
    color:#0d84ff;
    text-decoration:none;
    font-weight:bold;
}

@media(max-width:768px){
    .container{
        width:95%;
        padding:25px;
    }
}
</style>
</head>

<body>

<div class="container">

    <h1 class="title">Login</h1>

    <?php if(!empty($message)): ?>
        <p class="message">
            <?php echo $message; ?>
        </p>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <button type="submit" name="login" class="btn">
            LOGIN
        </button>

    </form>

    <div class="register-link">
        Don't have an account?
        <a href="register.php">Create Account</a>
    </div>

</div>

</body>
</html>