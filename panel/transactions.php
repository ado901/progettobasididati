<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <title>TRANSAZIONI</title>




</head>
<body>
  <body>
    <div class="jumbotron">
      <div class="container text-center">
        <h1>Pannello di controllo</h1>
        <p>Transazioni</p>
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
    <form action="index.php" method="post">
      <input type="submit" class="btn btn-default" name="back" value="Torna al menu principale">

    </form>
    <br>




    <?php
    function convertDate($DATA) {
      if ( $DATA != "" ) {
        if ( stripos( $DATA, "/" ) > -1 ) {     //Codifica
          list( $GIOR, $MESE, $ANNO ) = explode( "/", $DATA );
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
            return $GIOR . "/" . $MESE . "/" . $ANNO;
          } else {
            return "";
          }
        }
      } else { return ""; }
    }




    session_start();
    if (!$_SESSION['admin']) //se non sei riconosciuto come admin torni indietro
    {
      header("location:http://www.vestagames.it/login.php");
    }
    //---------------------------------------INIZIO BLOCCO AGGIUNTA in DATABASE---------------------------------------------------------
    elseif (isset($_POST['aggiungere'])) { //se scelgo il tasto dell'aggiunta
      echo '<div class="container">
      <form action="confirmed.php" method="post">
      <div class="form-group"
      Codice Prodotto<br> <input type="number" name="codice_prodotto"><br>
      Codice Commesso<br> <input type="number" name="codice_commesso"> <br>
      Prenotazione <br> <input type="hidden" name="prenotazione" value="0" />
      <input type="checkbox" name="prenotazione" value="1"> <br>
      Assicurazione <br> <input type="hidden" name="assicurazione" value="0" />
      <input type="checkbox" name="assicurazione" value="1"> <br>
      Prezzo  <br>      <input type="number" step="0.01" name="prezzo"><br>
      Email   <br>      <input type="text" name="email"><br>
      Data     <br>     <input type="date" name="data"><br>
      <input type="submit" name="addtransazione" value="Conferma">
      </form><form action="inventario.php" method="post">
      <input type="submit" name="annulla" value="Annulla">
      </div>
      </form>
      </div>';
    }
    //---------------------------------------FINE BLOCCO AGGIUNTA in DATABASE---------------------------------------------------------



    //---------------------------------------INIZIO BLOCCO INFO---------------------------------------------------------
    elseif (isset($_GET['cercare'])) { // clicco su uno dei collegamenti
      // Variable to check
      echo '<div class="container">';

      if (is_numeric($_GET['cercare'])) {
        $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
        if ($link->connect_error) {
          die("Connection failed: " . $link->connect_error);
        }

//DA QUI IN POI STAMPO TUTTE LE INFORMAZIONI COLLEGATE ALLA TRANSAZIONE ( TRANSAZIONE, COMMESSO, PRODOTTO, CLIENTE)
        //TRANSAZIONE

        $querysql='SELECT * FROM Ordini where codice_transazione='.$_GET['cercare'];
        $result=$link->query($querysql);

        echo "

        <table class=' table table-hover'>";
        echo "<h2>Transazione</h2><br>";

        echo "<thead><tr> <th>  Codice Transazione </th> <th> Codice Prodotto </th> <th> Codice Commesso </th> <th> Prenotazione </th> <th> Assicurazione </th> <th> Prezzo </th> <th> Email </th> <th> Data </th> </tr></thead> <tbody>";
        /* Fetch the value */
        while($row = $result->fetch_assoc()) { //scrivo i valor
          echo "<tr>";
          foreach ($row as $key => $value) {
            if ($key=='Data')
            {
              echo "<td>". convertDate($value).'</td> ';
            }
            elseif ($key=='email' and $value != '') {
              $email=$value;
              echo "<td>". $value.'</td> ';
            }
            else {
              echo "<td>". $value.'</td> ';
            }


          }
          echo "</tr>";
        }

        echo "</tbody></table><br>";
        //PRODOTTI

        $stmt= $link -> query("SELECT Prodotto.codice_prodotto, Prodotto.titolo, Prodotto.genere, Prodotto.Casa_producer, Prodotto.quantita, Prodotto.data_uscita, Prodotto.prezzo_nuovo,Prodotto.prezzo_usato
          FROM Prodotto, Ordini
          WHERE Ordini.codice_transazione=".$_GET['cercare']." AND Ordini.codice_prodotto=Prodotto.codice_prodotto;");
          if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo


            if ($stmt->num_rows==1) { // se le tuple sono esattamente una faccio la tabella
              echo "

              <table class=' table table-hover'>";
              echo "<h2>Prodotto</h2><br>";

              echo "<thead><tr> <th>  Codice_Prodotto </th> <th> Titolo </th> <th> Genere </th> <th> Casa Produttrice </th> <th> Quantità </th> <th> Data uscita </th> <th> Prezzo Nuovo </th> <th> Prezzo Usato </th> </tr></thead> <tbody>";
              /* Fetch the value */
              while($row = $stmt->fetch_assoc()) { //scrivo i valor
                echo "<tr>";
                foreach ($row as $key => $value) {
                  echo "<td>". $value.'</td> ';
                }
                echo "</tr>";
              }
              echo "</tbody></table>";

            }
            else {
              echo "Ci sono più righe";
            }
          }

          else // NO ROWS IN RESULT
          {
            echo "<strong> A quanto pare non esiste nel registro</strong>";
          }
          echo "<br>";
          //COMMESSI

          $stmt= $link -> query("SELECT Commesso.codice_Commesso, Commesso.nome, Commesso.Cognome, Commesso.nprenotazioni, Commesso.nassicurazioni
            FROM Commesso, Ordini
            WHERE Ordini.codice_transazione=".$_GET['cercare']." AND Ordini.codice_commesso=Commesso.codice_commesso;");


            if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo


              if ($stmt->num_rows==1) { // se le tuple sono esattamente una faccio la tabella
                echo "

                <table class=' table table-hover'>";
                echo "<h2>Commesso</h2><br>";
                echo "<thead><tr> <th>  Codice Commesso </th> <th> Nome </th> <th> Cognome </th> <th> Numero Prenotazioni </th> <th> Numero Assicurazioni </th> </tr></thead> <tbody>";
                /* Fetch the value */
                while($row = $stmt->fetch_assoc()) { //scrivo i valori
                  echo "<tr>";
                  foreach ($row as $key => $value) {
                    echo "<td>". $value.'</td> ';
                    echo "";
                  }
                  echo "</tr>";
                }
                echo "</tbody></table>";

              }
              else {
                echo "Ci sono più righe
                ";
              }
            }

            else // NO ROWS IN RESULT
            {
              echo "<strong> A quanto pare non esiste nel registro</strong>
              ";
            }
            // CLIENTE
            if ($email!= '') {

              $stmt= $link -> query("SELECT Cliente.Email, Cliente.Nome, Cliente.cognome, Cliente.indirizzo
                FROM Cliente, Ordini
                WHERE Ordini.codice_transazione=".$_GET['cercare']." AND Ordini.email=Cliente.Email;");
                if($stmt->num_rows > 0) { //se vengono riscontrate più tuple stampo


                  if ($stmt->num_rows==1) { // se le tuple sono esattamente una faccio la tabella

                    echo "

                    <table class=' table table-hover'>";
                    echo "<h2>Cliente</h2><br>";
                    echo "<thead><tr> <th>  Email </th> <th> Nome </th> <th> Cognome </th> <th> Indirizzo  </th> </tr></thead> <tbody>";
                    /* Fetch the value */
                    while($row = $stmt->fetch_assoc()) { //scrivo i valor
                      echo "<tr>";
                      foreach ($row as $key => $value) {
                        echo "<td>". $value.'</td> ';
                      }
                      echo "</tr>";
                    }
                    echo "</tbody></table>";

                  }
                  else {
                    echo "Ci sono più righe";
                  }
                }

                else // NO ROWS IN RESULT
                {
                  echo "<strong> A quanto pare non esiste nel registro</strong>";
                }
                echo "<br>";

              }
              $stmt->free_result();
              echo "<form action='transactions.php' method='post'>
              <input type='submit' class='btn btn-default' name='no' value='Annulla'>
              </form>
              ";
            }

            else { //get non valido
              echo("Variable is not an integer <br>
              <form action='transactions.php' method='post'>
              <input type='submit' name='no' value='Indietro'>
              </form>");

            }
            echo "</div>";
          }
          //---------------------------------------FINE BLOCCO INFO---------------------------------------------------------



          //PAGINA PREDEFINITA, stampo tutte le transazioni nella tabella
          else {
            echo '<div class="container">

            <form  action="transactions.php" method="post">
            <input type="submit" class="btn btn-primary" name="aggiungere" value="Aggiungere una transazione al database">
            </form>';
            $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
            if ($link->connect_error) {
              die("Connection failed: " . $link->connect_error);
            }
            $querysql='SELECT * FROM Ordini order by Data desc';
            $result=$link->query($querysql);
            echo "

            <table class=' table table-hover'>";
            echo "<thead><tr> <th>  Codice Transazione </th> <th> Codice Prodotto </th> <th> Codice Commesso</th> <th> Prenotazione </th> <th> Assicurazione </th> <th> Prezzo </th> <th> Email </th> <th> Data </th> </tr></thead> <tbody>";
            /* Fetch the value */
            while($row = $result->fetch_assoc()) { //scrivo i valor
              echo "<tr>";
              foreach ($row as $key => $value) {
                if ($key=='Data')
                {
                  echo "<td>". convertDate($value).'</td> ';
                }
                elseif ($key=='codice_transazione') {
                  echo '<td><a href="?cercare='.$value.'">'.$value.'</a> </td>';
                }
                elseif ($key=='prenotazione' || $key=='assicurazione')
                {
                  if ($value==1) {
                    echo "<td>SI</td>";
                  }
                  else {
                    echo "<td>NO</td>";
                  }
                }

                else {
                  echo "<td>". $value.'</td> ';
                }


              }
              echo "</tr>";
            }
            echo "</tbody></table>
            </div>";

          }



          ?>

        </body>
        </html>
