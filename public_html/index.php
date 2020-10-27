<?php session_start();unset($_SESSION['id_user']);?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

	<title>Приложение TODO list</title>
</head>
<body>
	<form action="index.php" method="POST">
		<input type="text" name="login" required placeholder="Логин">
		<input type="password" name="password" required placeholder="Пароль">
		<input type="submit" value="Войти">
	</form>
	<a href="/signup.php">Зарегистрироваться</a>

<?php
if(isset($_POST['login']) && isset($_POST['password'])){
    require_once 'connection.php'; 
    $link =  new mysqli($host, $user, $password, $database) ;
    if (mysqli_connect_errno()) { 
        printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
        exit(); 
    }
    $password=htmlentities(mysqli_real_escape_string($link, $_POST['password']));
    $login=htmlentities(mysqli_real_escape_string($link, $_POST['login']));
    $query = "SELECT * FROM `users` where login='$login'";
    $result = $link->query($query);
    $row = $result->fetch_object();
    if($row)
    {
 
        if(password_verify($password,$row->password)){
            $_SESSION['id_user']=$row->id;
            echo '<script>document.location.href="/tasks.php"</script>';
        }else{
            echo 'Неверный пароль';
        }
       
    }else{
        echo 'Пользователя с таким логином не существует';
    }
    $result->free();
	$link->close();
}
?>
</body>
</html>