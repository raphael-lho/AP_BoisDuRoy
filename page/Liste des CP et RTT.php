<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/css/style.css" />
    <title>Liste des CP et RTT</title>
</head>
<body>
    <div id="congesBox">
        <h2>Liste des congés :</h2>
        <?php
        // Connexion à la base de données
        $conn = mysqli_connect("localhost", "mpele", "sn", "bdd_bois_du_roy");

        // Vérification de la connexion
        if (!$conn) {
            die("Connexion échouée : " . mysqli_connect_error());
        }

        // Récupération de l'identifiant de l'utilisateur connecté
        session_start();

        if (session_status() == PHP_SESSION_ACTIVE && isset($_SESSION["identifiant"])) {
            $identifiant = $_SESSION["identifiant"];
        
            // Exécution de la requête
            $sql = "SELECT EMPLOYE.NOM_EMP, CONGE.DATE_DEBUT, CONGE.DATE_FIN FROM CONGE INNER JOIN EMPLOYE ON CONGE.MATRICULE_EMP = EMPLOYE.MATRICULE_EMP WHERE CONGE.MATRICULE_EMP = '$identifiant';";
        
            $resultat = mysqli_query($conn, $sql);
        
            // Traitement des résultats
            if (mysqli_num_rows($resultat) > 0) {
                echo "<form method='post'>";
                echo "<ul>";
                while ($ligne = mysqli_fetch_assoc($resultat)) {
                    echo "<li>Nom : " . $ligne["NOM_EMP"] . " - Début : " . $ligne["DATE_DEBUT"] . " - Fin : " . $ligne["DATE_FIN"] . 
                         " <input type='radio' name='validation_" . $ligne["NOM_EMP"] . "' value='Valider'>Valider
                         <input type='radio' name='validation_" . $ligne["NOM_EMP"] . "' value='Refuser'>Refuser</li>";
                }
                echo "</ul>";
                echo "<input type='submit' value='Valider'>";
                echo "</form>";
            } else {
                echo "Aucun résultat trouvé.";
            }
        } else {
            echo "Identifiant non défini.";
        }
        ?>

        <p id="selectedDays" style="display: none;"></p>
    </div>
</body>
</html>
