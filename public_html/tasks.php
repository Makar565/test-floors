<?php session_start();
require_once 'connection.php';
$link =  new mysqli($host, $user, $password, $database) ;
if (mysqli_connect_errno()) { 
    printf("Ошибка подключения: %s\n", mysqli_connect_error()); 
    exit();
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">

	<title>Приложение TODO list</title>
	<script src='https://code.jquery.com/jquery-3.5.1.min.js'></script>
</head>
<body>

    <?php 
     
    if(isset($_SESSION['id_user'])){
        $id=$_SESSION['id_user'];
    ?>
    <div id="out"></div>
    <button class="class-button" id="creat-button">Новая задача</button>
    <button class="class-button" id="exit-button">Выход</button>
    <select name="sort" id="select-sort">
        <optgroup label = "По дате завершения">
				<option value = "На сегодня" selected>На сегодня</option>
				<option value = "На неделю">На неделю</option>
				<option value = "На будущее"  >На будущее</option>
		</optgroup>
        <option value='update_date' >По дате последнего обновления</option>
        <?php 
        $query = "SELECT * FROM `users` where director='$id'";
        $result = $link->query($query);
        if($result->num_rows){
            echo '<optgroup label = "По отвественным">';
            
            
            while($row = $result->fetch_object()){
                echo '<option value ='.$row->id.'>'.$row->last_name." ".$row->first_name." ".$row->middle_name.' </option>';
            }
            echo '</option>';
        }
        $result->free();
	    $link->close();
         ?>
    </select>
	<ul class="tasks" id="list-tasks">
	  
	    
	</ul>
	
	<div class="modal closed" id="modal">
	    <div id="model-div">
	        
	    </div>
	<button class="class-button" id="close-button">Закрыть</button>
	</div>
	<div class="modal-overlay closed" id="modal-overlay"></div>

<script>
function sorting(){
    var modal = document.querySelector("#modal"),
    modalOverlay = document.querySelector("#modal-overlay");
    $.ajax({
    type: "POST",
    url: "list_tasks.php",
    data: 
    {
        sort:$('select[name="sort"]').val()  
    }
   }).done(function( result )
        {
            $("#list-tasks").html( result );
            $( "ul.tasks li" ).on( "click", function( event ) {
                var tasksid = $(this).data('tasksid'); 
                $.ajax({
                    type: "POST",
                    url: "task.php",
                    data: {id:tasksid}
                }).done(function( result )
                    {
                        $("#model-div").html( result );
                         $("#update-task-button").on( "click", function( event ) {
                           if($('input[name="title"]').val()=='' || $('textarea[name="description"]').val()=='' || $('input[name="end_date"]').val()=='' || $('select[name="priority"]').val()=='' || $('select[name="status"]').val()=='' || $('select[name="responsible"]').val()==''){
                             $("#error").html( "Не все поля заполнены" );
                            }else{
                        $.ajax({
                            type: "POST",
                            url: "update_task.php",
                            data: {
                                method:$('input[name="method"]').val(),
                                title:$('input[name="title"]').val(),
                                description:$('textarea[name="description"]').val(),
                                end_date:$('input[name="end_date"]').val(),
                                priority:$('select[name="priority"]').val(),
                                status:$('select[name="status"]').val(),
                                responsible:$('select[name="responsible"]').val()
                            }
                        }).done(function( result )
                        {
                            $("#out").html( result );
                            document.location.href="/tasks.php";
                        });
                        }
                       });
                    });
               modal.classList.toggle("closed");
               modalOverlay.classList.toggle("closed");
                });
            });
}
$(document).ready(function() {
sorting();
$( "#select-sort" ).change(function() {
 sorting();
});

var modal = document.querySelector("#modal"),
    modalOverlay = document.querySelector("#modal-overlay");

 
$( "#exit-button" ).on( "click", function( event ) {
   document.location.href="/index.php";
});
$( "#close-button" ).on( "click", function( event ) {
   modal.classList.toggle("closed");
   modalOverlay.classList.toggle("closed");
});
$( "#creat-button" ).on( "click", function( event ) {
     $.ajax({
        type: "POST",
        url: "task.php",
        data: {}
    }).done(function( result )
        {
            $("#model-div").html( result );
            $("#creat-task-button").on( "click", function( event ) {
                if($('input[name="title"]').val()=='' || $('textarea[name="description"]').val()=='' || $('input[name="end_date"]').val()=='' || $('select[name="priority"]').val()=='' || $('select[name="status"]').val()=='' || $('select[name="responsible"]').val()==''){
                     $("#error").html( "Не все поля заполнены" );
                }else{
                $.ajax({
                    type: "POST",
                    url: "new_task.php",
                    data: {
                        method:'new_task',
                        title:$('input[name="title"]').val(),
                        description:$('textarea[name="description"]').val(),
                        end_date:$('input[name="end_date"]').val(),
                        priority:$('select[name="priority"]').val(),
                        status:$('select[name="status"]').val(),
                        responsible:$('select[name="responsible"]').val()
                    }
                }).done(function( result )
                    {
                        $("#out").html( result );
                        document.location.href="/tasks.php";
                    });
                }
           });    
          
        });
   
   modal.classList.toggle("closed");
   modalOverlay.classList.toggle("closed");
});

}); 
</script>
<style>
input{
    width: 100%;
}
#close-button{
    height: 50px;
}
    .modal {
        background:white;
  position: fixed;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 600px;
  max-width: 100%;
  height: 400px;
  max-height: 100%;
  z-index: 1010;
  display: flex;
}
.modal.closed {
display: none;
}
.modal-overlay.closed {
display: none;
}
.modal-overlay {
  z-index: 1000;
  position: fixed;
  top:0;
  left:0;
  width: 100%;
  height: 100%;
}
.tasks li{
    cursor: pointer;
}
</style>
 <?php }
 else{echo "Войдите для начала<br>";echo "<a href='/index.php'>Войти</a>";}?>

</body>
</html>