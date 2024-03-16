<?php
    include("database.php");

    $error = ''; 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
        $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);

        if(empty($username)){
            $error = "Введите имя пользователя";
        }elseif(empty($password)){
            $error = "Введите пароль пользователя";
        }else{
            // Проверяем, существует ли пользователь в базе данных
            $check_sql = "SELECT * FROM users WHERE user = '$username'";
            $result = mysqli_query($conn, $check_sql);
            if(mysqli_num_rows($result) > 0){
                $error = "Такой пользователь уже есть";
            }else{
                $hash = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (user, password) VALUES ('$username', '$hash')";
                if(mysqli_query($conn, $sql)){
                    $error = "Теперь ты зареган.";
                }else{
                    $error = "Ошибка при регистрации: " . mysqli_error($conn);
                }
            }
        }
    }

    mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="wrapper">
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="inputtext">
            <h2 id="headtext">Приветствую вас на моем сайте!</h2>
            <br>
            <input id="inputline-one" type="text" name="username" placeholder="Имя пользователя"><br>
            <br>
            <input type="password" name="password" placeholder="Пароль"><br>
            <button type="submit" name="submit" value="register">&#xf0da;</button>
            <?php if (!empty($error)): ?>
                <div class="error"><?php echo $error; ?></div>
            <?php endif; ?>
        </form>
    </div>
</body>
</html>