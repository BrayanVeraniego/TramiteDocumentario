<!DOCTYPE html>
<html>
<head>
   <title>ERP - Universidad Católica de Santa María</title>
   <meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <link rel="stylesheet" href="js/jquery-ui-1.12.1/jquery-ui.css">
   <link rel="stylesheet" href="bootstrap4/css/bootstrap.min.css">
   <link rel="stylesheet" href="bootstrap4/css/bootstrap-select.css">
   <script src="js/jquery-3.1.1.min.js"></script>
   <script src="js/jquery-ui-1.12.1/jquery-ui.js"></script>
   <script src="bootstrap4/js/bootstrap.bundle.min.js"></script>
   <script src="bootstrap4/js/bootstrap-select.js"></script>
   <link rel="stylesheet" href="css/style.css">
   <script src="js/java.js"></script>
</head>
<body style="padding-top: 0;">
<main role="main">
<div class="jumbotron bg-ucsm">
   <div class="container">
      <h1 class="display-4"><img src="img/logo_ucsm.png" alt="logo UCSM" align="left" width="119" height="96">Universidad Catolica de Santa Maria - ERP</h1>
      <p class="lead">Arequipa - Perú</p>
   </div>
</div>
<div class="container d-flex justify-content-center">
<form action="index.php" method="post" class="col-sm-8">
   <div class="card">
      <div class="card-header text-center">Inicio de Sesión</div>
      <div class="card-body">
         <div class="form-group row">
            <label class="col-sm-3 col-form-label">Nro. DNI</label>
            <div class="col-sm-8">
               <input name="paData[CNRODNI]" type="text" class="form-control" maxlength="8" placeholder="DNI" required autofocus>
            </div>
         </div>
         <div class="form-group row">
            <label class="col-sm-3 col-form-label">Contraseña</label>
            <div class="col-sm-8">
               <input name="paData[CCLAVE]" type="password" class="form-control" required>
            </div>
         </div>
         <div class="form-group row d-flex justify-content-center">
            <div class="col-sm-10 text-center">
               <button type="submit" name="Boton1" value="IniciarSesion" class="btn bg-ucsm">Iniciar Sesion &raquo;</button>
            </div>
         </div>
      </div>
   </div>
</form>
</div>
</main>
<div id="footer"></div>
</body>
</html>
