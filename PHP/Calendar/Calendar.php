<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EPFL - Gestion des heures</title>
    <link rel="stylesheet" href="../../CSS/Calendar/Calendar2.css">
    <link rel="stylesheet" href="../../CSS/Calendar/Calendar.css">
</head>
<body>
    <header>
        <img src="../../img/epfllogo.png" alt="EPFL Logo">
    </header>
    
    <main>
        <h1>Gestion des heures</h1>
        <p class="time" id="dateTime"></p>
        <?php
session_start(); // Démarrer la session

// Vérifier si l'utilisateur est connecté en vérifiant la variable de session
if (isset($_SESSION['user_id'])) {
    // Utilisateur connecté
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "EPFL_timbreuse";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Vérification de la connexion
    if ($conn->connect_error) {
        die("Erreur de connexion à la base de données : " . $conn->connect_error);
    }
    
    // Utilisateur connecté, récupérez le gaspar de l'utilisateur depuis la session
    $gaspar = $_SESSION['user_id'];

    // Requête SQL pour récupérer les données de prénom, nom et heure_par_jour
    $sql = "SELECT p.prenom_personne, p.nom_personne, h.date, h.heure
    FROM t_personne p
    INNER JOIN t_timbrage h ON p.id_personne = h.id_personne
    WHERE gaspar_personne = '$gaspar'";
    
    $result = $conn->query($sql);

    if ($result === false) {
        die("Erreur lors de l'exécution de la requête SQL : " . $conn->error);
    }

    if ($result->num_rows > 0) {
        // Affichage des données dans la balise <p>
        while ($row = $result->fetch_assoc()) {
            echo "<h1>" . htmlspecialchars($row["prenom_personne"]) . " " . htmlspecialchars($row["nom_personne"]) . "</h1>";
            $heureParJour = $row["heure_par_jour"];
            echo "Heure de travail: " . $heureParJour . "<br>";
        }
    } else {
        echo "Aucune donnée trouvée pour cet utilisateur.";
    }
    
    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    // L'utilisateur n'est pas connecté, redirigez-le vers la page de connexion
    header("Location: ../../PHP/Login.php");
    exit();
}
?>
    </main>
    <div class="container">
        <div class="calendar">
            <div class="header">
                <button id="prevBtn">&lt;</button>
                <h2 id="monthYear"></h2>
                <button id="nextBtn">&gt;</button>
            </div>
            <div class="days"></div>
        </div>
    </div>
    <script src="../../JS/Calendar/homedate&heure.js"></script>
    <script src="../../JS/Calendar/Calendar.js"></script>
</body>
</html>

