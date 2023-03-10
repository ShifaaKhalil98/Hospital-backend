<?php
session_start();
header('Access-Control-Allow-Origin: *');
include('connection.php');

$blood_type=$_POST['blood_type'];
$bank_account=$_POST['bank_account'];
$user_id=$_SESSION['user_id'];
$usertype_id=1;

$result=$mysqli->prepare('select id from users where id=? and usertype_id=?');
$result->bind_param('ii',$user_id,$usertype_id);
$result->execute();
$result->store_result();
$num_rows=$result->num_rows();
if($num_rows>0){

    $result_department=$mysqli->prepare('update user_info set blood_type=?, bank_account=? where user_id=?');
    $result_department->bind_param('ssi',$blood_type,$bank_account,$user_id);
    $result_department->execute();
    $result_department->store_result();
    
    $response['status']='updated successfully';
    }
else{
    $response['status']='account';
}
echo json_encode($response);
?>