<?php
header('Access-Control-Allow-Origin: *');
include('connection.php');

$employee_name=$_POST['employee_name'];
$hospital_name=$_POST['hospital_name'];

$result_hospital=$mysqli->prepare('select id from hospitals where name=?');
$result_hospital->bind_param('s',$hospital_name);
$result_hospital->execute();
$result_hospital->store_result();
$result_hospital->bind_result($hospital_id);
$result_hospital->fetch();

$result_employee=$mysqli->prepare('select id from users where name=? and usertype_id=2');
$result_employee->bind_param('s',$employee_name);
$result_employee->execute();
$result_employee->store_result();
$num_employees=$result_employee->num_rows();
if($num_employees>0){
    $result_employee->bind_result($employee_id);
    $result_employee->fetch();

    $check=$mysqli->prepare('select hospital_id,user_id from hospital_users where hospital_id=? and user_id=?');
    $check->bind_param('ii',$hospital_id,$employee_id);
    $check->execute();
    $check->store_result();
    $num_rows=$check->num_rows();

    if($num_rows>0){
        $response['status']='employee already exists';
    }else{
       $add_employee=$mysqli->prepare('insert into hospital_users(hospital_id,user_id) values(?,?);');
       $add_employee->bind_param("ii",$hospital_id,$employee_id);
       $add_employee->execute();
       $add_employee->store_result();

       $response['status']='added';
    }
}else{
    $response['status']='employee has no account';
}
echo json_encode($response);
?>