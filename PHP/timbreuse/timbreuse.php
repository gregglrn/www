<?php
// Assurez-vous de configurer ces informations correctement
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EPFL_timbreuse";

// Créez une connexion à la base de données
$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifiez la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}

// Récupérez le temps envoyé depuis la requête POST
$data = json_decode(file_get_contents("php://input"), true);
$time = $data["time"];

// Échappez les caractères spéciaux pour éviter les injections SQL
$time = $conn->real_escape_string($time);

// Insérez le temps dans la base de données
$sql = "INSERT INTO temps_enregistres (temps) VALUES ('$time')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(array("success" => true, "message" => "Le temps a été enregistré avec succès."));
} else {
    echo json_encode(array("success" => false, "message" => "Erreur lors de l'enregistrement du temps : " . $conn->error));
}

// Fermez la connexion à la base de données
$conn->close();
?>