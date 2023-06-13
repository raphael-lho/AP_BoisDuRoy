<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/css/style.css" />
    <title>mot de passe oublié?</title>
</head>

<body class="fondConnexion">
    <form class="FontForm" method="POST">
        <div class="form">
            <?php


            // affichage des champs pour le changement de mot de passe
            echo "<p type='Ancien mot de passe:'><input type='text' name='AncienMDP'></p>";
            echo "<p type='Nouveau mot de passe:'><input type='text' name='NouveauMDP'></p>";
            echo "<a class='link' href='../index.php'>Se connecter</a><br>";
            echo "<button type='submit' name='SeConnecter'>Se connecter</button>";



            // Connexion à la base de données
            $serveur = "localhost";
            $utilisateur = "mpele";
            $mot_de_passe = "sn";
            $base_de_donnees = "bdd_bois_du_roy";

            $connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

            // Vérifie si la connexion a réussi
            if (!$connexion) {
                die("La connexion à la base de données a échoué : " . mysqli_connect_error());
            }




            // récupération des informations
            if (isset($_POST['identifiant']) && isset($_POST['mot_de_passe'])) {
                $matricule = $_SESSION['matricule'];
                $mdp = $_SESSION['mdp'];

                echo $matricule . $mdp;



                // récupération des champs de formulaire pour le changement de mot de passe
                if (isset($_POST['AncienMDP']) && isset($_POST['NouveauMDP'])) {
                    $AncienMDP = $_POST['AncienMDP'];
                    $NouveauMDP = $_POST['NouveauMDP'];

                    if (isset($_POST['SeConnecter'])) {
                        // vérifier si l'ancien mot de passe est correct
                        $sql = "SELECT `MDP_EMPLOYE` FROM `employe` WHERE `MATRICULE_EMP` = ?";
                        $stmt = $connexion->prepare($sql);
                        $stmt->bind_param("s", $matricule);
                        $stmt->execute();
                        $result = $stmt->get_result();
                        $row = $result->fetch_assoc();
                        $ancienMdpBDD = $row['MDP_EMPLOYE'];

                        if ($ancienMdpBDD == $AncienMDP && $AncienMDP != $NouveauMDP) {
                            // mettre à jour le mot de passe
                            $sql = "UPDATE `employe` SET `MDP_EMPLOYE` = ? WHERE employe.`MATRICULE_EMP` = ?";
                            $stmt = $connexion->prepare($sql);
                            $stmt->bind_param("ss", $NouveauMDP, $matricule);
                            $stmt->execute();

                            header('Location: ../index.php');
                        } else {
                            // afficher un message d'erreur
                            echo "L'ancien mot de passe est incorrect ou le nouveau mot de passe est identique à l'ancien.";
                        }
                    }
                }
            }

            ?>
        </div>
    </form>
</body>

</html>