<?php
session_start();
header('Access-Control-Allow-Origin: *');
include('connection.php');

$medication_name=$_POST['medication_name'];
$quantity=$_POST['quantity'];
$user_id=$_SESSION['user_id'];

$result_hospital=$mysqli->prepare('select hospital_id from hospital_users where user_id=?');
$result_hospital->bind_param('s',$user_id);
$result_hospital->execute();
$result_hospital->store_result();
$num_rows=$result_hospital->num_rows();
if($num_rows>0){

    $result_medication=$mysqli->prepare('select id from medications where name=?');
    $result_medication->bind_param('s',$medication_name);
    $result_medication->execute();
    $result_medication->store_result();
    $result_medication->bind_result($medication_id);
    $result_medication->fetch();

    $choose_medication=$mysqli->prepare('insert into user_medications(user_id,medication_id,quantity) values(?,?,?);');
    $choose_medication->bind_param("iis",$user_id,$medication_id,$quantity);
    $choose_medication->execute();
    $choose_medication->store_result();

    $response['status']='chosen successfully';
    }
else{
    $response['status']='patient is not assigned to any hospital';
}
echo json_encode($response);
?>