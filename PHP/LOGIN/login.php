<?php
// Connexion à la base de données (à adapter selon vos paramètres)
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "EPFL_timbreuse";

$conn = new mysqli($servername, $username, $password, $dbname);

// Vérifie la connexion
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les valeurs du formulaire
    $username = $_POST["username"];
    $password = $_POST["password"];



    // Récupère le mot de passe hashé de la base de données pour le nom d'utilisateur donné
    $stmt = $conn->prepare("SELECT password_personne FROM t_personne WHERE gaspar_personne = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($hashedPasswordFromDB);
    $stmt->fetch();
    $stmt->close();


    // Vérifie si le mot de passe correspond
    if (password_verify($password, $hashedPasswordFromDB)) {
        header("Location: ../../HTML/home/home.html");
    } else {
        //echo "Nom d'utilisateur ou mot de passe incorrect.";
        echo "BD: $hashedPasswordFromDB pwd: $password user: $username userdb: $gaspar";
    }
}

// Ferme la connexion à la base de données
$conn->close();
?>
