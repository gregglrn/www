<?php
session_start(); // Démarrer la session

// Variables de connexion à la base de données
$serveurNom = "localhost";
$nomUtilisateur = "root";
$motDePasse = "";
$nomBaseDeDonnees = "epfltimbreuse";

// Connexion à la base de données
$connexion = new mysqli($serveurNom, $nomUtilisateur, $motDePasse, $nomBaseDeDonnees);

    // Parse URL parameters to get day, month, and year values
    $day = isset($_GET['day']) ? $_GET['day'] : '';
    $month = isset($_GET['month']) ? $_GET['month'] : '';
    $year = isset($_GET['year']) ? $_GET['year'] : '';
    
        // Format the date values into "dd/mm/YYYY" format
        $formattedDate = sprintf('%02d/%02d/%04d', $day, $month, $year);

// Vérification de la connexion
if ($connexion->connect_error) {
    die("La connexion a échoué : " . $connexion->connect_error);
}

// Traitement du formulaire lorsque soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Récupération et échappement des données du formulaire
    $debutHeure = $connexion->real_escape_string($_POST['debut']);
    $debutMidi = $connexion->real_escape_string($_POST['debut_midi']);
    $finMidi = $connexion->real_escape_string($_POST['fin_midi']);
    $finHeure = $connexion->real_escape_string($_POST['fin']);
    $gasparPersonne = $_SESSION['user_id']; // Utilisateur connecté

    // Vérification si des données existent déjà pour cette date
    $sqlCheck = "SELECT h.id_heure
    FROM t_heure h
    JOIN t_heure_personne hp ON h.id_heure = hp.id_heure
    JOIN t_personne p ON hp.id_personne = p.id_personne
    WHERE p.gaspar_personne = '$gasparPersonne'
    AND h.date = '$formattedDate'";
    
    $resultCheck = $connexion->query($sqlCheck);
    
    if ($resultCheck->num_rows > 0) {
        // Données existent déjà, effectuer une mise à jour (UPDATE)
        $sql = "UPDATE t_heure
        SET debut_heure = '$debutHeure',
            debut_midi = '$debutMidi',
            fin_midi = '$finMidi',
            fin_heure = '$finHeure'
        WHERE id_heure IN (
            SELECT hp.id_heure
            FROM t_heure_personne hp
            JOIN t_personne p ON hp.id_personne = p.id_personne
            WHERE p.gaspar_personne = '$gasparPersonne')
            AND date = '$formattedDate'";
    } else {
        // Aucune donnée existante, effectuer une insertion (INSERT)
        $sql = "INSERT INTO t_heure (date, debut_heure, debut_midi, fin_midi, fin_heure, gaspar_personne) VALUES ('$formattedDate', '$debutHeure', '$debutMidi', '$finMidi', '$finHeure', '$gasparPersonne')";
    }

    // Exécution de la requête SQL
    if ($connexion->query($sql) === TRUE) {
        echo "Enregistrement réussi";
    } else {
        echo "Erreur : " . $sql . "<br>" . $connexion->error;
    }
}

// Fermeture de la connexion à la base de données
$connexion->close();
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>EPFL - Gestion des heures</title>
<link rel="stylesheet" href="../../CSS/Heure/Heure.css">
</head>
<body>
<header>
        <img src="../../img/epfllogo.png" alt="EPFL Logo">
    </header>
    
    <main>
        <h1>Gestion des heures</h1>
        <p class="time" id="dateTime"></p>
        </main>
        <div class="work-hours-form">
    <h2>Entrez les heures de travail du <?php echo $formattedDate; ?></h2>
    <form action="" method="post">
        <label for="debut">Début :</label>
        <input type="time" name="debut" id="debut" required><br>
        
        <label for="debut_midi">Début Midi :</label>
        <input type="time" name="debut_midi" id="debut_midi" required><br>
        
        <label for="fin_midi">Fin Midi :</label>
        <input type="time" name="fin_midi" id="fin_midi" required><br>
        
        <label for="fin">Fin :</label>
        <input type="time" name="fin" id="fin" required><br>

        <button type="submit">Enregistrer</button>
    </form>
    </div>
<script src="../../JS/HOME/homedate&heure.js"></script>
</body>
</html>


