<?php
header('Access-Control-Allow-Origin: *');
include('connection.php');

$patient_name=$_POST['patient_name'];
$hospital_name=$_POST['hospital_name'];
$usertype_id=1;

$result_hospital=$mysqli->prepare('select id from hospitals where name=?');
$result_hospital->bind_param('s',$hospital_name);
$result_hospital->execute();
$result_hospital->store_result();
$result_hospital->bind_result($hospital_id);
$result_hospital->fetch();

$result_patient=$mysqli->prepare('select id from users where name=? and usertype_id=?');
$result_patient->bind_param('si',$patient_name,$usertype_id);
$result_patient->execute();
$result_patient->store_result();
$num_patients=$result_patient->num_rows();
if($num_patients>0){
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
       $add_patient=$mysqli->prepare('insert into hospital_users(hospital_id,user_id,usertype_id) values(?,?,?);');
       $add_patient->bind_param("iii",$hospital_id,$patient_id,$usertype_id);
       $add_patient->execute();
       $add_patient->store_result();

       $response['status']='added';
    }
}else{
    $response['status']='patient has no account';
}
echo json_encode($response);
?>