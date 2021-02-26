<!DOCTYPE html>
<html>
<meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  <head>
    <meta charset="utf-8">


  </head>
  <body>
    <div class="jumbotron">
  <div class="container text-center">
    <h1>Pannello di controllo</h1>
    <p>Benvenuto manager!</p>
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
    <?php session_start();
    if (!$_SESSION['admin']) //se non sei riconosciuto come admin torni indietro
    {
      header("location: http://www.vestagames.it/login.php");
    }


     ?>
     <div class="container">


<label for="">Quello che puoi fare è:</label>
</body>
<form
     action= "staff.php" method= "post">
     <input type='submit' class="btn btn-primary" name='staff' value='Gestire il personale'>
</form>
<form action="inventario.php" method="post">
  <input type='submit' class="btn btn-primary" name='inventario' value="Gestire il catalogo">
</form>
  <form  action="transactions.php" method="post" >
    <input type="submit" class="btn btn-primary" name="Transazioni" value="Gestire le transazioni effettuate">
  </form>
  <form  action="customers.php" method="post">
    <input type="submit" class="btn btn-primary" name="cliente" value="Gestire i clienti registrati">

  </form>
  <form action= "../index.php" method="post">
    <input type="submit"class="btn btn-default" name="logout" value="Logout">
    </div>

  </form>
</html>
</table>
  </body>
</html>
