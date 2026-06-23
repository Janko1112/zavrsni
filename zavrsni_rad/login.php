<?php
session_start();
include "db.php";

if(isset($_SESSION['username'])) {
    if($_SESSION['role'] == 'admin') {
        header("Location: admin.php");
    } else {
        header("Location: index.php");
    }
    exit();
}

$poruka = "";
if(isset($_GET['info']) && $_GET['info'] == 'auth_required') {
    $poruka = "Molimo prijavite se kako biste mogli dodavati artikle u košaricu!";
}

if(isset($_POST['login'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = mysqli_query($conn, $sql);

    if(mysqli_num_rows($result) > 0){
        $user = mysqli_fetch_assoc($result);

        if(md5($password) === $user['password']) {

            $_SESSION['user_id'] = $user['id']; 
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];

            if($user['role'] == 'admin'){
                header("Location: admin.php");
                exit();
            } else {
                echo "
                <script>
                window.location='index.php';
                </script>
                ";
                exit();
            }
        } else {
            echo "<script>alert('Pogrešni podaci! (Lozinka se ne podudara)');</script>";
        }
    } else {
        echo "<script>alert('Pogrešni podaci! (Korisnik nije pronađen)');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="hr">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<section class="container">
    <div class="contact-box">
        <h2 class="section-title">Prijava</h2>
        <?php if(!empty($poruka)): ?>
            <p class="message"><?php echo $poruka; ?></p>
        <?php endif; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <button class="btn" name="login">Login</button>
        </form>
    </div>
</section>
</body>
</html>
