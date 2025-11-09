<?php

include('../auth.php');
include('../config.php');
$form=json_decode(file_get_contents("php://input"));
    $token=$form->token;
    $payload = $jwt->getPayload($token);
    $role = $payload['role'];
  
try {
    $response = ["role"=>$role];
    echo json_encode($response);
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo json_encode(array("reponse" => "false"));
}
