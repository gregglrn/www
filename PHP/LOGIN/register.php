<?php
session_start();

$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$nomBaseDeDonnees = "EPFL_timbreuse";

$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);

if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}

$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$mail = $_POST['mail'];
$gaspar = $_POST['gaspar'];
$password = $_POST['password'];

// Validation et nettoyage des données
$prenom = filter_var($prenom, FILTER_SANITIZE_STRING);
$nom = filter_var($nom, FILTER_SANITIZE_STRING);
$mail = filter_var($mail, FILTER_VALIDATE_EMAIL);
$gaspar = filter_var($gaspar, FILTER_SANITIZE_STRING);

// Crypter le mot de passe avec bcrypt
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Requête préparée pour l'insertion
$requete = "INSERT INTO t_personne (prenom_personne, nom_personne, mail_personne, gaspar_personne, password_personne) VALUES (?, ?, ?, ?, ?);";
$stmt = $connexion->prepare($requete);

$stmt->bind_param("sssss", $prenom, $nom, $mail, $gaspar, $hashedPassword);

if ($stmt->execute()) {
    header("Location: ../../HTML/home/home.html");
} else {
    echo "Erreur lors de l'inscription : " . $stmt->error;
}

$stmt->close();
$connexion->close();
?>
