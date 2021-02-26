<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <title>CLIENTI</title>
</head>
<body>
  <div class="jumbotron">
<div class="container text-center">
  <h1>Pannello di controllo</h1>
  <p>Clienti</p>
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
    <input type="submit" name="back" class="btn btn-default" value="Torna al menu principale">

  </form>
  </div>
  <?php
  session_start();
  if (isset($_POST['delete'])) //se si sceglie di cancellare appare un form search
  {
    echo '<div class="container">';
    echo " Chi vuoi cancellare? <br>
    <form  action='customers.php' method='post'>
    <input type='search' name='who' size='32' maxlenght='40' >
    <input type='submit' name='cancella' value='Conferma' >
    </form>
    <form  action='customers.php' method='post'>
    <input type='submit' name='annulla' value='Annulla' >
    </form></div>";



  }
  elseif (isset($_POST['aggiungere']))//se si sceglie di aggiungere un cliente puoi inserire i dati nei form
  {
    echo '<div class="container">';
    echo '<form action="confirmed.php" method="post">
    <div class="form-group">
    Email<input type="text" name="email"><br>
    Nome <input type="text" name="nome"> <br>
    Cognome <input type="text" name="cognome"><br>
    Indirizzo  <input type="text" name="indirizzo"><br>
    password  <input type="text" name="password"><br>
    <input type="submit" name="addcliente" value="Conferma">
    </form><form action="customers.php" method="post">
    <input type="submit" name="annulla" value="Annulla">
    </div>
    </form></div>';

  }


  else { //se non si è scelto nulla in precedenza si mostrano i pulsanti
    echo '<div class="container">';
    echo '
    <div class="form-group">
    <form  action="customers.php" method="post">
    <input type="submit" name="delete" class="btn btn-primary" value="Cancellare un cliente dal database">
    <input type="submit" name="aggiungere" class="btn btn-primary" value="Aggiungere un cliente al database">
    </div>
    </form></div>';
  }


  ?>
  <br> <br>
  <?php
echo "<div class='container'>";
  if (!$_SESSION['admin']) //se non sei riconosciuto come admin torni indietro
  {
    header("location:http://www.vestagames.it/login.php");
  }

  elseif (isset($_POST["who"])) //se viene cercato un commesso da cancellare nel database (riga 44)
  {
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $who= $_POST["who"];

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT Email, Nome, Cognome, Indirizzo FROM Cliente WHERE MATCH(Email,Nome, Cognome) AGAINST(+? IN BOOLEAN MODE)");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$who );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($email,$nome,$cognome,$indirizzo);

    $stmt->store_result();
    if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo
      echo "<h3> Ora ti mostro cosa cerchi:</h3> <br>";

      echo "

        <table class=' table table-hover'>";
      echo "<thead><tr> <th>  Email </th> <th> Nome </th> <th> Cognome </th> <th> Indirizzo </th> </tr></thead> <tbody>";
      /* Fetch the value */
      while($stmt->fetch()) {
        echo "<tr><td>" . $email . "</td><td>" . $nome . "</td><td>" . $cognome ."</td><td>" .$indirizzo. "</td></tr>";
      }
      echo "</tbody></table>";

      if ($stmt->num_rows==1) { // se le tuple sono esattamente una chiedo se vogliono cancellare
        $_SESSION['email']=$email;
        echo "<h3>Vuoi cancellare ".$nome." ".$cognome." con email ".$email."?</h3>
        <form action='confirmed.php' method='post'>
        <input type='submit'class='btn btn-primary' name='yescustomer' value='Conferma'>
        </form>
        <form action='customers.php' method='post'>
        <input type='submit' class='btn btn-default' name='no' value='Annulla'>
        </form>
        ";
      }
      else { // le tuple sono più di una e quindi non posso cancellare
        echo "Ci sono più righe, cerca meglio
        <form action='customers.php' method='post'>
        <input type='submit' class='btn btn-default' name='no' value='Indietro'>
        </form>";
      }
    }

    else // NO ROWS IN RESULT
    {
      echo "<strong> A quanto pare non esiste nel registro, prova a cercare altro!</strong>
      <form action='confirmed.php' method='post'>
      <input type='submit' class='btn btn-default' name='no' value='Indietro'>
      </form>";
    }
    echo '</div>';
  }



  else { // pagina predefinita, mostro tutta la tabella
    echo '<div class="container">';
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $querysql='SELECT Email, Nome, Cognome, Indirizzo FROM Cliente order by Cognome, Nome';
    $result=$link->query($querysql);
    echo "
    <table class=' table table-hover'>";
    echo "<thead><tr> <th> Email </th> <th> Nome </th> <th> Cognome </th> <th> Indirizzo</th> </tr> </thead><tbody>";
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
  echo '</div>';
  echo "</div>";
  session_write_close();
  ?>



  <br> <br>


</body>
</html>
