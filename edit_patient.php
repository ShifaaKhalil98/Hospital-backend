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

    $result=$mysqli->prepare('update patients_info set blood_type=?, bank_account=? where user_id=?');
    $result->bind_param('ssi',$blood_type,$bank_account,$user_id);
    $result->execute();
    $result->store_result();
    
    $response['status']='updated successfully';
    }
else{
    $response['status']='no account';
}
echo json_encode($response);
?>