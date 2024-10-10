<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <link rel="stylesheet" href="style/style.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

</head>
<body>
<div class="table-responsive">
    <div class="container">
        <form action="controller/login.php" method="POST" class="login-username">
        <p align="center" class="login-text" style="font-size: 2rem; font-weight: 800; margin-bottom: 10px; color: white;">LOGIN</p>
        <div class="input-group">
            <input type="text" placeholder="username" name="username" required><!-- Bagin id tambahin buat interaksi js-->
        </div>
        <div class="input-group">
            <input type="password" placeholder="password" name="password" required>
        </div>
        <div class="input-group">

            <button class="btn" name="submit">Login</button>
        </div>
        <p align= "center" class="login-register-text"> Belum Punya Akun? <a href=register_view.php > Buat Akun</a></p>
        </form>
    </div>
</div>
</body>
</html>