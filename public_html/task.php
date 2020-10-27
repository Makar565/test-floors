<?php session_start();
require_once 'connection.php';
$link =  new mysqli($host, $user, $password, $database) ;
if (mysqli_connect_errno()) { 
    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
    exit();
}
$id_task=$_POST['id'];
$id_user=$_SESSION['id_user'];
$result='';
$mas_priority=['высокий','средний','низкий'];
$mas_status=['к выполнению','выполняется','выполнена','отменена'];
if(isset($id_task)){
    $query = "SELECT * FROM `tasks` where id='$id_task'";
    $result = $link->query($query);
    if($result){
        $row = $result->fetch_object();
        if( $row->creator == $id_user){
            $return='<form action="tasks.php" method="POST">
	            <input type="text" name="title" required placeholder="Заголовок" value="'.$row->title.'">
	            <textarea name="description" required value="'.$row->description.'">'.$row->description.'</textarea>
	            <input type="date" name="end_date"  required placeholder="Дата окончания" value="'.$row->end_date.'" >
	           <select required name="priority">
	              <option disabled>Выберите приоритет</option>';
	             for($i=0;$i<count($mas_priority);$i++){
	                 if($mas_priority[$i]==$row->priority)
	                 $return.='<option value="'.$mas_priority[$i].'" selected>'.$mas_priority[$i].'</option>';
	                 else $return.='<option value="'.$mas_priority[$i].'" >'.$mas_priority[$i].'</option>';
	             }
                 
            $return.='</select>
                <select required name="status">
	              <option disabled>Выберите статус</option>';
                for($i=0;$i<count($mas_status);$i++){
	                 if($mas_status[$i]==$row->status)
	                 $return.='<option value="'.$mas_status[$i].'" selected>'.$mas_status[$i].'</option>';
	                 else $return.='<option value="'.$mas_status[$i].'" >'.$mas_status[$i].'</option>';
	             }   
            $return.='</select>
	            <select name="responsible" required >';
	       $query = "SELECT * FROM `users` where director='$id_user'";
           $result1 = $link->query($query);
           
           if($result1->num_rows){
               $return.='<option disabled>Выберите отвественного</option>';
               while($row1 = $result1->fetch_object()){
                   if($row1->id==$row->responsible) $return.='<option selected value='.$row1->id.'>'.$row1->last_name." ".$row1->first_name." ".$row1->middle_name.'</option>';
                   else $return.='<option value='.$row1->id.'>'.$row1->last_name." ".$row1->first_name." ".$row1->middle_name.'</option>';
               }
           }else{
               $return.='<option disabled>У вас нет подчиненых</option>';
           }
	       $return.='</select>
	       <input type="hidden" name="method" value="'.$id_task.'">
	            <input type="submit" id="update-task-button" value="Сохранить изменения">
	        </form>
	        <div id="error"></div>';
        }else if($id_user == $row->responsible){
           $return='<form action="tasks.php" method="POST">
	            <input type="text" name="title" readonly placeholder="Заголовок" value="'.$row->title.'">
	            <textarea name="description" readonly value="'.$row->description.'">'.$row->description.'</textarea>
	            <input type="date" name="end_date" readonly placeholder="Дата окончания" value="'.$row->end_date.'">
	            <input type="text" name="priority" readonly placeholder="Приоритет" value="'.$row->priority.'">
                <select  name="status">
                 <option disabled >Выберите статус</option>';
               
                for($i=0;$i<count($mas_status);$i++){
	                 if($mas_status[$i]==$row->status)
	                 $return.='<option value="'.$mas_status[$i].'" selected>'.$mas_status[$i].'</option>';
	                 else $return.='<option value="'.$mas_status[$i].'" >'.$mas_status[$i].'</option>';
	             }   
            $return.='</select>
            <select name="responsible" >';
	       $query = "SELECT * FROM `users` where id='$row->responsible'";
           $result1 = $link->query($query);
           if($result1->num_rows){
               while($row1 = $result1->fetch_object()){
                   $return.='<option selected value='.$row1->id.'>'.$row1->last_name." ".$row1->first_name." ".$row1->middle_name.'</option>';
               }
           }
	       $return.='</select>
	           
	       <input type="hidden" name="method" value="'.$id_task.'">
	            <input type="submit" id="update-task-button" value="Сохранить изменения">
	        </form>
	         <div id="error"></div>';
        }else{
             $return='<form action="tasks.php" method="POST">
	            <input type="text" name="title" readonly placeholder="Заголовок" value="'.$row->title.'">
	            <textarea name="description" readonly value="'.$row->description.'">'.$row->description.'</textarea>
	            <input type="date" name="end_date" readonly placeholder="Дата окончания" value="'.$row->end_date.'">
	            <input type="text" name="priority" readonly placeholder="Приоритет" value="'.$row->priority.'">
                <select  name="status">';
               
	                 $return.='<option value="'.$row->status.'" selected>'.$row->status.'</option>';

            $return.='</select>
            <select name="responsible" >';
	       $query = "SELECT * FROM `users` where id='$row->responsible'";
           $result1 = $link->query($query);
           if($result1->num_rows){
               while($row1 = $result1->fetch_object()){
                   $return.='<option selected value='.$row1->id.'>'.$row1->last_name." ".$row1->first_name." ".$row1->middle_name.'</option>';
               }
           }
	       $return.='</select>
	        </form>
	         <div id="error"></div>';
        }
    }
    
}else{
    $query = "SELECT * FROM `users` where director='$id_user'";
    $result = $link->query($query);
    
    $return='<form action="tasks.php" method="POST">
	            <input required type="text" name="title"  placeholder="Заголовок">
	            <textarea required name="description"  placeholder="Описание"></textarea>
	            <input required type="date" name="end_date"  placeholder="Дата окончания">
	            <select required name="priority">
	              <option disabled selected>Выберите приоритет</option>
                  <option value="высокий">высокий</option>
                  <option value="средний">средний</option>
                  <option value="низкий">низкий</option>
                </select>
                <select required name="status">
	              <option disabled selected>Выберите статус</option>
                  <option value="к выполнению">к выполнению</option>
                  <option value="выполняется">выполняется</option>
                  <option value="выполнена">выполнена</option>
                  <option value="отменена">отменена</option>
                </select>
                <select required name="responsible">';
    if($result->num_rows){
        $return.='<option disabled>Выберите отвественного</option>';
        while($row = $result->fetch_object()){
            $return.="<option value=".$row->id.">".$row->last_name." ".$row->first_name." ".$row->middle_name."</option>";
        }
    }else{
        $return.='<option disabled>У вас нет подчиненых</option>';
    }
    $return.='   </select>
                <input type="hidden" name="method" value="new_task">
	            <input type="button" id="creat-task-button" value="Создать задачу">
	        </form>
	         <div id="error"></div>';
}
$result->close();
$link->close();
echo $return;
?>