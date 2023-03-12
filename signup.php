<?php
header('Access-Control-Allow-Origin: *');
include('connection.php');

$email=$_POST['email'];
$password=$_POST['password'];
$name=$_POST['name'];
$dob=$_POST['dob'];
$usertype_id=1;
$phone_number=$_POST['phone_number'];

$check=$mysqli->prepare('select email from users where email=?');
$check->bind_param('s',$email);
$check->execute();
$check->store_result();
$num_rows=$check->num_rows();

if($num_rows>0){
    $response['status']='email already exists';
    echo json_encode($response);
}else{
    if(strlen($password)>=8 && preg_match('/[A-Z]/', $password) && preg_match('/[a-z]/', $password) && preg_match('/\d/',$password) && preg_match('/[!@#$%^&*()\-_=+{};:,<.>]/',$password)){
        $hashed_password=password_hash($password, PASSWORD_BCRYPT);
        $query=$mysqli->prepare('insert into users(name,email,password,dob,usertype_id,phone_number) values(?,?,?,?,?,?)');
        $query->bind_param('ssssii',$name,$email,$hashed_password, $dob, $usertype_id,$phone_number);
        $query->execute();
        $response['status']='user added successfully';
    }
    else{
        $response['status']='password is not valid';
    }
    echo json_encode($response);
}
?>