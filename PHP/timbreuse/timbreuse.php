<?php
// Configuration de la base de données
$servername = "127.0.0.1"; // Adresse du serveur MySQL
$username = "root"; // Nom d'utilisateur MySQL
$password = ""; // Mot de passe MySQL
$dbname = "EPFL_timbreuse"; // Nom de la base de données

// Récupérer le temps envoyé depuis JavaScript
$data = json_decode(file_get_contents('php://input'), true);
$time = $data['time'];

// Connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérification de la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Préparation et exécution de la requête SQL pour insérer le temps dans la base de données
$sql = "INSERT INTO t_heure  (heure_totale) VALUES ('$time')";
if ($conn->query($sql) === TRUE) {
    $response = array("success" => true, "message" => "Le temps a été enregistré avec succès.");
} else {
    $response = array("success" => false, "message" => "Erreur lors de l'enregistrement du temps : " . $conn->error);
}

// Fermeture de la connexion à la base de données
$conn->close();

// Renvoyer la réponse au format JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
