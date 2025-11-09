<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST,GET");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
include('../auth.php');
include('../config.php');
include('../config2.php');

$payload = $jwt->getPayload($token);
$user_email = $payload['email'];
$data = $data->form;


if (!isset($data->fname) || empty($data->fname) || !isset($data->lname) || empty($data->lname) || !isset($data->email) || empty($data->email) || !isset($data->structure) || empty($data->structure) ||  !isset($data->poste) || empty($data->poste) || !isset($data->password) || empty($data->password)) {
    die(json_encode(array('reponse' => 'false', 'message' => 1)));
}


$fname = $data->fname;
$lname = $data->lname;
$email = $data->email;
$structure = $data->structure;
$poste = $data->poste;
$password = $data->password;
$role = $data->role;
$pass=$password ;
$password = sha1($password);

//verifier si  l'email de bneder
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die(json_encode(array('reponse' => 'false', 'message' => 3)));
}

$bdd2 = new PDO("mysql:host=" . DB_SERVER2 . ";dbname=" . DB_NAME2 . "; charset=utf8", DB_USER2, DB_PASS2, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));


$username=str_replace('@bneder.dz','',$email);
$sql = "SELECT * FROM `mailbox`  where username=?";
$req = $bdd2->prepare($sql);
$req->execute(array($username));
$count = $req->rowCount();

if($count==0){
    die(json_encode(array('reponse' => 'false', 'message' => 2)));
}else{

    try {

        //connexion a la base de données
        $bdd = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . "; charset=utf8", DB_USER, DB_PASS, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    
        $sql = "INSERT INTO `users`( `fname`, `lname`, `email`, `password`, `structure`, `poste`, `date_creation`,`role`) VALUES(?,?,?,?,?,?,NOW(),?)";
        $req = $bdd->prepare($sql);
        $req->execute(array(
            $fname, $lname, $email, $password, $structure, $poste,$role
        ));
        $response = ['reponse' => "true"];
        echo json_encode($response);
        /*************************** Send mail *********************************** */
        $to = $email;
        $subject = "Creation de compte dans l'application IFN";
        //$body = "Votre compte est :" . "\n" . "Email :" . "  " . $email . "\n" . "Mot de passe :" . " " . $pass;
   	$body ="Bonjour," . "\n" . "
	Un compte dans l'application IFN a ete cree pour vous par l'administrateur." . "\n" . "
	Voici vos informations de connexion:" . "\n" . "
	email: " . $email  . "\n" . "
	Mot de passe: " . $pass . "\n" . "

	Voici le lien vers l'application: http://app.bneder.dz/ifn" . "\n"  . "\n" . "
	Cordialement.
	";
        $headers = 'From: ifn'  ;
      
    
        mail($to, $subject, $body, $headers);
        /************************************************************************ */
    } catch (Exception $e) {
        $msg = $e->getMessage();
        echo json_encode(array("reponse" => "false",));
    }
}



