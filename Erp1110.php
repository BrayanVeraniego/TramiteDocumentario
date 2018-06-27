<?php
//REGISTRO DE NUEVOS REQUERIMIENTOS -- MIGUEL
session_start();
require_once 'Libs/Smarty.class.php';
require_once 'Clases/CLogin.php';
$loSmarty = new Smarty;
if (false) {
//if (!fxInitSession()) {
   fxHeader("index.php", 'NO HA INICIADO SESION');
} elseif (@$_REQUEST['Boton0'] == 'Nuevo') {
   $_SESSION['paData'] = null;
   $_SESSION['paDatos'] = null;
   fxScreen(1);
} elseif (@$_REQUEST['Boton0'] == 'Editar') {
   fxEditar();
} elseif (@$_REQUEST['Boton0'] == 'Salir') {
   fxHeader("Mnu1000.php");
} elseif (@$_REQUEST['Boton1'] == 'Guardar') {
   $_SESSION['CESTADO'] = 'R';
   fxGuardar();
} elseif (@$_REQUEST['Boton1'] == 'Enviar') {
   $_SESSION['CESTADO'] = 'E';
   fxGuardar();
} elseif (@$_REQUEST['Boton1'] == 'Cancelar') {
   fxScreen(0);
} elseif (@$_REQUEST['Id'] == 'buscarRUC') {
   fxAxBuscarRuc();
} elseif (@$_REQUEST['Id'] == 'buscarArticulo') {
   fxAxBuscarArticulo();
} elseif (@$_REQUEST['Id'] == 'cargarArticulo') {
   fxAxCargarArticulo();
} elseif (@$_REQUEST['Id'] == 'agregarDetalle') {
   fxAxAgregarDetalle();
} elseif (@$_REQUEST['Id'] == 'eliminarDetalle') {
   fxAxEliminarDetalle();
} elseif (@$_REQUEST['Id'] == 'cambiarCentroCosto') {
   fxAxCambiarCentroCosto();
} else {
   $_SESSION['paMoneda']=null;/////////////////////
   fxInit();
}

function fxInit() {/*
   $lo = new CLogistica();
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $lo->paData['CCENCOS'] = $_SESSION['GCCENCOS'];
   $llOk = $lo->omInitRegistroRequerimientos();
   if (!$llOk) {
      fxHeader("Mnu1000.php", $lo->pcError);
      return;
   }
   $_SESSION['paMoneda'] = $lo->paMoneda;
   $_SESSION['paTipReq'] = $lo->paTipReq;
   $_SESSION['paRequer'] = $lo->paRequer;
   $_SESSION['paCodArt'] = null;
   $_SESSION['paData'] = null;
   $_SESSION['paDatos'] = null;
   $_SESSION['pnRango'] = $lo->pnRango;
   $lo->paData = ['CCENCOS' => $_SESSION['GCCENCOS'],
       'CCODUSU' => $_SESSION['GCCODUSU']];
   $llOk = $lo->omTraerCenCos();
   if (!$llOk) {
      fxHeader("Mnu1000.php", $lo->pcError);
      return;
   }
   $_SESSION['paCenCos'] = $lo->paCenCos;
   $lo->paData = ['CCENCOS' => $_SESSION['GCCENCOS']];
   $llOk = $lo->omTraerActividades();
   if (!$llOk) {
      fxHeader("Mnu1000.php", $lo->pcError);
      return;
   }
   $_SESSION['paActivi'] = $lo->paActivi;*/
   fxScreen(0);
}

function fxEditar() {
   $_SESSION['paDatos'] = null;
   $lcIdRequ = $_REQUEST['pcIdRequ'];
   foreach ($_SESSION['paRequer'] as $laFila) {
      if ($laFila['CIDREQU'] === $lcIdRequ) {
         $laData = $laFila;
         break;
      }
   }
   $_SESSION['paData'] = $laData;
   $lo = new CLogistica();
   $lo->paData = $_SESSION['paData'];
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $llOk = $lo->omEditarRequerimiento();
   if (!$llOk) {
      fxScreen(0);
      fxAlert($lo->pcError);
      return;
   }
   $_SESSION['paData']['NTOTAL'] = $lo->paData['NTOTAL'];
   $_SESSION['paDatos'] = $lo->paDatos;
   fxScreen(1);
}

function fxGuardar() {
   $laData = $_REQUEST['paData'];
   $laData['CIDREQU'] = (!empty($laData['CIDREQU'])) ? $laData['CIDREQU'] : '*';
   $laData['CCOMDIR'] = (isset($laData['CCOMDIR'])) ? $laData['CCOMDIR'] : 'N';
   $laData['CDESTIN'] = (isset($laData['CDESTIN'])) ? $laData['CDESTIN'] : 'I';
   $lo = new CLogistica();
   $lo->paData = $laData;
   $lo->paData['MDATOS'] = $_SESSION['paDatos'];
   $lo->paData['CESTADO'] = $_SESSION['CESTADO'];
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $lo->paData['CUSUCOD'] = $_SESSION['GCCODUSU'];
   $lo->paData['CCENCOS'] = $_SESSION['GCCENCOS'];
   $llOk = $lo->omGrabarRequerimiento();
   if (!$llOk) {
      $_SESSION['paData'] = $laData;
      fxScreen(1);
      fxAlert($lo->pcError);
      return;
   }
   fxAlert("DATOS GUARDADOS CORRECTAMENTE");
   fxInit();
}

function fxAxBuscarRUC() {
   $lcNroRuc = strtoupper($_REQUEST['pcNroRuc']);
   $lo = new CLogistica();
   $lo->paData['CNRORUC'] = $lcNroRuc;
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $llOk = $lo->omBuscarProveedorxNroRuc();
   if (!$llOk) {
      return;
   }
   echo json_encode($lo->paDatos);
}

function fxAxBuscarArticulo() {
   $lcBusArt = strtoupper($_REQUEST['pcBusArt']);
   $lo = new CLogistica();
   $lo->paData['CBUSART'] = $lcBusArt;
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $lo->paData['CCARGO'] = $_SESSION['GCCARGO'];
   $llOk = $lo->omBuscarArticuloxDescripcion();
   if (!$llOk) {
      return;
   }
   $_SESSION['paCodArt'] = $lo->paCodArt;
   echo json_encode($_SESSION['paCodArt']);
}

function fxAxAgregarDetalle() {
   $laData = $_REQUEST['paData'];
   if ($_SESSION['paDatos'] != null) {
      $k = 0;
      foreach ($_SESSION['paDatos'] as $laFila) {
         if ($laData['CCODART'] === $laFila['CCODART']) {
            $_SESSION['paDatos'][$k] = $laData;
            axPrintDetalles();
            return;
         }
         $k = $k + 1;
      }
   }
   $_SESSION['paDatos'][] = $laData;
   axPrintDetalles();
}

function fxAxCargarArticulo() {
   $lcCodArt = $_REQUEST['pcCodArt'];
   foreach ($_SESSION['paCodArt'] as $laFila) {
      if ($laFila['CCODART'] === $lcCodArt) {
         echo json_encode($laFila);
         return;
      }
   }
}

function fxAxEliminarDetalle() {
   $lnIndice = $_REQUEST['pnIndice'];
   $laTmp = null;
   $k = 0;
   foreach ($_SESSION['paDatos'] as $laFila) {
      if ($k != $lnIndice) {
         $laTmp[] = $laFila;
      }
      $k = $k + 1;
   }
   $_SESSION['paDatos'] = $laTmp;
   axPrintDetalles();
}

function fxAxCambiarCentroCosto() {
   $lcCenCos = $_REQUEST['pcCenCos'];
   $lcDesCco = $_REQUEST['pcDesCco'];
   $_SESSION['GCCENCOS'] = $lcCenCos;
   $_SESSION['GCDESCCO'] = $lcDesCco;
   $_SESSION['GCCENCOS'] = $lcCenCos;
   $lo = new CLogistica();
   $lo->paData['CCODUSU'] = $_SESSION['GCCODUSU'];
   $lo->paData['CCENCOS'] = $_SESSION['GCCENCOS'];
   $llOk = $lo->omInitRegistroRequerimientos();
   if (!$llOk) {
      fxHeader("Mnu1000.php", $lo->pcError);
      return;
   }
   $_SESSION['paRequer'] = $lo->paRequer;
   $_SESSION['paCodArt'] = null;
   $_SESSION['paData'] = null;
   $_SESSION['paDatos'] = null;
   axPrintRequerimientos();
}

function fxScreen($p_nBehavior) {
   global $loSmarty;
   /**/$loSmarty->assign('scCenCos', $_SESSION['GCCENCOS']);
   $loSmarty->assign('scDesCCo', $_SESSION['GCDESCCO']);
   $loSmarty->assign('scNombre', $_SESSION['GCNOMBRE']);
   $loSmarty->assign('saMoneda', $_SESSION['paMoneda']);
   $loSmarty->assign('saRequer', $_SESSION['paRequer']);
   $loSmarty->assign('saTipReq', $_SESSION['paTipReq']);
   $loSmarty->assign('saCenCos', $_SESSION['paCenCos']);
   $loSmarty->assign('saActivi', $_SESSION['paActivi']);
   $loSmarty->assign('snRango', $_SESSION['pnRango']);
   $loSmarty->assign('saData', $_SESSION['paData']);
   $loSmarty->assign('saDatos', $_SESSION['paDatos']);
   $loSmarty->assign('snBehavior', $p_nBehavior);
   $loSmarty->display('Plantillas/Erp1110.tpl');
}

function axPrintDetalles() {
   global $loSmarty;
   $loSmarty->assign('saDatos', $_SESSION['paDatos']);
   $loSmarty->display('Plantillas/Erp1111.tpl');
}

function axPrintRequerimientos() {
   global $loSmarty;
   $loSmarty->assign('saRequer', $_SESSION['paRequer']);
   $loSmarty->display('Plantillas/Erp1112.tpl');
}

?>
