<?php
try {
    if(empty($_SERVER['HTTP_X_REQUESTED_WITH']) || strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) != 'xmlhttprequest') {
        throw new Exception("Le script n'est accessible que depuis le formulaire de contact :/");
    }

    if(!isset($_POST['surname']) || !isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['need']) || !isset($_POST['message'])) {
        throw new Exception("Un des champs est manquant");
    }
    
    if (empty($_POST['surname']) || empty($_POST['name']) || empty($_POST['email']) || empty($_POST['need']) || empty($_POST['message'])) {
        throw new Exception("Un des champs est vide");
    }

    if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
        throw new Exception("Votre adresse email est invalide.");
    }

    if (!in_array($_POST['need'], ['Information','Partenariat', 'Autres'], true)) {
        throw new Exception("La raison n'est pas valide");
    }

    $answer = array('type' => 'success', 'message' => 'Votre message a bien été envoyé :)');

} catch(Exception $e) {
    $answer = array('type' => 'danger', 'message' => $e->getMessage());
}

if(isset($answer)) {
    header('Content-Type: application/json');
    echo json_encode($answer);
}

try
{
	$bdd = new PDO('mysql:host=localhost;dbname=aci - web;charset=utf8', 'root', 'root');
}
catch (Exception $e)
{
        die('Erreur : ' . $e->getMessage());
}

$bdd->exec('INSERT INTO contact(nom, prenom, email, raison, msg, dateMsg) VALUES(:nom, :prenom, :email, :raison, :msg, :dateMsg)');

$datetime = date("Y-m-d H:i:s");
$req->execute(array(
	'nom' =>  $_POST['name'],
    'prenom' => $_POST['surname'],
    'email' => $_POST['email'],
    'raison' => $_POST['need'],
    'msg' => $_POST['message'],
    'dateMsg' => $datetime
));