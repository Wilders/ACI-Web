<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
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

    $bdd = new PDO('mysql:host=localhost;dbname=aciweb;charset=utf8', 'root', 'root');
    $req = $bdd->prepare('INSERT INTO contact(nom, prenom, email, raison, msg, dateMsg) VALUES(:nom, :prenom, :email, :raison, :msg, :dateMsg)');
    $datetime = date("Y-m-d H:i:s");
    $req->execute(array(
        'nom' =>  htmlentities($_POST['name']),
        'prenom' => htmlentities($_POST['surname']),
        'email' => htmlentities($_POST['email']),
        'raison' => htmlentities($_POST['need']),
        'msg' => htmlentities($_POST['message']),
        'dateMsg' => $datetime
    ));
    $answer = array('type' => 'success', 'message' => 'Votre message a bien été envoyé :)');

} catch(Exception $e) {
    $answer = array('type' => 'danger', 'message' => $e->getMessage());
}

if(isset($answer)) {
    header('Content-Type: application/json');
    echo json_encode($answer);
}