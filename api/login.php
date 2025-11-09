<?php

// header("Access-Control-Allow-Origin: https://app.bneder.dz/");

// for localhost use this :
header("Access-Control-Allow-Origin: *");




header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");

header("Access-Control-Allow-Methods: POST");

require_once './config.php';
require_once './classes/JWT.php';



$Received_JSON = file_get_contents('php://input');
$obj = json_decode($Received_JSON, true);
if (isset($obj['email']) && isset($obj['password'])) {





    $email = $obj['email'];
    //echo $username;
    $password = $obj['password'];
    //echo $password;


    try {
        if (empty($password) || empty(trim($email))) {

            die(json_encode(array(
                "reponse" => "false", "message" => 1
            )));
        } else {
            $email = trim($email);
            $password = trim($password);
            $password = sha1($password);

            //connexion a la base de données
            $bdd = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

            $req = $bdd->prepare('select * from  users where email = ? and password = ? ');
            $req->execute(array($email, $password));
            $res = $req->fetch(PDO::FETCH_ASSOC);

            $count = $req->rowCount();

            if ($count > 0) {
                // On crée le header
                $header = [
                    'typ' => 'JWT',
                    'alg' => 'HS256'
                ];

                $payload = [
                    'user_id' => $res['user_id'],
                    'email' => $res['email'],
                    'fname' => $res['fname'],
                    'lname' => $res['lname'],
                    'role'=>$res['role']
                ];
                $validity = 604800;

                $jwt = new JWT();

                $token = $jwt->generate($header, $payload, SECRET, $validity);

                echo json_encode(array("token" => $token));
            } else {
                die(json_encode(array(
                    "reponse" => "false", "message" => 2
                )));
            }
        }


        //echo json_encode($count);


    } catch (Exception $e) {
        $msg = $e->getMessage();
        die(json_encode(array(
            "reponse" => "false", "message" => $msg
        )));
    }
} else {
    die(json_encode(array(
        "reponse" => "false", "message" => 3
    )));
}


