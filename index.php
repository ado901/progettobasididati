<!DOCTYPE html>
<html lang="it">
<head>
  <title>VestaGames</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

</head>
<body>



  <div class="jumbotron">
    <div class="container text-center">
      <h1>VestaGames</h1>
      <p>Compra quello che ti interessa!</p>
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

  <?php session_start();
  unset($_SESSION['done']);
  function convertDate($DATA) {//funzione che passa dal formato intero a dd-mm-yyyy
    if ( $DATA != "" ) {
      if ( stripos( $DATA, "-" ) > -1 ) {     //Codifica
        list( $GIOR, $MESE, $ANNO ) = explode( "-", $DATA );
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

  if (isset($_POST['logout'])) //se si esegue il logout tutte le variabili di sessione vengono cancellate
  {
    unset($_SESSION['loggato']);
    unset($_SESSION["registrato"]);
    unset($_SESSION['admin']);
    unset($_SESSION['nome']);
    unset($_SESSION['email']);
  }
  if (isset($_SESSION['loggato'])) //riconoscimento dell'utente
  {
    if (isset($_SESSION['nome'])) {//scrivo il messaggio di benvenuto

    echo '<ul class="nav navbar-nav navbar-right">
      <li><a><span class="glyphicon glyphicon-user"></span> Benvenuto '.$_SESSION['nome'].'</a></li>';
    echo "  <li> <form
    action= 'index.php'
    method= 'post'>
    <input type='submit' name= 'logout' class='btn btn-primary' value='Logout'>
    <br> <br> </form></li> </ul> ";
    echo "</div>
    </div>
    </nav>";
    if (isset($_SESSION['registrato'])) {
    echo "<h4> <label> Registrazione avvenuta con successo</label> </h4> ";
    unset($_SESSION['registrato']);
  }

    }
  }
  else { // utente generico non loggato
    echo ' <ul class="nav navbar-nav navbar-right">
     <li><a href="login.php"><span class="glyphicon glyphicon-user"></span> Login</a></li></ul></div>
     </div>
     </nav>';
  } ?>


<div class="container">


<form
action= "index.php"
method= "post">
<input type='search' name='searching' size='32'placeholder="Cerca un gioco..." maxlenght='40' >
<input type='submit' name='search' class='btn btn-primary'  value="Cerca">
<br>
<input type='submit' class="btn btn-default" name='data' value="Ordina per data di uscita"   >
<input type='submit'class="btn btn-default" name='prezzo'  value="Ordina per prezzo" >
</form>
</div>
<br>
  <?php
  echo "<div class='container'>";

  //--------------------------------------blocco motore ricerca---------------------------------------
  if (isset($_POST['search'])) // è stata eseguita una ricerca
  {
    //var_dump($_POST["searching"]);
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // Statement per le query
    $searching= $_POST['searching'];

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT codice_prodotto, Titolo, Genere, casa_producer as 'Software House', prezzo_nuovo, prezzo_usato, data_uscita FROM Prodotto WHERE MATCH(Titolo, Genere, casa_producer) AGAINST(+? in boolean mode)");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$searching );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codiceprodotto,$titolo,$genere,$casa,$prezzon,$prezzou,$data);

    $stmt->store_result();
    if($stmt->num_rows > 0) {//se ci sono risultati stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "

      <table class=' table table-hover'>";
      echo "<thead><tr> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> <th> Data </th></tr> </thead>
      <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo ' <tr><td><a href="buy.php?cercare='.$codiceprodotto.'">'.$titolo.'</a></td><td>' . $genere . '</td><td>' . $casa .'</td><td>' .$prezzon.'</td><td>' .$prezzou. '</td><td>'.convertDate($data).'</tr>';
      }

      echo " </tbody></table>";
      echo '<div class="container">
        <div class="form-group">
          <form action="index.php" method="post">
            <button type="submit" class="btn btn-default">Home</button>

          </form>
        </div>
      </div>';
    }


    else // NO ROWS IN RESULT
    {
      echo "<strong> A quanto pare non esiste nel nostro registro, prova a cercare altro! (Es: Monster Hunter)</strong>";
      echo '<div class="container">
        <div class="form-group">
          <form action="index.php" method="post">
            <button type="submit" class="btn btn-default">Home</button>

          </form>
        </div>
      </div>';
    }


    $stmt->free_result();
  }

//---------------------------------------blocco ordine data -----------------------------------------------
  elseif (isset($_POST['data'])) {//ordinamento per data

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->stmt_init();
    $stmt = $link -> prepare("SELECT codice_prodotto, Titolo, Genere, casa_producer, prezzo_nuovo, prezzo_usato, data_uscita FROM Prodotto order by data_uscita desc");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */


    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codiceprodotto,$titolo,$genere,$casa,$prezzon,$prezzou,$data);

    $stmt->store_result();

      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "

      <table class=' table table-hover'>";
      echo "<thead><tr> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> <th> Data </th></tr> </thead>
      <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo ' <tr><td><a href="buy.php?cercare='.$codiceprodotto.'">'.$titolo.'</a></td><td>' . $genere . '</td><td>' . $casa .'</td><td>' .$prezzon.'</td><td>' .$prezzou. '</td><td>'.convertDate($data).'</tr>';
      }

      echo " </tbody></table>";
      echo '<div class="container">
        <div class="form-group">
          <form action="index.php" method="post">
            <button type="submit" class="btn btn-default">Home</button>

          </form>
        </div>
      </div>';


  }
//--------------------------------------------blocco ordinamento per prezzo--------------------------------------
  elseif (isset($_POST['prezzo'])) {// ordinamento per prezzo nuovo

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->stmt_init();
    $stmt = $link -> prepare("SELECT codice_prodotto, Titolo, Genere, casa_producer, prezzo_nuovo, prezzo_usato, data_uscita FROM Prodotto order by prezzo_nuovo desc");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */


    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codiceprodotto,$titolo,$genere,$casa,$prezzon,$prezzou,$data);

    $stmt->store_result();

      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "

      <table class=' table table-hover'>";
      echo "<thead><tr> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> <th> Data </th></tr> </thead>
      <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) { //metto un hyperlink con un get avente il codice prodotto
        echo ' <tr><td><a href="buy.php?cercare='.$codiceprodotto.'">'.$titolo.'</a></td><td>' . $genere . '</td><td>' . $casa .'</td><td>' .$prezzon.'</td><td>' .$prezzou. '</td><td>'.convertDate($data).'</tr>';
      }

      echo " </tbody></table>";
      echo '<div class="container">
        <div class="form-group">
          <form action="index.php" method="post">
            <button type="submit" class="btn btn-default">Home</button>

          </form>
        </div>
      </div>';


  }

//----------------------------------------------------------------Blocco di default-------------------------------------------
  else // Non è stata eseguita una ricerca, mostro il catalogo con 6 giochi random
  {
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $querysql='SELECT codice_prodotto, Titolo, Genere, casa_producer,prezzo_nuovo,prezzo_usato, data_uscita, img FROM  Prodotto order by rand() limit 0,6';
$stmt=$link->prepare($querysql);
$stmt -> execute();
$stmt -> bind_result($codiceprodotto,$titolo,$genere,$casa,$prezzon,$prezzou,$data, $img);


    /* Fetch the value */

      $i=0;
    while($stmt->fetch()) { //scrivo i valori
      if ($i==3) { // la variabile $i permette l'allineamento automatico dei prodotti a 3 per volta
        echo '</div>
        </div>';
        $i=0;
      }
      if ($i==0) {
        echo '<div class="container">
          <div class="row">';
      }
        echo '<div class="col-sm-4">
          <div class="panel panel-primary">
            <div class="panel-heading"><a href="buy.php?cercare='.$codiceprodotto. '"> <span style="color: FFFFFF; ">' .$titolo. '</a></div>
            <span style="color: 000000; ">
            <div class="panel-body"><a href="buy.php?cercare=' .$codiceprodotto.'"><img src='.$img.' class="img-responsive" style="width:90%" alt="Image"></a></div>
            <div class="panel-footer">'.$genere.' | Data uscita: '.convertDate($data).'</div>
          </div>
        </div>';
        $i=$i+1;
      }
      echo '</div>
      </div>';
    }
echo "</div>";

  session_write_close();
   ?>

</div>




</body>
</html>
