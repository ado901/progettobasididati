<!DOCTYPE html>
<html>
<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <meta charset="utf-8">
  <title>Vestagames - Azione eseguita</title>
</head>
<body>
  <?php  function convertDate($DATA) { //funzione converte le date da interi a dd-mm-yyyy e viceversa

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
  function back()
  {
    echo '<h2> Azione eseguita </h2> <br>
    <h3> Presto verrai reindirizzato al pannello di controllo </h3>';
    header('Refresh: 5; URL=http://www.vestagames.it/panel/index.php');
  }



  if (isset($_POST['yes'])) //Confermato la cancellazione commesso
  {
    session_start();
    $codicecommesso=$_SESSION['matricola'];
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->stmt_init();
    $stmt = $link -> prepare("DELETE FROM Commesso WHERE codice_commesso=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("i",$codicecommesso);

    /* Execute it */
    $stmt -> execute();
    back();
  }



  elseif (isset($_POST['yescustomer'])) //Confermato la cancellazione del cliente
  {
    session_start();
    $email=$_SESSION['email'];

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->stmt_init();
    $stmt = $link -> prepare("DELETE FROM Cliente WHERE Email=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$email);

    /* Execute it */
    $stmt -> execute();
    back();
  }

  elseif (isset($_POST['yesproduct'])) //Confermato la cancellazione del prodotto
  {
    session_start();
    $codice=$_SESSION['codiceprodotto'];

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $link->stmt_init();
    $stmt = $link -> prepare("DELETE FROM Prodotto WHERE codice_prodotto=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$codice);

    /* Execute it */
    $stmt -> execute();
    back();
  }


  elseif (isset($_POST['add'])) { //confermato il voler aggiungere un commesso
    if (empty($_POST['codice_commesso']) || empty($_POST['nome']) || empty($_POST['cognome']) || empty($_POST['numprenotazioni']) || empty($_POST['numvendite'])) {
      die('<div class="container"> <h3><label> compila tutti i form </label> </h3> </div>');
    }
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $codicecommesso= (int)$_POST["codice_commesso"];
    $nome=$_POST['nome'];
    $cognome=$_POST['cognome'];
    $nprenotazioni=(int)$_POST['numprenotazioni'];
    $nassicurazioni=(int)$_POST['numvendite'];
    $link->stmt_init();
    $stmt = $link -> prepare("SELECT codice_Commesso FROM Commesso WHERE codice_Commesso=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("i",$codicecommesso );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($codice);
    $stmt -> store_result();


    if($stmt->num_rows >0) {
      echo "<h2> Esiste già un commesso con quella matricola </h2>";
      $stmt->free_result();
    }

    else {
      $stmt->free_result();
      $link->stmt_init();
      $stmt = $link -> prepare("INSERT INTO Commesso (`codice_Commesso`, `Nome`, `Cognome`, `nprenotazioni`, `nassicurazioni`) VALUES (?, ?, ?, ?, ?);");
      /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
      $stmt -> bind_param("issii",$codicecommesso,$nome,$cognome,$nprenotazioni,$nassicurazioni );

      /* Execute it */
      $stmt -> execute();
      $stmt->free_result();
      back();

    }

  }

  elseif (isset($_POST['addcliente'])) { //confermato il voler aggiungere un cliente
    if (empty($_POST['email']) || empty($_POST['nome']) || empty($_POST['cognome']) || empty($_POST['indirizzo']) || empty($_POST['password'])) { //check form
      die('<div class="container"> <h3><label> compila tutti i form </label> </h3> </div>');
    }
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $email= $_POST["email"];
    $nome=$_POST['nome'];
    $cognome=$_POST['cognome'];
    $indirizzo=$_POST['indirizzo'];
    $password= md5($_POST['password']);
    $link->stmt_init();
    $stmt = $link -> prepare("SELECT email FROM Cliente WHERE Email=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$email );

    /* Execute it */
    $stmt -> execute();
    $stmt -> bind_result($mail);
    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> store_result();


    if($stmt->num_rows >0) {
      echo "<h2> Esiste già un cliente con quella mail </h2>";
      $stmt->free_result();
    }

    else { // se non esistono già account con quell'email proseguo
      $stmt->free_result();
      $link->stmt_init();
      $stmt = $link -> prepare("INSERT INTO Cliente (`Email`, `Nome`, `Cognome`, `indirizzo`, `password`) VALUES (?, ?, ?, ?, ?);");
      /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
      $stmt -> bind_param("sssss",$email,$nome,$cognome,$indirizzo,$password );

      /* Execute it */
      $stmt -> execute();
      back();
    }
  }

  elseif (isset($_POST['addprodotto'])) { //confermato il voler aggiungere un prodotto
    if (empty($_POST['titolo']) || empty($_POST['genere']) || empty($_POST['casaproducer']) || ($_POST['quantita'] =='') || empty($_POST['uscita']) || empty($_POST['nuovo']) || empty($_POST['usato'])) {
      var_dump($_POST['titolo']);
      var_dump($_POST['genere']);
      var_dump($_POST['casaproducer']);
      var_dump($_POST['quantita']);
      var_dump($_POST['uscita']);
      var_dump($_POST['nuovo']);
      var_dump($_POST['usato']);
      die('<div class="container"> <h3><label> compila tutti i form </label> </h3> </div>');
    }
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $titolo= $_POST["titolo"];
    $genere=$_POST['genere'];
    $casaproducer=$_POST['casaproducer'];
    $quantita=$_POST['quantita'];
    $data=convertDate($_POST['uscita']);
    $nuovo=(double)$_POST['nuovo'];
    $usato= (double)$_POST['usato'];
    $link->stmt_init();



    $target_dir = "../src/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);


    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
    // Check if image file is a actual image or fake image
    if(isset($_POST["addprodotto"])) {
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]); //gestione file immagine
      if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        $uploadOk = 1;
      } else {
        echo "File is not an image.";
        $uploadOk = 0;
      }
    }
    // Check if file already exists
    if (file_exists($target_file)) { //check by name
      echo "Sorry, file already exists.";
      $uploadOk = 0;
    }
    // Check file size
    if ($_FILES["fileToUpload"]["size"] > 500000) {
      echo "Sorry, your file is too large.";
      $uploadOk = 0;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
      echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
      $uploadOk = 0;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
      die("Sorry, your file was not uploaded.");
      // if everything is ok, try to upload file
    } else {
      if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
      } else {
        echo "Sorry, there was an error uploading your file.";
      }
    }

    $stmt = $link -> prepare("SELECT titolo FROM Prodotto WHERE Titolo=?");
    if ( false===$stmt ) {
      die('prepare() failed: ' . htmlspecialchars($link->error));
    }
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("s",$titolo );

    /* Execute it */
    $stmt -> execute();
    $stmt -> bind_result($titolo);
    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> store_result();

    if($stmt->num_rows >0) {
      echo "<h2> Esiste già un prodotto con quel nome </h2>";
      $stmt->free_result();
    }

    else { // se non esiste un prodotto con quel nome lo aggiungo
      $stmt->free_result();
      $link->stmt_init();

      $stmt = $link -> prepare("INSERT INTO `Prodotto` (`codice_prodotto`, `Titolo`, `Genere`, `Casa_producer`, `quantita`, `data_uscita`, `prezzo_nuovo`, `prezzo_usato`, img) VALUES (NULL,?, ?, ?, ?, ?, ?, ?,?);");
      /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
      $pathfile='http://www.vestagames.it/src/'.basename($_FILES["fileToUpload"]["name"]);

      $stmt -> bind_param("sssssdds",$titolo,$genere,$casaproducer,$quantita,$data,$nuovo,$usato,$pathfile);

      /* Execute it */
      $stmt -> execute();
      back();
    }
  }

  elseif (isset($_POST['addtransazione'])) { //aggiunta transazione al database
    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    $codiceprodotto= (int)$_POST["codice_prodotto"];
    $codicecliente=(int)$_POST['codice_commesso'];
    $prenotazione=(int)$_POST['prenotazione'];
    $assicurazione=(int)$_POST['assicurazione'];
    $data=convertDate($_POST['data']);
    $prezzo=(double)$_POST['prezzo'];
    $email= $_POST['email'];

    $link->stmt_init();

    $stmt = $link -> prepare("INSERT INTO Ordini VALUES (NULL,?, ?, ?, ?, ?, ?, ?);");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    if ( false===$stmt ) {
      die('prepare() failed: ' . htmlspecialchars($link->error));
    }


    if (false===($stmt -> bind_param("iiiidss",$codiceprodotto,$codicecliente,$prenotazione,$assicurazione,$prezzo,$email,$data ))) {
      die('bind_param failed: ' . htmlspecialchars($link->error));
    }

    /* Execute it */
    if (false===$stmt -> execute())
    {
      die('execute() failed: ' . htmlspecialchars($link->error));
    }

    back();
  }

  session_write_close();
  ?>

</body>
</html>
