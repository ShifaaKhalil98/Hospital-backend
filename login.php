<?php
session_start();
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
if(password_verify($password,$hashed_password)){
    $_SESSION['logged_in']=true;
    $_SESSION['user_id']=$id;
    $_SESSION['name']=$name;
    $_SESSION['usertype_id']=$usertype_id;
    $response['status']="logged in successfully";
}
else{
    $response['status']="password is incorrect";
}
}
else{
    $response['status']="user does not exist";
}
echo json_encode($response);
?>