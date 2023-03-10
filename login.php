<?php
header('Access-Control-Allow-Origin: *');
include('connection.php');

$email=$_POST['email'];
$password=$_POST['password'];

$query=$mysqli->prepare('select id, name, email, password, dob, usertype_id from users where email=?');
$query->bind_param('s', $email);
$query->execute();
$query->store_result();
$num_rows=$query->num_rows();
$query->bind_result($id,$name,$email,$hashed_password,$dob,$usertype_id);
$query->fetch();

if($num_rows>0){
if($password==$hashed_password){
    $response['status']="logged in successfully";
    echo json_encode($response);
}
else{
    $response['status']="password is incorrect";
    echo json_encode($response);
}
}
else{
    $response['status']="user does not exist";
    echo json_encode($response);
}
?>