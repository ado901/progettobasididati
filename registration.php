<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
<meta charset="utf-8">

<head>
  <meta charset="utf-8">
  <title>VestaGames</title>
</head>
<body>
  <div class="jumbotron">
<div class="container text-center">
  <h1>VestaGames</h1>
  <p>Registrati</p>
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


<form
action= "registration.php"
method= "post">
<div class="container">
  <div class="form-group">
    <label for="email">Email address:</label>
    <input type="email" class="form-control" id="email" name="username" placeholder="Enter email">
  </div>
  <div class="form-group">
    <label for="pwd">Password:</label>
    <input type="password" class="form-control" id="pwd" name='password' placeholder="Enter password">
  </div>
  <div class="form-group">
    <label for="nome">Nome:</label>
    <input type="text" class="form-control" id="nome" name="nome" placeholder="Enter name">
  </div>
  <div class="form-group">
    <label for="cognome">Cognome:</label>
    <input type="text" class="form-control" id="cognome" name="cognome" placeholder="Enter surname">
  </div>
  <div class="form-group">
    <label for="indirizo">Indirizzo:</label>
    <input type="text" class="form-control" id="residenza" name="residenza">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
</form>
</div>

<?php
session_start();
unset($_SESSION['loggato']);
unset($_SESSION["registrato"]);


if (isset($_POST['username']) && isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['residenza']) && isset($_POST['password']))
{

  if (empty($_POST['username']) || empty($_POST['nome']) || empty($_POST['cognome']) || empty($_POST['residenza']) || empty($_POST['password']) )
  {
    echo "<h3>Compila tutti i form </h3>";
  }
  if (!preg_match("/^[a-zA-Z ]*$/",$_POST['nome'])) {//controllo che l'input sia un nome verosimile

  $nameErr = "Only letters and white space allowed in 'Nome'<br>";
  echo $nameErr;
}
if (!preg_match("/^[a-zA-Z ]*$/",$_POST['cognome'])) { //controllo che l'input sia un cognome verosimile

  $nameErr = "Only letters and white space allowed in 'Cognome' <br>";
  echo $nameErr;
}
if (!preg_match("/^[a-zA-Z ]*$/",$_POST['residenza'])) { //controllo che l'input sia una residenza verosimile

  $nameErr = "Only letters and white space allowed in 'Indirizzo'<br>";
  echo $nameErr;
}
if (!filter_var($_POST['username'], FILTER_VALIDATE_EMAIL)) { //controllo che l'input sia una email verosimile

  $emailErr = "Invalid email format";
  echo $emailErr;
}
  else {


  $link=new mysqli('89.46.111.59','Sql1182390','m774w78080', 'Sql1182390_1');
  if ($link->connect_error)
  {
    die("Connection failed: " . $link->connect_error);
  }
  $link->stmt_init();
  $stmt = $link -> prepare("SELECT Email FROM Cliente WHERE Email=?");
  /* Bind parameters, NOTA:PER OGNI ? BISOGNA METTERE UNA LETTERA E UNA VARIABILE DELLO STESSO TIPO */
  $stmt -> bind_param("s",$_POST['username'] );

  /* Execute it */
  $stmt -> execute();

  /* Bind results, NOTA BENE: DEVI ASSEGNARE LO STESSO NUMERO DI VARIABILI DEGLI ATTRIBUTI IN SELECT */
  $stmt -> bind_result($email);

  $stmt->store_result();
  if($stmt->num_rows >0) {
    echo "<h2> L'utente è già registrato con questa email </h2>";
  }
  else { //se non ci sono problemi creo il nuovo utente
    $stmt->free_result();
    $stmt = $link -> prepare("INSERT INTO Cliente VALUES (?,?,?,?,?)");
    $mdpassword=md5($_POST['password']);
    $stmt -> bind_param("sssss",$_POST['username'], $_POST['nome'], $_POST['cognome'], $_POST['residenza'], $mdpassword);
    $stmt -> execute();

    $_SESSION["registrato"]=1;
    $_SESSION['loggato']=1;
    $_SESSION['nome']=$_POST['nome'];
    $_SESSION["username"]= $_POST['username'];
    header("location: /index.php");
  }
}
}

session_write_close();
?>
<br>
<div class="container">
  <div class="form-group">
    <form action="index.php" method="post">
      <button type="submit" class="btn btn-default">Home</button>

    </form>
  </div>
</div>



</body>
</html>
