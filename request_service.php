<?php
session_start();
header('Access-Control-Allow-Origin: *');
include('connection.php');

$service_name=$_POST['service_name'];
$quantity=$_POST['quantity'];
$user_id=$_SESSION['user_id'];
$usertype_id=1;

$result_hospital=$mysqli->prepare('select hospital_id from hospital_users where user_id=? and usertype_id=?');
$result_hospital->bind_param('ii',$user_id,$usertype_id);
$result_hospital->execute();
$result_hospital->store_result();
$hospital_rows=$result_hospital->num_rows();
if($hospital_rows>0){
    $result_hospital->bind_result($hospital_id);
    $result_hospital->fetch();

    $result_department=$mysqli->prepare('select department_id from user_departments where user_id=?');
    $result_department->bind_param('s',$user_id);
    $result_department->execute();
    $result_department->store_result();
    $department_rows=$result_department->num_rows();
    if($department_rows>0){
    $result_department->bind_result($department_id);
    $result_department->fetch();

    $result_service=$mysqli->prepare('select id from services where name=?');
    $result_service->bind_param('s',$service_name);
    $result_service->execute();
    $result_service->store_result();
    $result_service->bind_result($service_id);
    $result_service->fetch();

    
    if(//admin approval){
        //get employee id
        $choose_service=$mysqli->prepare('insert into user_services(patient_id,employee_id,department_id,hospital_id,service_id) values(?,?,?,?);');
        $choose_service->bind_param("iiii",$user_id,$employee_id,$department_id,$hospital_id,$service_id);
        $choose_service->execute();
        $choose_service->store_result();
    
        $response['status']='chosen successfully';
    }
    else{
        $response['status']='request not approved';
    }
    }
    else{
        $response['status']="no chosen department";
    }
    }
else{
    $response['status']='patient is not assigned to any hospital';
}
echo json_encode($response);
?>