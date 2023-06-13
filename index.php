<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style/css/style.css" />
    <title>Page de connexion</title>
</head>

<body class="fondConnexion">



        <form class="FontForm" method="POST">
            <div class="form">

                <?php
                /*Partie DRH*/
                echo "<form method='post'>";
                echo "<p type='Identifiant :'><input type='text' name='identifiant'></p>";
                echo "<p type='mot de passe :'><input type='text' name='mot_de_passe'></p><br>";
                echo "<a href='./page/MtDePasseOublié.php'>Mot de passe oublié ?</a><br><br><br><br><br><br>";
                echo "<button type='submit' name='connexion'>Connexion</button>";
                echo "</form>";

                // Vérifie si le bouton "connexion" a été cliqué
                echo "<a method='post' action='./page/MtDePasseOublié.php'";
                if (isset($_POST['connexion'])) {
                    // Récupère l'identifiant de connexion
                    $matricule = $_POST['identifiant'];
                    $mdp = $_POST['mot_de_passe'];
                    echo "</a>";
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



                    // Prépare la requête SQL
                    $sql = "SELECT MATRICULE_EMP FROM employe WHERE REF_FONCTION = 3 OR REF_FONCTION = 4 OR REF_FONCTION = 5 OR REF_FONCTION = 6 OR REF_FONCTION = 7";
                    $stmt = $connexion->prepare($sql);

                    $sql2 = "SELECT MATRICULE_EMP FROM employe WHERE REF_FONCTION = 1 OR REF_FONCTION = 2";
                    $stmt2 = $connexion->prepare($sql2);

                    $sql3 = "SELECT MDP_EMPLOYE FROM employe WHERE MATRICULE_EMP = ? AND MDP_EMPLOYE NOT LIKE '%mdp%'";
                    $stmt3 = $connexion->prepare($sql3);
                    $stmt3->bind_param("s", $matricule);

                    $sql4 = "SELECT MDP_EMPLOYE FROM employe WHERE MATRICULE_EMP = ? AND MDP_EMPLOYE LIKE '%mdp%'";
                    $stmt4 = $connexion->prepare($sql4);
                    $stmt4->bind_param("s", $matricule);


                    $sql5 = "SELECT MDP_EMPLOYE FROM employe WHERE MATRICULE_EMP = ?";
                    $stmt5 = $connexion->prepare($sql5);
                    $stmt5->bind_param("s", $matricule);


                    // Exécute la requête SQL et stocke les résultats
                    $stmt->execute();
                    $result = $stmt->get_result();

                    $stmt2->execute();
                    $result2 = $stmt2->get_result();

                    $stmt3->execute();
                    $result3 = $stmt3->get_result();

                    $stmt4->execute();
                    $result4 = $stmt4->get_result();

                    $stmt5->execute();
                    $result5 = $stmt5->get_result();


                    // Vérifie si l'identifiant existe dans la base de données
                    $found = false;
                    while ($row = $result->fetch_assoc()) {
                        if ($row['MATRICULE_EMP'] == $matricule) {
                            $found = true;
                            break;
                        }
                    }


                    $found2 = false;
                    while ($row = $result2->fetch_assoc()) {
                        if ($row['MATRICULE_EMP'] == $matricule) {
                            $found2 = true;
                            break;
                        }
                    }


                    $found3 = false;
                    while ($row = $result3->fetch_assoc()) {
                        if ($row['MDP_EMPLOYE'] == $mdp) {
                            $found3 = true;
                            break;
                        }
                    }


                    $found4 = false;
                    while ($row = $result4->fetch_assoc()) {
                        if ($row['MDP_EMPLOYE'] == $mdp) {
                            $found4 = true;
                            break;
                        }
                    }



                    // Affiche le résultat de la vérification
                    if ($found) {
                        echo "L'identifiant " . $matricule . " a été trouvé .";
                        if ($mdp = "") {
                            header('Location: index.php');
                            exit;
                        } else {
                            if ($mdp == $result5) {
                                echo "<a style='color: red;'>le mot de passe est incorrect ! </a>";
                            } else {
                                if ($found4) {
                                    session_start();

                                    $_SESSION['matricule'] = $_POST['identifiant'];
                                    $_SESSION['mdp'] = $_POST['mot_de_passe'];

                                    header('Location: ./page/MtDePasseOublié.php');
                                    exit;
                                } else {
                                    if ($found3) {
                                        header('Location: ./page/PageConnectéEmployé.php');
                                        exit;
                                    }
                                }
                            }
                        }
                    } else {
                        if ($found2) {
                            echo "L'identifiant " . $matricule . " a été trouvé .";
                            if ($mdp = "") {
                                header('Location: index.php');
                                exit;
                            } else {
                                if ($mdp == $result5) {
                                    echo "<a style='color: red;'>le mot de passe est incorrect ! </a>";
                                } else {
                                    if ($found4) {
                                        session_start();

                                        $_SESSION['matricule'] = $_POST['identifiant'];
                                        $_SESSION['mdp'] = $_POST['mot_de_passe'];


                                        header('Location: ./page/MtDePasseOublié.php');
                                        exit;
                                    } else {
                                        if ($found3) {
                                            header('Location: ./page/PageConnectéDRH.php');
                                            exit;
                                        }
                                    }
                                }
                            }
                        } else {
                            echo "<a style='color: red;'>L'identifiant " . $matricule . " n'a pas été trouvé dans la base de données</a>";
                        }
                    }

                    // Libère les ressources
                    $result->free();
                    $stmt->close();

                    $result2->free();
                    $stmt2->close();

                    $result3->free();
                    $stmt3->close();

                    $result4->free();
                    $stmt4->close();
                }

                ?>
            </div>
        </form>
    </form>
</body>

</html>