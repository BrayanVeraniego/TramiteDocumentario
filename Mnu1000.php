<?php
session_start();
require_once 'Libs/Smarty.class.php';
require_once 'Clases/CMenu.php';

$loSmarty = new Smarty;

if (!fxInitSession()) {
   fxHeader("index.php", 'NO HA INICIADO SESION');
} else {
   fxInit();
}


function fxInit() {
   $lo = new CMenu();
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $lo->paData['CCENCOS'] = $_SESSION['GCCENCOS'];
   $llOk = $lo->omBuscarOpciones();
   if (!$llOk) {
      fxHeader("index.php", $lo->pcError);
      return;
   }
   $_SESSION['paRoles'] = $lo->paRoles;
   $_SESSION['paOpcion'] = $lo->paOpcion;
   fxScreen();
}


function fxScreen() {
   global $loSmarty;
   $loSmarty->assign('scNombre', $_SESSION['GCNOMBRE']);
   $loSmarty->assign('saRoles', $_SESSION['paRoles']);
   $loSmarty->assign('saOpcion', $_SESSION['paOpcion']);
   $loSmarty->display('Plantillas/Mnu1000.tpl');
}
?>