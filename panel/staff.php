<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <title>STAFF</title>

</head>

<body>
  <div class="jumbotron">
<div class="container text-center">
  <h1>Pannello di controllo</h1>
  <p>Staff</p>
</div>
</div>


<nav class="navbar navbar-inverse">
<div class="container-fluid">
  <div class="navbar-header">
    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
      <span class="icon-bar"></span>
    </button>
    <a class="navbar-brand" href="http://www.vestagames.it/">Home</a>
  </div>
  <div class="collapse navbar-collapse" id="myNavbar">

  </div>
  </div>
  </nav>

  <?php
  session_start();
  if (!$_SESSION['admin']) //se non sei riconosciuto come admin torni indietro
  {
    header("location:http://www.vestagames.it/login.php");
  }

  elseif (isset($_POST["who"])) //se viene cercato un commesso da cancellare nel database
  {
    echo "<div class='container'>";
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["who"];
    echo '<div class="container">';

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT * FROM Commesso WHERE MATCH(Nome, Cognome) AGAINST(+? IN BOOLEAN MODE) OR codice_commesso=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("ss",$who,$who );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codicecommesso,$nome,$cognome,$nprenotazioni,$nassicurazioni);

    $stmt->store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";


      echo "
      <table class=' table table-hover'>"; // TABELLA
      echo "<thead><tr> <th>  Codice Commesso </th> <th> Nome </th> <th> Cognome </th> <th> Numero Prenotazioni </th> <th> Numero Assicurazioni </th> </tr> </thead> <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $codicecommesso . "</td><td>" . $nome . "</td><td>" . $cognome ."</td><td>" .$nprenotazioni."</td><td>" .$nassicurazioni. "</td></tr>";
      }
      echo "</tbody></table>";
      if ($stmt->num_rows==1) { // se le tuple sono esattamente una chiedo se vogliono cancellare
        $_SESSION['matricola']=$codicecommesso;
        echo "<h3> <label>Vuoi cancellare ".$nome." ".$cognome." con codice ".$codicecommesso."?</label></h3>
        <form action='confirmed.php' method='post'>
        <input type='submit' class='btn btn-primary' name='yes' value='Conferma'>
        </form>
        <form action='staff.php' method='post'>
        <input type='submit' class='btn btn-default' name='no' value='Annulla'>
        </form>
        ";
      }
      else {
        echo "Ci sono più righe, cerca meglio";
      }
    }
    else // NO ROWS IN RESULT
    {
      echo "<strong> A quanto pare non esiste nel registro, prova a cercare altro!</strong>";
    }
    echo '</div>';
  }



  elseif (isset($_POST['classifica'])) { // classifica dei commessi in base alla quantità di prenotazioni e assicurazioni effettuate, sommate in un punteggio
    echo '<div class="container">';

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $stmt = $link -> prepare("SELECT * FROM Commesso order by (nprenotazioni+nassicurazioni) desc");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice, $nome,$cognome,$nprenotazioni,$nassicurazioni);
    $stmt -> store_result();
    echo "
    <table class=' table table-hover'>";
    echo "<thead><tr><th> Posizione </th> <th> Punteggio </th> <th> Codice </th> <th> Nome </th> <th> Cognome</th></tr></thead> <tbody>";
    $count =1;
    while ($stmt->fetch())
    {

      $punteggio= $nassicurazioni + $nprenotazioni;
      echo "<tr><td>" .$count ."</td><td>" . $punteggio .  "</td><td>" . $codice . "</td><td>" . $nome ."</td><td>" .$cognome."</td></tr>";
      $count =$count +1;

    }
    echo "</tbody></table>
    <form  action='staff.php' method='post'>
    <input type='submit' class='btn btn-default' name='annulla' value='Indietro' >
    </form>
    </div>";

  }


  else { // pagina predefinita
    echo "<div class='container'>";
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $querysql='SELECT * FROM Commesso';
    $result=$link->query($querysql);
    echo "
    <table class=' table table-hover'>";
    echo "<thead><tr> <th> Codice Commesso </th> <th> Nome </th> <th> Cognome </th> <th> Prenotazioni</th> <th> Assicurazioni </tr></thead> <tbody>";
    /* Fetch the value */
    while($row = $result->fetch_assoc()) { //scrivo i valori
      echo "<tr>";
      foreach ($row as $key => $value) {
        echo "<td>". $value.'</td> ';
      }
      echo "</tr>";
    }
    echo "</tbody></table>";

  }

    ?>


    <?php
    echo "<div class='container'>";
    if (isset($_POST['delete'])) // clicco sul bottone per cancellare
    {
      echo "<div class='container'>";
      echo " Chi vuoi cancellare? <br>
      <form  action='staff.php' method='post'>
      <div class='form-group'>
      <input type='search' name='who' size='32' maxlenght='40' >
      <input type='submit' name='cancella' value='Conferma' >
      </form>
      <form  action='staff.php' method='post'>
      <input type='submit' class='btn btn-default' name='annulla' value='Annulla' >
      </div>
      </form>";



    }
    elseif (isset($_POST['aggiungere'])) // clicco sul bottone per aggiungere
    {
      echo '<form action="confirmed.php" method="post">
      <div class="form-group">
      Codice Commesso <input type="text" name="codice_commesso"><br>
      Nome <input type="text" name="nome"> <br>
      Cognome <input type="text" name="cognome"><br>
      Numero Prenotazioni  <input type="text" name="numprenotazioni"><br>
      Numero Assicurazioni <input type="text" name="numvendite"><br>
      <input type="submit" name="add" value="Conferma">
      </form><form action="staff.php" method="post">
      <input type="submit" class="btn btn-default" name="annulla" value="Annulla">
      </div>
      </form>';

    }


    else { //non ho cliccato su nulla, mostro i bottoni
      echo '</body>
      <form  action="staff.php" method="post">
      <div class="form-group">
      <input type="submit" class="btn btn-primary" name="delete" value="Cancellare un commesso dal database">
      <input type="submit" class="btn btn-primary" name="aggiungere" value="Aggiungere un commesso al database">
      <input type="submit" class="btn btn-primary" name="classifica" value="Controllare la classifica delle prenotazioni e vendite">
      </div>
      </form>';
    }
    echo "</div></div>";

  session_write_close();
  ?>
  <br> <br>
  <div class="container">


  <form action="index.php" method="post">
    <input type="submit"class="btn btn-default" name="back" value="Torna al menu principale">

  </form>
</div>
</body>
</html>
