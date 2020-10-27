<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

	<title>Приложение TODO list</title>
</head>
<body>
	<form action="signup.php" method="POST">
		<input type="text" name="login" required placeholder="Логин">
		<input type="password" name="password" required placeholder="Пароль">
		<input type="text" name="first_name" required placeholder="Имя">
		<input type="text" name="last_name" required placeholder="Фамилия">
		<input type="text" name="middle_name" required placeholder="Отчество">
		<!--<input type="text" name="director" required placeholder="Руководитель">-->
		<select name="director" >
	              <option disabled selected>Выберите руководителя</option>
                  <option value="None">У меня нет руководителя</option>
                  <?php
                  require_once 'connection.php';
                  $link =  new mysqli($host, $user, $password, $database) ;
                  if (mysqli_connect_errno()) { 
                    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
                    exit();
                  }
                  $query = "SELECT * FROM `users`";
                  $result = $link->query($query);
                  if($result){
                      while($row = $result->fetch_object()){
                          echo "<option value=".$row->id.">".$row->last_name." ".$row->first_name." ".$row->middle_name."</option>";
                      }
                      
                      $result->close();
                  }
                  $link->close();
            
                  ?>
        </select>
		<input type="submit" value="Зарегистрироваться">
	</form>
	<a href="/index.php">Войти</a>

<?php
if(isset($_POST['login']) && isset($_POST['password']) && isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['middle_name']) && isset($_POST['director'])){
    require_once 'connection.php'; 
    $link =  new mysqli($host, $user, $password, $database) ;
    if (mysqli_connect_errno()) { 
    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
    exit(); 
    }
    $password=crypt(htmlentities(mysqli_real_escape_string($link, $_POST['password'])));
    $login=htmlentities(mysqli_real_escape_string($link, $_POST['login']));
    $first_name=htmlentities(mysqli_real_escape_string($link, $_POST['first_name']));
    $last_name=htmlentities(mysqli_real_escape_string($link, $_POST['last_name']));
    $middle_name=htmlentities(mysqli_real_escape_string($link, $_POST['middle_name']));
    $director=htmlentities(mysqli_real_escape_string($link, $_POST['director']));
    $query = "SELECT * FROM `users` where login='$login'";
    $result = $link->query($query);
    $row = $result->fetch_object();
    if($row)
    {
        echo 'Пользователь с таким логином уже зарегистрирован<br>';
        echo '<a href="/index.php">Войти?</a>';
            
    }else{
            $result->free();
            $sql="INSERT INTO `users` VALUES(NULL,'$login','$password','$first_name','$last_name','$middle_name','$director')";
	        $result = $link->prepare($sql);
            if ($result === FALSE) {
	    
		        die($link->error);
	        }else{
	             echo 'Вы успешно зарегистрировались';
	             echo '<script>document.location.href="/index.php"</script>';
        	}
	        $result->execute();
            $result->close();
        }
    
	$link->close();
}
?>
</body>
</html>