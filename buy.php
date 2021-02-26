<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Checkout</title>
</head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<body>
  <div class="jumbotron">
    <div class="container text-center">
      <h1>VestaGames</h1>
      <p>Checkout</p>
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
  unset($_SESSION['done']);
  function convertDate($DATA) { //cconverte date nel formato intero o
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

  if (isset($_GET['cercare'])) { //get con il codice del prodotto, si fa una pagina adhoc per il gioco
    if (is_numeric($_GET['cercare'])) {

      $param= (int)$_GET['cercare'];

      $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
      if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
      }


      $link->stmt_init();

      $stmt=$link->prepare('SELECT * FROM  Prodotto where codice_prodotto=?');
      $stmt->bind_param('i',$param);
      $stmt -> execute();
      $stmt -> bind_result($codiceprodotto,$titolo,$genere,$casa,$quantita,$data,$prezzon,$prezzou, $img);
      $stmt -> store_result();
      if ($stmt->num_rows() < 1) { //se non corrisponde a nessun gioco
        echo "Non esiste nel nostro database";
      }
      else {
      while ($stmt->fetch()) {
        echo "<div class='container'>
        <div class='panel panel-primary'>";
        echo '<div class="panel-heading">'.$titolo.'</div>';
        echo '<div class="panel-body"><img src='.$img.' class="img-responsive" style="width:40%" alt="Image">';
        echo '<h4><br>Genere: '.$genere.'<br>Casa produttrice: '.$casa.'</h4>';
        $_SESSION['prodotto']=$codiceprodotto;
        $_SESSION['prezzonuovo']=$prezzon;
        $_SESSION['prezzousato']=$prezzou;
        if ($data < date('Ymd')) { // controllo sull'uscita del prodotto
          $uscito=true;
          if ($quantita > 0) { // controllo disponibilità in negozio

            $disponibile=true;

            echo '<h4><br><span class="label label-success"> Disponibile</span> </div></h4>';
          }
          else {
            echo '<br><span class="label label-default"> Non disponibile</span> </div>';
            $disponibile=false;
          }

        }
        else {
          echo '<br><span class="label label-default"> Non uscito</span> </div>';
          $uscito=false;
        }
        echo "</div>";
        if ($disponibile==true) { // se è disponibile puoi comprarlo

          echo '<form  action="checkout.php" method="post">
          <label><h3> Vuoi acquistare questo prodotto?</h3> </label>
          <div class="dropdown">
          <button class="btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">Compra questo prodotto
          <span class="caret"></span></button>
          <ul class="dropdown-menu">
          <li><a href="/checkout.php?assicurato">Assicura </a></li>
          <li><a href="/checkout.php?comprato"> Non Assicurare </a></li>
          <input type="hidden" name="comprato" >

          </ul>
          </div>
          </form>


          ';
        }
        else { // se no lo puoi prenotare
          echo '<label for="prenotato"><h3> Vuoi prenotare questo prodotto?</h3> </label>
          <form  action="checkout.php" method="post">
          <div class="form-group">
          <input type="submit" class="btn btn-primary" name="prenotato" value="Prenota">

          </form>

          </div>

          </form>';
        }

        if ($uscito==true) { // se è uscito puoi sicuramente venderlo
          echo '<label for="venduto"><h3> Vuoi vendere questo prodotto?</h3> </label>
          <form  action="checkout.php" method="post">
          <div class="form-group">
          <input type="submit" class="btn btn-primary" name="venduto" value="Vendi">

          </form>

          </div>

          </form>';
        }
      }
    }


    }
    else { //se la get non è numerica
      echo "il codice non è valido";
    }
  }
  else { // se non c'è nessuna get vieni rimandato indietro
    header("location: /index.php");
  }

  session_write_close();
  ?>


</body>

</html>
