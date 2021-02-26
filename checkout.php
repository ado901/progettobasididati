<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Vestagames - Checkout</title>
</head>
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
echo "<div class='container'";

if (isset($_SESSION['done'])) {
  echo "<h3><label> Hai già acquistato </label></h3>";
}

elseif (isset($_GET['comprato'])) { // è stato effettuato un acquisto

  $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
  if ($link->connect_error) {
    die("Connection failed: " . $link->connect_error);
  }


  $link->stmt_init();
  // si fa meno 1 alla quantità visto che si compra un prodotto
  $stmt=$link->prepare('UPDATE `Sql1182390_1`.`Prodotto` SET `quantita` = quantita-1 WHERE `Prodotto`.`codice_prodotto` = ?');
  $stmt->bind_param('i',$_SESSION['prodotto']);
  $stmt -> execute();
  $stmt->free_result();

  $stmt=$link->prepare('SELECT codice_commesso from Commesso order by rand() limit 0,1');

  $stmt -> execute();
  $stmt -> bind_result($codicecommesso);
  while ($stmt->fetch()) {}
    $stmt->free_result();



    // generazione transazione a seconda del login dell'utente
    if (isset($_SESSION['email'])) {

      $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
        (`codice_transazione` ,
          `codice_prodotto` ,
          `codice_commesso` ,
          `prenotazione` ,
          `assicurazione` ,
          `prezzo` ,
          `email` ,
          `Data`)
          VALUES (null,?,?,0,0,?,?,?)');

          $stmt->bind_param('iidsi',$_SESSION['prodotto'], $codicecommesso, $_SESSION['prezzonuovo'],$_SESSION['email'], date('Ymd'));
        }
        else {


          $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
            (`codice_transazione` ,
              `codice_prodotto` ,
              `codice_commesso` ,
              `prenotazione` ,
              `assicurazione` ,
              `prezzo` ,
              `email` ,
              `Data`)
              VALUES (null,?,?,0,0,?,null,?)');
              $stmt->bind_param('iidi',$_SESSION['prodotto'], $codicecommesso,$_SESSION['prezzonuovo'], date('Ymd'));

            }

            $stmt -> execute();
            $stmt->free_result();

            unset($_SESSION['prodotto']);
            unset($_SESSION['prezzonuovo']);
            unset($_SESSION['prezzousato']);
            echo "<h3><label>il prodotto è stato acquistato</label> </h3>";
            $_SESSION['done']=1;

          }
          //--------------------------------------------------------------blocco garanzia-------------------------------------------------------
          elseif (isset($_GET['assicurato'])) { // comprato e assicurato
            $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
            if ($link->connect_error) {
              die("Connection failed: " . $link->connect_error);
            }


            $link->stmt_init();

            $stmt=$link->prepare('UPDATE `Sql1182390_1`.`Prodotto` SET `quantita` = quantita-1 WHERE `Prodotto`.`codice_prodotto` = ?');
            $stmt->bind_param('i',$_SESSION['prodotto']);
            $stmt -> execute();
            $stmt->free_result();

            $stmt=$link->prepare('SELECT codice_commesso from Commesso order by rand() limit 0,1');

            $stmt -> execute();
            $stmt -> bind_result($codicecommesso);
            while ($stmt->fetch()) {}
              $stmt->free_result();



              // generazione transazione a seconda del login dell'utente
              if (isset($_SESSION['email'])) {


                $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
                  (`codice_transazione` ,
                    `codice_prodotto` ,
                    `codice_commesso` ,
                    `prenotazione` ,
                    `assicurazione` ,
                    `prezzo` ,
                    `email` ,
                    `Data`)
                    VALUES (null,?,?,0,1,?,?,?)');

                    $stmt->bind_param('iidsi',$_SESSION['prodotto'], $codicecommesso, $_SESSION['prezzonuovo'],$_SESSION['email'], date('Ymd'));
                  }
                  else {


                    $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
                      (`codice_transazione` ,
                        `codice_prodotto` ,
                        `codice_commesso` ,
                        `prenotazione` ,
                        `assicurazione` ,
                        `prezzo` ,
                        `email` ,
                        `Data`)
                        VALUES (null,?,?,0,1,?,null,?)');
                        $stmt->bind_param('iidi',$_SESSION['prodotto'], $codicecommesso,$_SESSION['prezzonuovo'], date('Ymd'));

                      }

                      $stmt -> execute();
                      $stmt->free_result();

                      unset($_SESSION['prodotto']);
                      unset($_SESSION['prezzonuovo']);
                      unset($_SESSION['prezzousato']);
                      echo "<h3><label>il prodotto è stato acquistato e assicurato</label> </h3>";
                      $stmt=$link->prepare('UPDATE `Sql1182390_1`.`Commesso` SET `nassicurazioni` = nassicurazioni+1 WHERE codice_commesso = ?');
                      $stmt->bind_param('i',$codicecommesso);
                      $stmt -> execute();
                      $stmt->free_result();
                      $_SESSION['done']=1;



                    }

                    //-----------------------------------------blocco vendita-------------------------
                    elseif (isset($_POST['venduto'])) { // effettuato vendita
                      $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
                      if ($link->connect_error) {
                        die("Connection failed: " . $link->connect_error);
                      }


                      $link->stmt_init();

                      $stmt=$link->prepare('UPDATE `Sql1182390_1`.`Prodotto` SET `quantita` = quantita+1 WHERE `Prodotto`.`codice_prodotto` = ?');
                      $stmt->bind_param('i',$_SESSION['prodotto']);
                      $stmt -> execute();
                      $stmt->free_result();

                      $stmt=$link->prepare('SELECT codice_commesso from Commesso order by rand() limit 0,1');

                      $stmt -> execute();
                      $stmt -> bind_result($codicecommesso);
                      while ($stmt->fetch()) {}
                        $stmt->free_result();



                        // generazione transazione a seconda del login dell'utente
                        if (isset($_SESSION['email'])) {


                          $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
                            (`codice_transazione` ,
                              `codice_prodotto` ,
                              `codice_commesso` ,
                              `prenotazione` ,
                              `assicurazione` ,
                              `prezzo` ,
                              `email` ,
                              `Data`)
                              VALUES (null,?,?,0,0,?,?,?)');

                              $stmt->bind_param('iidsi',$_SESSION['prodotto'], $codicecommesso, $_SESSION['prezzousato'],$_SESSION['email'], date('Ymd'));
                            }
                            else {


                              $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
                                (`codice_transazione` ,
                                  `codice_prodotto` ,
                                  `codice_commesso` ,
                                  `prenotazione` ,
                                  `assicurazione` ,
                                  `prezzo` ,
                                  `email` ,
                                  `Data`)
                                  VALUES (null,?,?,0,0,?,null,?)');
                                  $stmt->bind_param('iidi',$_SESSION['prodotto'], $codicecommesso,$_SESSION['prezzousato'], date('Ymd'));

                                }

                                $stmt -> execute();
                                $stmt->free_result();

                                unset($_SESSION['prodotto']);
                                unset($_SESSION['prezzonuovo']);
                                unset($_SESSION['prezzousato']);
                                echo "<h3><label>il prodotto è stato venduto</label> </h3>";
                                $_SESSION['done']=1;



                              }
                              //-------------------------------------------blocco prenotazione------------------------------------------------
                              elseif (isset($_POST['prenotato'])) { // effettuata prenotazione
                                $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
                                if ($link->connect_error) {
                                  die("Connection failed: " . $link->connect_error);
                                }


                                $link->stmt_init();



                                $stmt=$link->prepare('SELECT codice_commesso from Commesso order by rand() limit 0,1');

                                $stmt -> execute();
                                $stmt -> bind_result($codicecommesso);
                                while ($stmt->fetch()) {}
                                  $stmt->free_result();



                                  // generazione transazione a seconda del login dell'utente
                                  if (isset($_SESSION['email'])) {


                                    $stmt=$link->prepare('INSERT INTO  `Sql1182390_1`.`Ordini`
                                      (`codice_transazione` ,
                                        `codice_prodotto` ,
                                        `codice_commesso` ,
                                        `prenotazione` ,
                                        `assicurazione` ,
                                        `prezzo` ,
                                        `email` ,
                                        `Data`)
                                        VALUES (null,?,?,1,0,?,?,?)');

                                        $stmt->bind_param('iidsi',$_SESSION['prodotto'], $codicecommesso, $_SESSION['prezzonuovo'],$_SESSION['email'], date('Ymd'));
                                        $stmt -> execute();
                                        $stmt->free_result();

                                        unset($_SESSION['prodotto']);
                                        unset($_SESSION['prezzonuovo']);
                                        unset($_SESSION['prezzousato']);
                                        echo "<h3><label>il prodotto è stato prenotato</label> </h3>";
                                        $stmt=$link->prepare('UPDATE `Sql1182390_1`.`Commesso` SET `nprenotazioni` = nprenotazioni+1 WHERE codice_commesso = ?');
                                        $stmt->bind_param('i',$codicecommesso);
                                        $stmt -> execute();
                                        $stmt->free_result();
                                        $_SESSION['done']=1;
                                      }
                                      else {
                                        echo "<label> Devi effettuare l'accesso per prenotare </label>";

                                      }




                                    }
                                    echo "</div>";



                                    session_write_close(); ?>
                                  </body>
                                  </html>
