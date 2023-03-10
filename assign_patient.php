<?php
header('Access-Control-Allow-Origin: *');
include('connection.php');

$patient_name=$_POST['patient_name'];
$hospital_name=$_POST['hospital_name'];

$result_hospital=$mysqli->prepare('select id from hospitals where name=?');
$result_hospital->bind_param('s',$hospital_name);
$result_hospital->execute();
$result_hospital->store_result();
$result_hospital->bind_result($hospital_id);
$result_hospital->fetch();

$result_patient=$mysqli->prepare('select id from users where name=?');
$result_patient->bind_param('s',$patient_name);
$result_patient->execute();
$result_patient->store_result();
$result_patient->bind_result($patient_id);
$result_patient->fetch();

$check=$mysqli->prepare('select hospital_id,user_id from hospital_users where hospital_id=? and user_id=?');
$check->bind_param('ii',$hospital_id,$patient_id);
$check->execute();
$check->store_result();
$num_rows=$check->num_rows();

if($num_rows>0){
    $response['status']='patient already exists';
}else{
    $add_patient=$mysqli->prepare('insert into hospital_users(hospital_id,user_id) values(?,?);');
    $add_patient->bind_param("ii",$hospital_id,$patient_id);
    $add_patient->execute();
    $add_patient->store_result();
    
    $response['status']='added';
}
echo json_encode($response);
?>