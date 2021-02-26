<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta charset="utf-8">
<head>
  <title>INVENTARIO</title>

</head>


<body>
  <div class="jumbotron">
    <div class="container text-center">
      <h1>Pannello di controllo</h1>
      <p>Inventario</p>
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
  <div class="container">


    <form action="index.php" method="post">
      <input type="submit" class="btn btn-default" name="back" value="Torna al menu principale">

    </form>
  </div>
  <br>
  <?php
  session_start();
  if (isset($_POST['delete'])) //clicco il bottone cancellare un prodotto
  {
    echo '<div class="container">';
    echo " Che gioco vuoi cancellare? <br>
    <form  action='inventario.php' method='post'>
    <input type='search' name='who' size='32' maxlenght='40' >
    <input type='submit' name='cancella' value='Conferma' >
    </form>
    <form  action='inventario.php' method='post'>
    <input type='submit' class='btn btn-default' name='annulla' value='Annulla' >
    </form> </div>";



  }
  elseif (isset($_POST['aggiungere'])) //clicco il bottone aggiungere un prodotto
  {
    echo '<div class="container">';
    echo '<form action="confirmed.php" method="post" enctype="multipart/form-data">
    Titolo<input type="text" name="titolo"><br>
    Genere <input type="text" name="genere"> <br>
    Casa Produttrice <input type="text" name="casaproducer"><br>
    Quantità  <input type="text" name="quantita"><br>
    Data uscita (yyyy-mm-dd)  <input type="date" name="uscita"><br>
    Prezzo Nuovo  <input type="number" step="0.01" name="nuovo"><br>
    Prezzo Usato  <input type="number" step="0.01" name="usato"><br>
    <label>Carica copertina:</label>
    <input type="file" name="fileToUpload" id="fileToUpload">
    <input type="submit" name="addprodotto" value="Conferma">



    </form><form action="inventario.php" method="post">
    <input type="submit" name="annulla" value="Annulla">

    </form> </div>';

  }
  elseif (isset($_POST['cercare'])) //clicco sul bottone cercare un prodotto
  {
    echo '<div class="container">';
    echo " Cerca quello che ti serve <br>
    <form  action='inventario.php' method='post'>
    <input type='search' name='search' size='32' maxlenght='40' >
    <input type='submit' name='cancella' value='Conferma' >
    </form>
    <form  action='inventario.php' method='post'>
    <input type='submit' name='annulla' value='Annulla' >
    </form> </div>";
  }




  else { // non ho cliccato su nessun bottone
    echo '<div class="container">
    <div class="form-group">
    <form  action="inventario.php" method="post">
    <input type="submit" class="btn btn-primary" name="delete" value="Cancellare un gioco dal database">
    <input type="submit" class="btn btn-primary" name="aggiungere" value="Aggiungere un gioco al database">
    <input type="submit" class="btn btn-primary" name="nonuscito" value="Visualizzare i giochi in uscita">
    <input type="submit" class="btn btn-primary" name="uscito" value="Visualizzare i giochi già usciti">
    <input type="submit" class="btn btn-primary" name="cercare" value="Cercare un prodotto">

    </form>
    </div>
    </div>';
  }


  ?>
  <br> <br>
  <?php
  // string(10) "2015-07-07"
  function convertDate($DATA) {

    if ( $DATA != "" ) {
      if ( stripos( $DATA, "-" ) > -1 ) {     //Codifica
        list( $ANNO, $MESE, $GIOR ) = explode( "-", $DATA );
        if ( strlen( trim( $MESE ) ) == 1 ) {   $MESE = "0" . trim( $MESE ); } //Aggiunge zero
        if ( strlen( trim( $GIOR ) ) == 1 ) {   $GIOR = "0" . trim( $GIOR ); } //Aggiunge zero
        return $ANNO . $MESE . $GIOR;
      } else { //decodifica
        if ( strlen( $DATA ) == 8 ) {
          $ANNO = substr( $DATA, 0, 4 );
          $MESE = substr( $DATA, 4, 2 );
          $GIOR = substr( $DATA, 6, 2 );
          if ( strlen( $MESE ) == 1 ) {   $MESE = "0" . $MESE; } //Aggiunge zero
          if ( strlen( $GIOR ) == 1 ) {   $GIOR = "0" . $GIOR; } //Aggiunge zero
          return $GIOR . "-" . $MESE . "-" . $ANNO;
        } else {
          return "";
        }
      }
    } else { return ""; }
  }

  if (!$_SESSION['admin']) //se non sei riconosciuto come admin torni indietro
  {
    header("location:http://www.vestagames.it/login.php");
  }

  elseif (isset($_POST["who"])) //se viene cercato un prodotto da cancellare nel database
  {
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["who"];

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT * FROM Prodotto WHERE MATCH(Titolo, Genere, casa_producer) AGAINST(+? IN BOOLEAN MODE) OR codice_prodotto=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("ss",$who, $who );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice,$titolo,$genere,$casaproducer,$quantita, $data, $nuovo, $usato,$img);

    $stmt->store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "
      <table class=' table table-hover'>"; //TABELLE
      echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> <th> Immagine </th></tr> </thead> <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $codice . "</td><td>" . $titolo . "</td><td>" . $genere ."</td><td>" . $casaproducer ."</td><td>" . $quantita ."</td><td>" . convertDate($data) ."</td><td>" . $nuovo ."</td><td>" .$usato. "</td><td>".$img. "</td></tr>";
      }
      echo "</tbody></table>";

      if ($stmt->num_rows==1) { // se le tuple sono esattamente una chiedo se vogliono cancellare
        $_SESSION['codiceprodotto']=$codice;
        echo "<h3>Vuoi cancellare ".$titolo." con codice ".$codice."?</h3>
        <form action='confirmed.php' method='post'>
        <input type='submit' name='yesproduct' value='Conferma'>
        </form>
        <form action='inventario.php' method='post'>
        <input type='submit' name='no' value='Annulla'>
        </form>
        ";
      }
      else {
        echo "Ci sono più righe, cerca meglio
        <form action='inventario.php' method='post'>
        <input type='submit' name='no' value='Indietro'>
        </form>";
      }
    }

    else // NO ROWS IN RESULT
    {
      echo "<strong> A quanto pare non esiste nel registro, prova a cercare altro!</strong>
      <form action='inventario.php' method='post'>
      <input type='submit' name='no' value='Indietro'>
      </form>";
    }
    $stmt-> free_result();
    echo "</div>";
  }
  elseif (isset($_POST["search"])) { //semplice ricerca
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["search"];
    $link->stmt_init();
    $stmt = $link -> prepare("SELECT * FROM Prodotto WHERE MATCH(Titolo, Genere, casa_producer) AGAINST(+? in boolean mode) OR codice_prodotto=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("ss",$who, $who );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice,$titolo,$genere,$casaproducer,$quantita, $data, $nuovo, $usato, $img);
    $stmt -> store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "
      <table class=' table table-hover'>";
      echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> </tr></thead><tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $codice . "</td><td>" . $titolo . "</td><td>" . $genere ."</td><td>" . $casaproducer ."</td><td>" . $quantita ."</td><td>" . convertDate($data) ."</td><td>" . $nuovo ."</td><td>" .$usato. "</td></tr>";
      }
      echo "</tbody></table>";
    }
    else // NO ROWS IN RESULT
    {
      echo "<strong> A quanto pare non esiste nel registro, prova a cercare altro!</strong>
      <form action='inventario.php' method='post'>
      <input type='submit' name='no' value='Indietro'>
      </form>";
    }
    echo '</div>';
  }
  elseif (isset($_POST['nonuscito'])) { //filtra per data uscita
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["search"];
    $oggi=(int)date('Ymd');

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT * FROM Prodotto WHERE data_uscita > ? ");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("i",$oggi );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice,$titolo,$genere,$casaproducer,$quantita, $data, $nuovo, $usato, $img);
    $stmt ->store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "
      <table class=' table table-hover'>";
      echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th><th> Immagine </th> </tr></thead><tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $codice . "</td><td>" . $titolo . "</td><td>" . $genere ."</td><td>" . $casaproducer ."</td><td>" . $quantita ."</td><td>" . convertDate($data) ."</td><td>" . $nuovo ."</td><td>" .$usato. "</td><td>".$img. "</td></tr>";
      }
      echo "</tbody></table>";
      $stmt ->free_result();
    }
  }


  elseif (isset($_POST['uscito'])) {// filtra per data uscita
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["search"];
    $oggi=(int)date('Ymd');

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT * FROM Prodotto WHERE data_uscita < ? ");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("i",$oggi );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice,$titolo,$genere,$casaproducer,$quantita, $data, $nuovo, $usato,$img);
    $stmt ->store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "
      <table class=' table table-hover'>";
      echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th><th> Immagine </th> </tr></thead><tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $codice . "</td><td>" . $titolo . "</td><td>" . $genere ."</td><td>" . $casaproducer ."</td><td>" . $quantita ."</td><td>" . convertDate($data) ."</td><td>" . $nuovo ."</td><td>" .$usato. "</td><td>".$img."</td></tr>";
      }
      echo "</tbody></table>";
      $stmt ->free_result();
      echo '</div>';
    }
  }


  else { // pagin predefinita stampo tutto
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $querysql='SELECT * FROM Prodotto order by Titolo, Genere';
    $result=$link->query($querysql);
    echo "
    <table class=' table table-hover'>";
    echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> <th> Immagine </th> </tr></thead>
    <tbody>";
    /* Fetch the value */
    while($row = $result->fetch_assoc()) { //scrivo i valori
      echo "<tr>";
      foreach ($row as $key => $value) {
        if ($key=='data_uscita')
        {
          echo "<td>". convertDate($value).'</td> ';
        }
        else {
          echo "<td>". $value.'</td> ';
        }

      }
      echo "</tr>";
    }
    echo " </tbody></table>";
    echo '</div>';
  }
  session_write_close();
  ?>



  <br> <br>


</body>
</html>
