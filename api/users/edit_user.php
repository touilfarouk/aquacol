<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include('../auth.php');
include('../config.php');

$data = $data->form;
if (!isset($data->fname) || empty($data->fname) || !isset($data->lname) || empty($data->lname) || !isset($data->email) || empty($data->email) || !isset($data->structure) || empty($data->structure) ||  !isset($data->poste) || empty($data->poste)) {
    die(json_encode(array('reponse' => 'false', 'message' => 1)));
}


$user_id= $data->user_id;
$fname = $data->fname;
$lname = $data->lname;
$email = $data->email;
$structure = $data->structure;
$poste = $data->poste;
$role = $data->role;

try {
    // Connect to the database
    $bdd = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    // Update the record
    $sql = "UPDATE `users` SET `fname`=?, `lname`=?, `email`=?, `structure`=?, `poste`=?, `role`=? WHERE `user_id`=?";
    $req = $bdd->prepare($sql);
    $req->execute(array($fname, $lname, $email, $structure, $poste, $role, $user_id));

    $response = ['reponse' => "true"];
    echo json_encode($response);
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo json_encode(array("reponse" => "false", "message" => $msg));
}
?>
