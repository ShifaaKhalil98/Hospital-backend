<?php
session_start();
header('Access-Control-Allow-Origin: *');
include('connection.php');

$date_joined=$_POST['date_joined'];
$user_id=$_SESSION['user_id'];
$usertype_id=2;

$result=$mysqli->prepare('select user_id from hospital_users where user_id=? and usertype_id=?');
$result->bind_param('ii',$user_id,$usertype_id);
$result->execute();
$result->store_result();
$num_rows=$result->num_rows();
if($num_rows>0){

    $result=$mysqli->prepare('update employee_info set bank_account=? where user_id=?');
    $result->bind_param('si',$bank_account,$user_id);
    $result->execute();
    $result->store_result();
    
    $response['status']='updated successfully';
    }
else{
    $response['status']='no account';
}
echo json_encode($response);
?>