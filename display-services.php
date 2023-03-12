<?php
include("connection.php");

$hospital_id = $_GET['id'];

$result = $mysqli->prepare("select * FROM services where hospital_id=?;");
$result->bind_param('i',$hospital_id);
$result->execute();
$array=$result->get_result();

$departments = array();

while($row = $array->fetch_assoc()) {
    $departments[] = $row;
}
echo json_encode($departments);
?>
