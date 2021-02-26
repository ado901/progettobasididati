<html>
<title>VestaGames</title><br>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<div class="jumbotron">
  <div class="container text-center">
    <h1>VestaGames</h1>
    <p>Login</p>
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


<form
action= "login.php"
method= "post">
<div class="form-group">
  <label for="username">Email: </label>
<input type='text' name='username' size='32' placeholder="Inserisci username" maxlenght='40' >


  <label for="password"> Password:  </label><input type='password' size="29" placeholder="Digita la password" name='password'>
  <br>
<input type='submit' class="btn btn-primary" name= "login">
</div>
</form>
<br>Vuoi registrarti?
<form
action="registration.php"
method="post">
<input type='submit' class="btn btn-default" name= "registration" value='Registrati'>







  <?php
  session_start();
  //per evitare problemi con le variabili di sessione, ogni volta che si entra nel login si resettano tutte le variabili session legate all'utente
  unset($_SESSION['loggato']);
  unset($_SESSION["registrato"]);
  unset($_SESSION['admin']);
  unset($_SESSION['nome']);
  unset($_SESSION['email']);
  if (isset($_POST['username']) and isset($_POST['password'])) //gestione form login
  {

    $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
    if ($link->connect_error) {
      die("Connection failed: " . $link->connect_error);
    }
    // IL CODICE DA QUI IN GIU' è UTILE SE HAI UN FORM TEXT, TI GESTISCE I CARATTERI ESCAPE ED è PROTETTO DA INJECTION
    $mail=$_POST['username'];
    $pwd=md5($_POST['password']);

    $link->stmt_init();
    $stmt = $link -> prepare("SELECT Email, Nome, Password FROM Cliente WHERE Email=? AND Password=?");
    /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
    $stmt -> bind_param("ss",$mail,$pwd );

    /* Execute it */
    $stmt -> execute();

    /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
    $stmt -> bind_result($email,$nome, $password);

    $stmt->store_result();

    if($stmt->num_rows >0) {
      $stmt->fetch();
      if ($email =='superadmin')
      {
        $_SESSION['admin']=1;
        header("location:/panel/index.php");
      }
      else {
        $_SESSION['loggato']=1;
        $_SESSION['nome']=$nome;
        $_SESSION['email']=$email;
        header("location: /index.php");
      }


    }
    else echo "Password o email sbagliata";

  } session_write_close(); ?>
</div>
  </html>
