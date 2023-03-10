<?php
session_start();
header('Access-Control-Allow-Origin: *');
include('connection.php');

$department_name=$_POST['department_name'];
$room_number=$_POST['room_number'];
$user_id=$_SESSION['user_id'];

$result_hospital=$mysqli->prepare('select hospital_id from hospital_users where user_id=?');
$result_hospital->bind_param('s',$user_id);
$result_hospital->execute();
$result_hospital->store_result();
$num_rows=$result_hospital->num_rows();
if($num_rows>0){
    $result_hospital->bind_result($hospital_id);
    $result_hospital->fetch();

    $result_department=$mysqli->prepare('select id from departments where name=?');
    $result_department->bind_param('s',$department_name);
    $result_department->execute();
    $result_department->store_result();
    $result_department->bind_result($department_id);
    $result_department->fetch();

    $result_room=$mysqli->prepare('select id from rooms where room_number=?');
    $result_room->bind_param('s',$room_number);
    $result_room->execute();
    $result_room->store_result();
    $result_room->bind_result($room_id);
    $result_room->fetch();

    $choose_department=$mysqli->prepare('insert into user_departments(user_id,department_id,hospital_id) values(?,?,?);');
    $choose_department->bind_param("iii",$user_id,$department_id,$hospital_id);
    $choose_department->execute();
    $choose_department->store_result();

    $response['status']='chosen successfully';
    }
else{
    $response['status']='patient is not assigned to any hospital';
}
echo json_encode($response);
?>