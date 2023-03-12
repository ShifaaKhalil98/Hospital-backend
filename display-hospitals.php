<?php
include("connection.php");

$result = $mysqli->prepare("SELECT * FROM hospitals;");
$result->execute();
$array=$result->get_result();

$hospitals = array();

while($row = $array->fetch_assoc()) {
    $hospitals[] = $row;
}
echo json_encode($hospitals);
?>