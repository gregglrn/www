<?php
session_start();

// Connexion sécurisée à la base de données
$serveur = "localhost";
$utilisateur = "root";
$motDePasse = "";
$nomBaseDeDonnees = "EPFL_timbreuse";

$connexion = new mysqli($serveur, $utilisateur, $motDePasse, $nomBaseDeDonnees);
if ($connexion->connect_error) {
    die("Erreur de connexion à la base de données : " . $connexion->connect_error);
}

// Récupérer les valeurs du formulaire
$username = $_POST['username'];
$password = $_POST['password'];

// Préparer la requête SQL pour obtenir le mot de passe haché associé à l'username
$requete = "SELECT gaspar_personne, password_personne FROM t_personne WHERE gaspar_personne = ?";
$statement = $connexion->prepare($requete);
$statement->bind_param("s", $username);
$statement->execute();
$resultat = $statement->get_result();

if ($resultat->num_rows === 1) {
    $row = $resultat->fetch_assoc();
    $hashedPassword = $row['password_personne'];
    
    if (password_verify($password, $hashedPassword)) {
        // Les informations d'identification sont valides
        
        // Génération d'un jeton de session sécurisé
        $sessionToken = bin2hex(random_bytes(32));
        
        // Enregistrez l'identifiant de l'utilisateur et le jeton de session dans les variables de session
        $_SESSION['user_id'] = $username;
        $_SESSION['session_token'] = $sessionToken;
       
        // Rediriger vers la page home
        header("Location: ../HOME/home.php");
        exit();
    } else {
        // Mot de passe incorrect
        echo "Mot de passe incorrect!";
    }
} else {
    // L'utilisateur n'existe pas
    echo "Identifiants invalides!";
}

// Fermer la requête préparée
$statement->close();

// Fermer la connexion à la base de données
$connexion->close();
?>
