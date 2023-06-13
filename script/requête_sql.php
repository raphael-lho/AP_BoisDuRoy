<?php
session_start();



// Paramètres de connexion à la base de données
$serveur = "localhost";
$utilisateur = "mpele";
$mot_de_passe = "sn";
$base_de_donnees = "bdd_bois_du_roy";

// Connexion à la base de données via PDO
try {
    $dbh = new PDO("mysql:host=$serveur;dbname=$base_de_donnees", $utilisateur, $mot_de_passe);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(array("error" => "Erreur de connexion à la base de données: " . $e->getMessage()));
    die();
}

// Exécution de la commande SQL pour obtenir le nombre de REF_CONGE
try {
    $stmt_count = $dbh->prepare("SELECT COUNT(REF_CONGE) AS Nombre_de_REF_CONGE FROM conge WHERE REF_CONGE IS NOT NULL");
    $stmt_count->execute();
    $result_count = $stmt_count->fetch(PDO::FETCH_ASSOC);
    $nombre_de_ref_conge = $result_count['Nombre_de_REF_CONGE'] + 1;
} catch (PDOException $e) {
    echo json_encode(array("error" => "Erreur lors de l'exécution de la commande SQL : " . $e->getMessage()));
    die();
}

// Préparer la requête SQL avec des paramètres
$stmt = $dbh->prepare("INSERT INTO conge (REF_CONGE, MATRICULE_EMP, DATE_DEBUT, DATE_FIN, NB_JOURS_RTT, NB_JOURS_CP) VALUES (:ref_conge, :matricule_emp, :date_debut, :date_fin, :nb_jours_rtt, :nb_jours_cp)");

// Récupérer le matricule de l'employé connecté
$stmt_matricule_emp = $dbh->prepare("SELECT MATRICULE_EMP FROM EMPLOYE WHERE MATRICULE_EMP = :matricule_emp");
$stmt_matricule_emp->bindParam(':matricule_emp', $_SESSION["matricule"]);
$stmt_matricule_emp->execute();
$matricule_emp = $stmt_matricule_emp->fetchColumn();

// Liée les valeurs à des paramètres
$ref_conge = $nombre_de_ref_conge;
$date_debut = isset($dtDebut) ? $dtDebut : null;
$date_fin = isset($dtFin) ? $dtFin : null;
$nb_jours_rtt = '[value-5]';
$nb_jours_cp = '[value-6]';

$stmt->bindParam(':ref_conge', $ref_conge);
$stmt->bindParam(':matricule_emp', $matricule_emp);
$stmt->bindParam(':date_debut', $date_debut);
$stmt->bindParam(':date_fin', $date_fin);
$stmt->bindParam(':nb_jours_rtt', $nb_jours_rtt);
$stmt->bindParam(':nb_jours_cp', $nb_jours_cp);

// Exécution de la commande SQL
try {
    $stmt->execute();
    $nombre_de_lignes_ajoutees = $stmt->rowCount();
    echo json_encode(array("Nombre_de_lignes_ajoutees" => $nombre_de_lignes_ajoutees));
} catch (PDOException $e) {
    echo json_encode(array("error" => "Erreur lors de l'exécution de la commande SQL : " . $e->getMessage()));
    die();
}
?>
