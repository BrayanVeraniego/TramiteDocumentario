<?php

session_start();
date_default_timezone_set('America/Lima');
require_once 'Libs/Smarty.class.php';
require_once 'Clases/CLogin.php';
$loSmarty = new Smarty;
if (@$_REQUEST['Boton1'] == 'IniciarSesion') {
   fxIniciar();
} else {
   $_SESSION = [];
   fxScreen();
}

function fxIniciar() {
   $lo = new CLogin();
   $lo->paData = $_REQUEST['paData'];
   $llOk = $lo->omIniciarSesion();
   if (!$llOk) {
      fxScreen();
      fxAlert($lo->pcError);
      return;
   }
   $_SESSION['GCCODUSU'] = $lo->paData['CCODUSU'];
   $_SESSION['GCNOMBRE'] = $lo->paData['CNOMBRE'];
   $_SESSION['GCCENCOS'] = $lo->paData['CCENCOS'];
   $_SESSION['GCDESCCO'] = $lo->paData['CDESCCO'];
   $_SESSION['GCCARGO'] = $lo->paData['CCARGO'];
   fxHeader("Mnu1000.php");
}

function fxScreen() {
   global $loSmarty;
   $loSmarty->assign('snBehavior', 0);
   $loSmarty->display('Plantillas/index.tpl');
}

?>
