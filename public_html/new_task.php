<?php session_start();
date_default_timezone_set('UTC');
require_once 'connection.php';
$link =  new mysqli($host, $user, $password, $database) ;
if (mysqli_connect_errno()) { 
    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
    exit(); 
}
$s='';
if($_POST['method']=="new_task"){
        $title=htmlentities(mysqli_real_escape_string($link, $_POST['title']));
        $description=htmlentities(mysqli_real_escape_string($link, $_POST['description']));
        $end_date=htmlentities(mysqli_real_escape_string($link, $_POST['end_date']));
        $creation_date=date("Y-m-d");
        $update_date=date("Y-m-d");
        $priority=htmlentities(mysqli_real_escape_string($link, $_POST['priority']));
        $status=htmlentities(mysqli_real_escape_string($link, $_POST['status']));
        $id_user=$_SESSION['id_user'];
        $creator=$id_user;
        $responsible=htmlentities(mysqli_real_escape_string($link, $_POST['responsible']));
        $sql="INSERT INTO `tasks` VALUES(NULL,'$title','$description','$end_date','$creation_date','$update_date','$priority','$status','$creator','$responsible')";
	   $result = $link->prepare($sql);
        if ($result === FALSE) {
	        $s=$link->error;
	   }else{
	       $s= 'Вы успешно создали новую задачу';
        }
        $result->execute();
        $result->close();
        
}
$link->close();
echo $s;
?>