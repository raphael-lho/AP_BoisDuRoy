<?php

session_start();


// Définir les informations de connexion à la base de données
$serveur = "localhost";
$utilisateur = "mpele";
$mot_de_passe = "sn";
$base_de_donnees = "bdd_bois_du_roy";

// Connexion à la base de données
$connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

// Vérifier si la connexion a réussi
if (!$connexion) {
  die("La connexion à la base de données a échoué : " . mysqli_connect_error());
} else {
  echo "<script> console.log(La connexion à la base de données a réussi !)</script>";
}




// Liste des jours fériés pour l'année en cours
$holidays = array(
  '01-01',
  '04-18',
  '05-01',
  '05-08',
  '05-26',
  '07-14',
  '08-15',
  '11-01',
  '11-11',
  '12-25'
);

// Liste des week-ends (samedi et dimanche)
$weekends = array(
  'Sam',
  'Dim'
);

// Ajout des samedis et dimanches dans la liste des jours qui ne peuvent pas être sélectionnés
for ($i = 1; $i <= 31; $i++) {
  $date = date('m-d-Y', strtotime(date('Y') . '-' . date('m') . '-' . $i));
  $dayOfWeek = date('D', strtotime($date));
  if ($dayOfWeek == 'Sam' || $dayOfWeek == 'Dim') {
    $weekends[] = $dayOfWeek;
  }
}

// Récupération du mois et de l'année actuels, ou des nouvelles valeurs s'ils ont été passés en paramètres GET
$month = date("m");
$year = date("Y");

if (isset($_GET['month']) && isset($_GET['year'])) {
  $month = $_GET['month'];
  $year = $_GET['year'];
}

// Calcul du premier jour du mois, du nombre total de jours dans le mois et du jour de la semaine correspondant au premier jour
$first_day = mktime(0, 0, 0, $month, 1, $year);
$total_days = date("t", $first_day);
$weekday = date("w", $first_day);

// Définition des noms de jours de la semaine et du nom du mois
$weekday_names = array("Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim");
$month_name = date("F", $first_day);

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style/css/style.css" />
  <title>Connecté</title>
</head>

<body class='fontConnecté'>
  <?php


  $identifiant = "";
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST["identifiant"])) {
      $_SESSION["identifiant"] = $_POST["identifiant"];

      echo $_SESSION["identifiant"];
    }
    if (isset($_POST["mot_de_passe"])) {
      $mot_de_passe = $_POST["mot_de_passe"];
    }
  }
  ?>

  <matricule class="matricule">
    <?php

    // Récupérer l'identifiant de connexion depuis la variable de session
    if (isset($_SESSION['matricule'])) {
      $identifiant = $_SESSION['matricule'];
      echo $identifiant;
    } else {
      // Si la clé 'matricule' n'existe pas dans la variable de session, afficher un message d'erreur
      echo "Identifiant non défini";
    }
    ?>
  </matricule>



  <a href="../index.php" class="retour"></a>
  <?php if (isset($_SESSION["identifiant"])) { ?>
    <a class="Nom_identifiant">Dubois Karine
      <?php

      $serveur = "localhost";
      $utilisateur = "mpele";
      $mot_de_passe = "sn";
      $base_de_donnees = "bdd_bois_du_roy";

      // Connexion à la base de données
      $connexion = mysqli_connect($serveur, $utilisateur, $mot_de_passe, $base_de_donnees);

      // Vérifier si la connexion a réussi
      if (!$connexion) {
        die("La connexion à la base de données a échoué : " . mysqli_connect_error());
      } else {
        echo "<script> console.log(La connexion à la base de données a réussi !)</script>";
      }

      if (isset($_SESSION["identifiant"])) {

        $identifiant = $_SESSION['identifiant'];

        $sql = "SELECT NOM_EMP, PRENOM_EMP FROM employe WHERE MATRICULE_EMP = ?";
        $stmt = $connexion->prepare($sql);
        $stmt->bind_param("s", $identifiant);
        $stmt->execute();
        $result = $stmt->get_result();

        // Afficher les résultats
        while ($row = $result->fetch_assoc()) {
          echo $row["NOM_EMP"] . " " . $row["PRENOM_EMP"];
        }
      } else {
        echo "Le champ matricule n'a pas été renseigné";
      }

      ?></a>
  <?php } ?>
  <img class="logo" src="../style/img/logo_entreprise.png" alt="Logo HubSpot" />


  <form method="POST" action="#">
    <div class="typeCongés">

      <label for="radio">Sélectionner le type de congés :</label><br><br>
      <label>CP
        <input type="radio" name="radio1" value="CP" onchange="toggleCalendar()" <?php if (isset($_GET['radio1']) && $_GET['radio1'] == 'radio1') {
                                                                                    echo 'checked';
                                                                                  } ?>>
      </label>
      <label>RTT
        <input type="radio" name="radio1" value="RTT" onchange="toggleCalendar()" <?php if (isset($_GET['radio1']) && $_GET['radio1'] == 'radio2') {
                                                                                    echo 'checked';
                                                                                  } ?>>
      </label>
      <label>1/2 RTT
        <input type="radio" name="radio1" value="1/2 RTT" onchange="toggleCalendar()" <?php if (isset($_GET['radio1']) && $_GET['radio1'] == 'radio3') {
                                                                                      } ?>>
      </label>

    </div><br>
    <div>
      <calendrier id="calendrier" class="contourCalendrier<?php if (!isset($_GET['radio1'])) {
                                                            echo ' disabled';
                                                          } ?>" <?php if (!isset($_GET['radio1'])) {
                                                                  echo 'disabled';
                                                                } ?>>
        <?php
        $month = date("m");
        $year = date("Y");
        if (isset($_GET['month']) && isset($_GET['year'])) {
          $month = $_GET['month'];
          $year = $_GET['year'];
        }
        $first_day = mktime(0, 0, 0, $month, 1, $year);
        $total_days = date("t", $first_day);
        $weekday = date("w", $first_day);
        $weekday_names = array("Dim", "Lun", "Mar", "Mer", "Jeu", "Ven", "Sam");
        $month_name = date("F", $first_day);
        echo "<h2 class='contourCalendrier'>$month_name $year</h2>";

        $previous_month = date("m", strtotime("-1 month", $first_day));
        $previous_year = date("Y", strtotime("-1 month", $first_day));
        $next_month = date("m", strtotime("+1 month", $first_day));
        $next_year = date("Y", strtotime("+1 month", $first_day));
        echo "<div>";
        echo "<a href='?month=$previous_month&year=$previous_year'>Précédent</a>";
        echo "<a href='?month=$next_month&year=$next_year'>Suivant</a>";
        echo "</div>";

        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        foreach ($weekday_names as $day) {
          echo "<th>$day</th>";
        }
        echo "</tr>";
        echo "</thead>";
        echo "<tbody id='calendar-body'>";
        $day_count = 1;
        $row_count = 1;
        while ($day_count <= $total_days) {
          echo "<tr>";
          for ($i = 0; $i < 7; $i++) {
            if ($day_count > $total_days) {
              break;
            }
            if ($row_count == 1 && $i < $weekday) {
              echo "<td></td>";
            } else {
              $day_text = str_pad($day_count, 2, "0", STR_PAD_LEFT);
              $class = in_array($weekday_names[$i], $weekends) || in_array($month . '-' . $day_text, $holidays) ? "disabled" : "";
              echo "<td class='$class'>$day_count</td>";
              $day_count++;
            }
          }
          echo "</tr>";
          $row_count++;
        }

        echo "</tbody>";
        echo "</table>";

        ?>
      </calendrier>
      <button id="addJourCongés" type="button" class="addJourCongés" disabled onclick="sendRequest()">Envoyer ma demande</button>
    </div>
  </form>

  <div id="congesBox">
    <h2>Demande de jours de congés :</h2><br>
    <?php
    // Connexion à la base de données
    $conn = mysqli_connect("localhost", "mpele", "sn", "bdd_bois_du_roy");

    // Vérification de la connexion
    if (!$conn) {
      die("Connexion échouée : " . mysqli_connect_error());
    }

    // Exécution de la requête
    $sql = "SELECT * FROM conge";
    $resultat = mysqli_query($conn, $sql);

    // Traitement des résultats
    if (mysqli_num_rows($resultat) > 0) {
      while ($ligne = mysqli_fetch_assoc($resultat)) {
        echo "ID: " . $ligne["id_conge"] . " - Début: " . $ligne["date_debut"] . " - Fin: " . $ligne["date_fin"] . "<br>";
      }
    } else {
      echo "Aucun résultat trouvé.";
    }

    ?>

    <p id="selectedDays" style="display: none;"></p>
  </div>

  <div class="graphique">
    <h1>Récapitulatif des congés et RTT</h1>
    <p>Il vous reste :</p>
    <p>
      <strong class="cp">
        <?php
        // Vérifier si la variable de session "identifiant" est définie
        if (isset($_SESSION["identifiant"])) {
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "mpele", "sn", "bdd_bois_du_roy");

          // Vérification de la connexion
          if (!$conn) {
            die("Connexion échouée : " . mysqli_connect_error());
          }

          // Exécution de la requête
          $sql = "SELECT NB_CONGE_ACQUIS_CP FROM congeaquis INNER JOIN employe ON congeaquis.MATRICULE_EMP = employe.MATRICULE_EMP WHERE employe.NOM_EMP = '" . $_SESSION["identifiant"] . "';";
          $sql2 = "SELECT NB_CONGE_RESTANT_CP FROM congeaquis INNER JOIN employe ON congeaquis.MATRICULE_EMP = employe.MATRICULE_EMP WHERE employe.NOM_EMP = '" . $_SESSION["identifiant"] . "';";

          $resultat = mysqli_query($conn, $sql);
          $resultat2 = mysqli_query($conn, $sql2);

          // Récupération des données
          $row = mysqli_fetch_array($resultat);
          $row2 = mysqli_fetch_array($resultat2);

          // Vérification si $row est null ou vide
          if (isset($row) && !empty($row) && isset($row2) && !empty($row2)) {
            // Affichage du résultat
            echo $row["NB_CONGE_ACQUIS_CP"] . " congé payé sur " . $row2["NB_CONGE_RESTANT_CP"];
          } else {
            // Traitement de l'absence de données
            echo "Aucun CP trouvé.";
          }
        } else {
          // La variable de session "identifiant" n'est pas définie
          echo "La session a expiré ou la variable de session identifiant n'a pas été initialisée.";
        }
        ?>


      </strong>
    <div class="bar">
      <div class="progress" style="width: 0%;"></div>
    </div>
    </p>
    <p>
      <strong class="rtt">

        <?php
        // Vérifier si la variable de session "identifiant" est définie
        if (isset($_SESSION["identifiant"])) {
          // Connexion à la base de données
          $conn = mysqli_connect("localhost", "mpele", "sn", "bdd_bois_du_roy");

          // Vérification de la connexion
          if (!$conn) {
            die("Connexion échouée : " . mysqli_connect_error());
          }

          // Exécution de la requête
          $sql = "SELECT NB_CONGE_ACQUIS_RTT FROM congeaquis INNER JOIN employe ON congeaquis.MATRICULE_EMP = employe.MATRICULE_EMP WHERE employe.NOM_EMP = '" . $_SESSION["identifiant"] . "';";
          $sql2 = "SELECT NB_CONGE_RESTANT_RTT FROM congeaquis INNER JOIN employe ON congeaquis.MATRICULE_EMP = employe.MATRICULE_EMP WHERE employe.NOM_EMP = '" . $_SESSION["identifiant"] . "';";

          $resultat = mysqli_query($conn, $sql);
          $resultat2 = mysqli_query($conn, $sql2);

          // Récupération des données
          $row = mysqli_fetch_array($resultat);
          $row2 = mysqli_fetch_array($resultat2);

          // Vérification si $row est null ou vide
          if (isset($row) && !empty($row) && isset($row2) && !empty($row2)) {
            // Affichage du résultat
            echo $row["NB_CONGE_ACQUIS_RTT"] . " RTT sur " . $row2["NB_CONGE_RESTANT_RTT"];
          } else {
            // Traitement de l'absence de données
            echo "Aucun RTT trouvé.";
          }
        } else {
          // La variable de session "identifiant" n'est pas définie
          echo "La session a expiré ou la variable de session identifiant n'a pas été initialisée.";
        }
        ?>


      </strong>
    <div class="bar">
      <div class="progress" style="width: 0%;"></div>
    </div>
    </p>
  </div>

  <?php
  // Vérifier si la clé "selectedDays" existe dans le tableau $_POST
  if (array_key_exists('selectedDays', $_POST)) {
    // Récupérer les valeurs stockées dans le localStorage
    $selectedDays = json_decode($_POST['selectedDays'], true);
    if (!empty($selectedDays)) {
      $smallestDate = new DateTime($selectedDays[0]['year'] . '-' . $selectedDays[0]['month'] . '-' . $selectedDays[0]['day']);
      $largestDate = new DateTime($selectedDays[count($selectedDays) - 1]['year'] . '-' . $selectedDays[count($selectedDays) - 1]['month'] . '-' . $selectedDays[count($selectedDays) - 1]['day']);

      // Stocker les dates dans les variables PHP
      $dtDebut = $smallestDate->format('Y-m-d');
      $dtFin = $largestDate->format('Y-m-d');
      $nbJour = count($selectedDays);
    }
  }
  ?>








  <script>
    var selectedCells = [];
    var cells = document.getElementsByTagName("td");
    var currentMonth = new Date().getMonth();
    var monthNames = ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"];

    localStorage.clear();
    storeSelectedDays();
    clearSelectedDays();

    for (var i = 0; i < cells.length; i++) {
      cells[i].addEventListener("click", function() {
        var clickedMonth = new Date().getMonth();
        if (clickedMonth == currentMonth && !this.classList.contains("disabled")) {
          if (!this.classList.contains("selected")) {
            selectedCells.push(this);
            this.classList.add("selected");
          } else {
            var index = selectedCells.indexOf(this);
            selectedCells.splice(index, 1);
            this.classList.remove("selected");
          }
          storeSelectedDays();
        }
      });
    }




    function activerBouton() {
      var radioButtons = document.getElementsByName("radio1");
      var envoyerButton = document.querySelector(".addJourCongés");
      for (var i = 0; i < radioButtons.length; i++) {
        if (radioButtons[i].checked) {
          envoyerButton.removeAttribute("disabled");
          return;
        }
      }
      envoyerButton.setAttribute("disabled", "disabled");
    }

    var radioButtons = document.getElementsByName("radio1");
    for (var i = 0; i < radioButtons.length; i++) {
      radioButtons[i].addEventListener("click", activerBouton);
    }


    function updateSelectedDays() {
      var selectedDays = selectedCells.length;
      var totalDays = 30;
      var remainingDays = totalDays - selectedDays;

      var cpProgress = Math.round(selectedDays / totalDays * 100);
      var rttProgress = Math.round(selectedDays / 10 * 100);

      var cpBar = document.querySelector(".cp .progress");
      cpBar.style.width = cpProgress + "%";
      cpBar.textContent = cpProgress + "%";

      var rttBar = document.querySelector(".rtt .progress");
      rttBar.style.width = rttProgress + "%";
      rttBar.textContent = rttProgress + "%";

      var cpText = document.querySelector(".cp strong");
      cpText.textContent = remainingDays + " jours de congés payés sur " + totalDays;

      var rttText = document.querySelector(".rtt strong");
      rttText.textContent = (10 - selectedDays) + " RTT sur 10";
    }


    function selectDay() {
      var selectedDate = new Date(new Date().getFullYear(), currentMonth, this.textContent);
      if (!this.classList.contains("disabled")) {
        if (!this.classList.contains("selected")) {
          selectedCells.push(this);
          this.classList.add("selected");
          this.classList.add("green"); // ajout de la classe "green" pour mettre en vert les jours sélectionnés
        } else {
          var index = selectedCells.indexOf(this);
          selectedCells.splice(index, 1);
          this.classList.remove("selected");
          this.classList.remove("green"); // suppression de la classe "green" pour enlever la couleur verte des jours déselectionnés
        }
        updateSelectedDays();
      }
    }





    function toggleCalendar() {
      var radioButtons = document.getElementsByName("radio1");
      var calendar = document.querySelector("#calendrier");
      var cells = document.querySelectorAll("#calendrier .cell");
      var holidays = ["1-1", "5-1", "1-5", "8-5", "14-7", "15-8", "1-11", "11-11", "25-12"];
      var weekends = ["Sam", "Dim"];
      var currentMonth = new Date().getMonth();
      var isRadioSelected = false;


      for (var i = 0; i < radioButtons.length; i++) {
        if (radioButtons[i].checked) {
          isRadioSelected = true;
          break;
        }
      }

      if (isRadioSelected) {
        // Afficher le calendrier
        calendar.classList.remove("disabled");
        calendar.style.opacity = "1";
        // Activer/désactiver les jours fériés et les week-ends
        for (var i = 0; i < cells.length; i++) {
          var dayText = cells[i].textContent;
          var selectedDate = new Date(new Date().getFullYear(), currentMonth, dayText);
          if (radioButtons[0].checked && (weekends.includes(cells[i].textContent) || holidays.includes((currentMonth + 1) + '-' + dayText))) {
            cells[i].classList.add("disabled");
            cells[i].removeEventListener("click", selectDay);
          } else if ((radioButtons[1].checked || radioButtons[2].checked) && weekends.includes(cells[i].textContent)) {
            cells[i].classList.add("disabled");
            cells[i].removeEventListener("click", selectDay);
          } else {
            cells[i].classList.remove("disabled");
            cells[i].addEventListener("click", selectDay);
          }
        }
        // Activer les liens de changement de mois
        var prevLink = document.getElementById('prev');
        var nextLink = document.getElementById('next');
        prevLink.href = `?month=${currentMonth}&year=${new Date().getFullYear()}`;
        nextLink.href = `?month=${currentMonth + 2}&year=${new Date().getFullYear()}`;
        activerBouton();
      } else {
        // Masquer le calendrier et désactiver tous les jours
        calendar.classList.add("disabled");
        calendar.style.opacity = "0.5";
        for (var i = 0; i < cells.length; i++) {
          cells[i].classList.add("disabled");
          cells[i].removeEventListener("click", selectDay);
        }
        // Désactiver les liens de changement de mois
        var prevLink = document.getElementById('prev');
        var nextLink = document.getElementById('next');
        prevLink.href = 'javascript:void(0)';
        nextLink.href = 'javascript:void(0)';
        envoyerButton.setAttribute("disabled", "disabled");
      }
    }










    function storeSelectedDays() {
      var selectedDays = [];
      for (var i = 0; i < selectedCells.length; i++) {
        var dayText = selectedCells[i].textContent;
        var selectedDate = new Date(new Date().getFullYear(), currentMonth, dayText);
        selectedDays.push({
          day: dayText,
          month: currentMonth + 1,
          year: new Date().getFullYear()
        });
      }

      localStorage.setItem("selectedDays", JSON.stringify(selectedDays));

      var selectedDaysText = "";
      selectedDays.forEach(function(day) {
        selectedDaysText += day.day + "/" + day.month + "/" + day.year + "<br><br>";
      });

      document.getElementById("selectedDays").innerHTML = selectedDaysText;
    }

    function loadSelectedDays() {
      var selectedDays = JSON.parse(localStorage.getItem("selectedDays_" + currentMonth));
      if (selectedDays !== null) {
        for (var i = 0; i < cells.length; i++) {
          var dayText = cells[i].textContent;
          var selectedDate = new Date(new Date().getFullYear(), currentMonth,
            dayText);
          for (var j = 0; j < selectedDays.length; j++) {
            if (selectedDays[j].toLocaleDateString() === selectedDate
              .toLocaleDateString()) {
              cells[i].classList.add("selected");
              selectedCells.push(cells[i]);
              break;
            }
          }
        }
      }
    }

    function clearSelectedDays() {
      var selectedDays = JSON.parse(localStorage.getItem("selectedDays_" + currentMonth));
      if (selectedDays !== null) {
        for (var i = 0; i < selectedCells.length; i++) {
          selectedCells[i].classList.remove("selected");
        }
        selectedCells = [];
        localStorage.removeItem("selectedDays_" +
          currentMonth);
      }
    }

    document.getElementById("prev").addEventListener("click", function() {
      currentMonth--;
      if (currentMonth < 0) currentMonth = 11;
      clearSelectedDays();
    });

    document.getElementById("next").addEventListener("click", function() {
      currentMonth++;
      if (currentMonth > 11) currentMonth = 0;
      clearSelectedDays();
    });

    window.addEventListener("load", function() {
      loadSelectedDays();
    });

    const congesBox = document.querySelector('#congesBox');
    const selectedDays = document.querySelector('#selectedDays');
    const addJourCongesBtn = document.querySelector('#addJourCongés');
    addJourCongesBtn.addEventListener('click', () => {
      // ajouté: condition pour afficher les jours de congé sélectionnés uniquement lorsque le bouton est cliqué
      if (selectedCells.length > 0) {
        const selectedDaysData = selectedCells.map(cell => {
          return {
            day: cell.textContent,
            month: currentMonth + 1,
            year: new Date().getFullYear()
          };
        });
        const selectedDaysText = selectedDaysData.map(day => {
          return day.day + '/' + day.month + '/' + day.year;
        }).join('<br><br>');
        selectedDays.innerHTML = selectedDaysText;
        selectedDays.style.display = 'block';
      }
    });



    function sendRequest() {
      var selectedCells = document.querySelectorAll("#calendar-body .selected");
      for (var i = 0; i < selectedCells.length; i++) {
        selectedCells[i].classList.add("green");
      }
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.open("GET", "../script/requête_sql.php", true);
      xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
          document.getElementById("congesBox").innerHTML = xmlhttp.responseText;
        }
      };
      xmlhttp.send();
    }


    activerBouton();
  </script>

</body>

</html>