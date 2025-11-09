<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include('../auth.php');
include('../config.php');


try {

    //connexion a la base de données
    $bdd = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    $sql = "select * FROM `users` WHERE user_id=?";
    $req = $bdd->prepare($sql);
    $req->execute(array($data->user_id));
    $res = $req->fetch(PDO::FETCH_ASSOC);

    $response = ['reponse' => "true", "result" => $res];
    echo json_encode($response);
} catch (Exception $e) {
    $msg = $e->getMessage();
    echo json_encode(array("reponse" => "false",));
}
