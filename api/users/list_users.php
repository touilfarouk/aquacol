<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include('../auth.php');
include('../config.php');
$form=json_decode(file_get_contents("php://input"));
    $token=$form->token;
    $payload = $jwt->getPayload($token);
    $id_user = $payload['user_id'];
    $role = $payload['role'];
  
try {

    //connexion a la base de données
    $bdd = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
  if($role==1)
    $sql = "SELECT * FROM users order by user_id desc ";
  if($role==2)
  $sql = "SELECT * FROM users WHERE role ='4' order by user_id desc ";
  if($role==6)
  $sql = "SELECT * FROM users WHERE role ='7' order by user_id desc ";
  if($role==3)
    $sql = "SELECT * FROM users WHERE role ='5' order by user_id desc ";
    $req = $bdd->prepare($sql);
    $req->execute();
    $output = [];

    while ($res = $req->fetch(PDO::FETCH_ASSOC)) {

        $output[] = $res;
    } //fin while

    $response = ['reponse' => "true", "liste" => $output , "role"=>$role];
    echo json_encode($response);
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo json_encode(array("reponse" => "false",));
}
