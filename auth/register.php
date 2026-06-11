<?php
include '../dblink.php';

$message = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $school = trim($_POST['school']);
    $language = trim($_POST['language']);

    $check = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $check->bind_param("s", $email);
    $check->execute();
    $result = $check->get_result();

    if ($result->num_rows > 0) {

        $message = "Email already exists.";

    } else {

        $stmt = $conn->prepare("
            INSERT INTO users
            (name, email, password, school, language)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssss",
            $name,
            $email,
            $password,
            $school,
            $language
        );

        if ($stmt->execute()) {

            header("Location: login.php");
            exit();

        } else {

            $message = "Registration failed.";
        }

        $stmt->close();
    }

    $check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Registration Form</title>

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
    width:700px;
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

/* TWO COLUMNS */
.form-row{
    display:flex;
    gap:20px;
    margin-bottom:25px;
}

.input-group{
    flex:1;
}

.input-group label{
    display:block;
    margin-bottom:8px;
    color:#666;
    font-size:15px;
}

.input-group input,
.input-group select{
    width:100%;
    border:none;
    border-bottom:2px solid #58e0d2;
    outline:none;
    padding:10px 0;
    font-size:16px;
    background:transparent;
}

.input-group input:focus,
.input-group select:focus{
    border-bottom:2px solid #0d84ff;
}

.checkbox{
    display:flex;
    align-items:flex-start;
    gap:10px;
    margin:20px 0 30px;
    color:#888;
    font-size:12px;
    line-height:1.5;
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

.login-link{
    text-align:center;
    margin-top:20px;
    color:#666;
}

.login-link a{
    color:#0d84ff;
    text-decoration:none;
    font-weight:bold;
}

.message{
    text-align:center;
    color:red;
    margin-bottom:20px;
}

/* MOBILE */
@media(max-width:768px){

    .container{
        width:95%;
        padding:25px;
    }

    .form-row{
        flex-direction:column;
        gap:25px;
    }
}
</style>

</head>
<body>

<div class="container">

    <h1 class="title">Registration Form</h1>

    <?php if(!empty($message)): ?>
        <p class="message"><?php echo $message; ?></p>
    <?php endif; ?>

<form method="POST">

    <div class="form-row">

        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" required>
        </div>

        <div class="input-group">
            <label>Email Address</label>
            <input type="email" name="email" required>
        </div>

    </div>

    <div class="form-row">

        <div class="input-group">
            <label>School</label>
            <input type="text" name="school">
        </div>

        <div class="input-group">
            <label>Language</label>
            <select name="language">
                <option value="">Select Language</option>
                <option value="English">English</option>
                <option value="Myanmar">Myanmar</option>
            </select>
        </div>

    </div>

    <div class="input-group">
        <label>Password</label>
        <input type="password" name="password" required>
    </div>

    <div class="checkbox">
        <input type="checkbox" required>
        <span>
            I agree to the Terms & Conditions and Privacy Policy
        </span>
    </div>

    <button type="submit" name="register" class="btn">
        CREATE ACCOUNT
    </button>

</form>

    <div class="login-link">
        Already have an account?
        <a href="login.php">Sign in</a>
    </div>

</div>

</body>
</html>