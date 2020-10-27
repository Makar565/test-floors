<?php session_start();
date_default_timezone_set('UTC');
require_once 'connection.php';
$link =  new mysqli($host, $user, $password, $database) ;
if (mysqli_connect_errno()) { 
    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
    exit(); 
}
$id=$_SESSION['id_user'];
$sort=$_POST['sort'];
$date=date("Y-m-d");
$w = date("w");
if($w==0)$w=7;
$day1=date("Y-m-d",strtotime("-".($w-1)." day"));
$day7=date("Y-m-d",strtotime("+".(7-$w)." day"));
if($sort=='update_date') $query ='SELECT * FROM `tasks` ORDER BY `update_date` DESC';
else if($sort=='На сегодня') $query ="SELECT * FROM `tasks` where responsible='$id' AND end_date='$date'";
else if($sort=='На неделю') $query ="SELECT * FROM `tasks` where responsible='$id' AND end_date>='$day1' AND end_date<='$day7'";
else if($sort=='На будущее') $query ="SELECT * FROM `tasks` where responsible='$id' AND end_date>'$day7'";
else $query ="SELECT * FROM `tasks` where responsible='$sort'";
$result = $link->query($query);
$list='Нет задач';    
if($result->num_rows)
{
    $list='';
    while($row = $result->fetch_object()) {
	      $color="gray";
	      if($row->status=="выполнена"){
	          $color="green";
	      }else if($row->end_date < date("Y-m-d")){
	          $color="red";
	      }
	     
	      $list.= '<li style="background:'.$color.'" class="element" data-tasksid='.$row->id.'>';
	      
	       $list.='<h2>'.$row->title.'</h2>';
	       $list.='<h4>Приоритет: '.$row->priority.'</h4>';
	       $list.='<p>Дата окончания: '.$row->end_date.'</p>';
	       $list.='<p>Отвественный: ';
	          $query = "SELECT * FROM `users` where id='$row->responsible'";
               $result1 = $link->query($query);
               if($result1->num_rows){
                    $ro = $result1->fetch_object();
                    $list.= $ro->last_name." ".$ro->first_name." ".$ro->middle_name;
               }
	      $list.= '</p>';
	      $list.='<p>Статус: '.$row->status.'</p> </li>';
	  }
}
echo $list;
?>