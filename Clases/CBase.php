<?php
   require_once "class/PHPExcel.php";
   
   //require_once 'class/RTFTable.php';

//------------------------------------------------------
// Clase Base
//------------------------------------------------------
class CBase {
   public $pcError;

   function __construct() {
      $this->pcError = null;
   }
}

//------------------------------------------------------
// Clase para fechas
//------------------------------------------------------
class CDate extends CBase {
   public $date;
   public $days;

   public function valDate($p_dFecha) {
      $laFecha = explode('-', $p_dFecha);
      $llOk = checkdate((int)$laFecha[1], (int)$laFecha[2], (int)$laFecha[0]); 
      if (!$llOk) {
         $this->pcError = 'FORMATO DE FECHA INVALIDA';
      }
      return $llOk;
   }

   public function add($p_dFecha, $p_nDias) {
      $llOk = $this->valDate($p_dFecha);
      if (!$llOk) {
         return false;
      }
      if (!is_int($p_nDias)) {
         $this->pcError = 'PARAMETRO DE DIAS ES INVALIDO';
         return false;
      } elseif ($p_nDias >= 0) {
         $lcDias = ' + '.$p_nDias.' days';
      } else {
         $p_nDias = $p_nDias * (-1);
         $lcDias = ' - '.$p_nDias.' days';
      }
      $this->date = date('Y-m-d', strtotime($p_dFecha.$lcDias));
      return true;
   }
   
   public function diff($p_dFecha1, $p_dFecha2) {
      $llOk = $this->valDate($p_dFecha1);
      if (!$llOk) {
         return false;
      }
      $llOk = $this->valDate($p_dFecha2);
      if (!$llOk) {
         return false;
      }
      $this->days = (strtotime($p_dFecha1) - strtotime($p_dFecha2)) / 86400;
      $this->days = floor($this->days);
	  return true;
   }
   
   public function dateText($p_dDate) {
      $llOk = $this->valDate($p_dDate);
      if (!$llOk) {
         return 'Error: '.$p_dDate;
      }
      $laDays = array('Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado');
      $laMonths = array('Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
      $laDate = explode('-', $p_dDate);
      $ldDate = mktime(0, 0, 0, $laDate[1], $laDate[2], $laDate[0]);
      return $laDays[date('w', $ldDate)].', '.date('d', $ldDate).' '.$laMonths[date('m', $ldDate) - 1].' de '.date('Y', $ldDate);
   }
   
}

class CXls extends CBase {
   public $pcData = "", $pcFile, $pcFilXls;
   protected $loXls, $lo, $lcFilXls;

   public function __construct() {
      parent::__construct();
      $this->loXls = new PHPExcel();
      $this->lo = PHPExcel_IOFactory::createReader('Excel2007');      
   }
   
   public function openXls($p_cFilXls) {
      $this->loXls = $this->lo->load('./Xls/'.$p_cFilXls.'.xlsx');      
      $this->lcFilXls = './Files/R'.rand().'.xlsx';
      $this->pcFilXls = $this->lcFilXls;
   }
   
   public function sendXls($p_nSheet, $p_cCol, $p_nRow, $p_xValue) {      
      $this->loXls->setActiveSheetIndex($p_nSheet)->setCellValue($p_cCol.$p_nRow, $p_xValue);         
      return;
   }
   
   public function closeXls() {   
      $lo = PHPExcel_IOFactory::createWriter($this->loXls, 'Excel2007');                        
      $lo->save($this->lcFilXls);
   }
   
   public function cellColor($cells, $color) {
      $this->loXls->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
         'type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => $color)));
   }
   public function cellBorderColor($cells, $color) {
      $this->loXls->getActiveSheet()->getStyle($cells)->getFill()->applyFromArray(array(
         'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN,
                'color' => array('rgb' => 'D74040')
            ))        
          ));
   }
   
   public function cellColor1($Sheet, $cells, $color) {
      $this->loXls->getActiveSheet($Sheet)->getStyle($cells)->getFill()->applyFromArray(array(
         'type' => PHPExcel_Style_Fill::FILL_SOLID, 'startcolor' => array('rgb' => $color)));
   }
   
   public function copyContent($p_SheetIndex = 0) {
      return $this->loXls->getActiveSheet();
   }
   
   public function createSheet($p_SheetName,$p_SheetIndex) {
      $this->loXls->createSheet($p_SheetIndex);
      $this->loXls->setActiveSheetIndex($p_SheetIndex);
      $this->loXls->getActiveSheet()->setTitle($p_SheetName);
   }
   
   public function createSheetFormat($p_Format,$p_SheetName,$p_SheetIndex) {    
      echo $p_SheetIndex.'-';
      if ($p_SheetIndex)
      $this->loXls->addExternalSheet($p_Format,$p_SheetIndex);
      echo -2;
      $this->loXls->setActiveSheetIndex($p_SheetIndex);
      $this->loXls->getActiveSheet()->setTitle($p_SheetName);
   }
   
   public function setActiveSheet($p_nSheet) {
      $this->loXls->setActiveSheetIndex($p_nSheet);
   }
   
   public function getValue($p_nSheet, $p_cCol, $p_nRow) {
      $lcCell = $p_cCol.$p_nRow;
      //$lxValue = $this->loXls->getActiveSheet($p_nSheet)->getCell($lcCell)->getValue();
      $lxValue = $this->loXls->getActiveSheet(1)->getCell($lcCell)->getValue();
      return $lxValue;
   }
   
   public function openXlsIO($p_cFilXls, $p_cPrefij) {
      $this->loXls = $this->lo->load('./Xls/'.$p_cFilXls.'.xlsx');
      $lcFile = $p_cPrefij.rand();
      $this->pcFile = './FILES/'.$lcFile.'.xlsx';
   }
   
   public function closeXlsIO() {
      $lo = PHPExcel_IOFactory::createWriter($this->loXls, 'Excel2007');                        
      $lo->save($this->pcFile);
   }
   
   public function getColor() {
      $lxValue = $this->loXls->getActiveSheet()->getStyle('D2')->getFill()->getStartColor()->getRGB();
      return $lxValue;
   }
}

class CRtf extends CBase {
   public $pcFile, $paArray, $pcFilRet, $pcCodUsu, $paAArray, $pcFileName;
   protected $lcFolXls, $lcFolSal, $lcFilRet, $lcFilInp, $lcTodo, $lp;

   function __construct () {
      parent::__construct();
      $this->paArray = null;
      $this->lcFolXls = './Xls/';
      $this->lcFolSal = './Ficheros/';
   }

   public function omInit() {
      $lcFile1 = $this->lcFolXls.$this->pcFile.'.rtf';
      if (empty($this->pcCodUsu)) {
         $this->pcError = 'CODIGO DE USUARIO NO DEFINIDO';
         return false;
      } elseif (!is_file($lcFile1)) {
         $this->pcError = 'ARCHIVO DE ORIGEN NO EXISTE ['.$lcFile1.']';
         return false;
      }
      if (empty($this->pcFilRet)) {
         $this->pcFilRet = $this->lcFolSal.$this->pcFileName.'.doc';
      }
      $this->lp = fopen($this->pcFilRet, 'w');
      // Lee archivo formato
      $laTexto = file($lcFile1);
      $lnSize = sizeof($laTexto);
      $this->lcTodo = '';
      for ($i = 0;$i < $lnSize; $i++) {
          $this->lcTodo .= $lcTodo.$laTexto[$i];
      }
      return true;
   }

   public function omInicializar() {
      $lcFile1 = $this->lcFolXls.$this->pcFile.'.rtf';
      if (empty($this->pcCodUsu)) {
         $this->pcError = 'CODIGO DE USUARIO NO DEFINIDO';
         return false;
      } elseif (!is_file($lcFile1)) {
         $this->pcError = 'ARCHIVO DE ORIGEN NO EXISTE';
         return false;
      }
      if (empty($this->pcFilRet)) {
         $this->pcFilRet = $this->lcFolSal.$this->pcFile.'_'.$this->pcCodUsu.'.doc';
      }
      $this->lp = fopen($this->pcFilRet, 'w');
      // Lee archivo formato
      $laTexto = file($lcFile1);
      $lnSize = sizeof($laTexto);
      $this->lcTodo = '';
      for ($i = 0;$i < $lnSize; $i++) {
          $this->lcTodo .= $lcTodo.$laTexto[$i];
      }
      return true;
   }

   protected function mxTerminar() {
      fputs($this->lp, $this->lcTodo);
      fclose($this->lp);
      return true;
   }

   public function omGenerar($p_lClose = false) {
      if (!(is_array($this->paArray) and count($this->paArray) > 0)) {
         fclose($this->lp);
         $this->pcError = 'ARREGLO DE DATOS NO DEFINIDO';
         return false;
      }
      // Reemplazo de variables
      foreach ($this->paArray as $lcValor1 => $lcValor2) {
         $lcValor2 = utf8_decode($lcValor2);
         $this->lcTodo = str_replace($lcValor1, $lcValor2, $this->lcTodo);
      }
      if ($p_lClose) {
         $this->mxTerminar();
      }
      return true;
   }

   public function omGenerarArray($p_lClose = false) {
      if (!(is_array($this->paAArray) and count($this->paAArray) > 0)) {
         fclose($this->lp);
         $this->pcError = 'ARREGLO DE DATOS NO DEFINIDO';
         return false;
      }
      foreach ($this->paAArray as $lcValor1 => $lcValor2) {
         $loTabla = new RTFTable($lcValor2[0], $lcValor2[1]);
         if ($lcValor2[3]=='') {
            $loTabla->SetWideColsTable(round(10500/$lcValor2[0]));
         }else {
            for ($k = 0;$k < count($lcValor2[3]);$k++) {
                $loTabla->SetWideColTable($k,$lcValor2[3][$k]);
            }
         }
         //Llenar Tabla cn arreglo pos:2
         for ($i = 0;$i < count($lcValor2[2]);$i++) {
             for ($j = 0;$j < count($lcValor2[2][0]);$j++) {
                 $lcValor2[2][$i][$j] = utf8_decode($lcValor2[2][$i][$j]);
                 if ($j ==0 ) {
                    //Centrado
                    $loTabla->SetElementCell($i,$j,'\\qc '.$lcValor2[2][$i][$j]);
                 }else
                    $loTabla->SetElementCell($i,$j,' '.$lcValor2[2][$i][$j]);
             }
         }
         $this->lcTodo = str_replace($lcValor1,$loTabla->GetTable() ,$this->lcTodo);
      }
      if ($p_lClose) {
         $this->mxTerminar();
      }
      return true;
   }

   protected function mxLeerArchivo() {
      if (!is_file($this->pcFile)) {
         $this->pcError = '<DATA><ERROR>ARCHIVO DE ORIGEN NO EXISTE</ERROR></DATA>';
         return false;
      }
      $laTexto = file($this->pcFile);
      $lnSize = sizeof($laTexto);
      $lcTodo = '';
      for ($i = 0;$i < $lnSize;$i++) {
          $lcTodo = $lcTodo.$laTexto[$i];
      }
      return $lcTodo;
   }

   public function omProcesar() {
      $this->lcFilRet = $this->lcFolSal.$this->pcFile.'_'.$this->pcCodUsu.'.rtf';//-- DEFINIMOS EL NOMBRE DEL NUEVO FICHERO
      $this->pcFile = $this->lcFolXls.$this->pcFile.'.rtf';
      if ($lcTexto = $this->mxLeerArchivo()) {
         $lp = fopen($this->lcFilRet, 'w');
         if (is_array($this->paArray) and count($this->paArray) > 0) {
            foreach($this->paArray as $lcValor1 =>$lcValor2) {//-- REEMPLAZAMOS LAS VARIABLES
               $lcValor2 = utf8_decode($lcValor2);
               $lcTexto = str_replace($lcValor1, $lcValor2 ,$lcTexto);
            }
         }
         fputs($lp, $lcTexto);
         fclose($lp);
         header ('Content-Disposition: attachment;filename = '.$this->lcFilRet.'\n\n');
         header ('Content-Type: application/octet-stream');
         readfile($this->lcFilRet);
      }
   }
}


class CNumeroLetras {
   protected $lcVacio, $lcNegati;

   public function __construct() {
      //parent::__construct();
      $this->lcVacio = '';
      $this->lcNegati = 'Menos';
   }

   public function omNumeroLetras($p_nNumero, $p_cDesMon) {
      $lcSigno = '';
      if (floatVal($p_nNumero) < 0) {
         $lcSigno = $this->lcNegati.' ';
      }
      $lcNumero = number_format($p_nNumero, 2, '.', '');
      // Posicion del punto decimal
      $Pto = strpos($lcNumero, '.');
      if ($Pto === false) {
         $lcEntero = $lcNumero;
         $lcDecima = $this->lcVacio;
      } else {
         $lcEntero = substr($lcNumero, 0, $Pto);
         $lcDecima =  substr($lcNumero, $Pto+1);
      }
      if ($lcEntero == '0' || $lcEntero == $this->lcVacio) {
         $lcNumero = 'Cero ';
      } elseif (strlen($lcEntero) > 7) {
         $lcNumero = $this->SubValLetra(intval(substr($lcEntero, 0,  strlen($lcEntero) - 6)))."Millones " . $this->SubValLetra(intval(substr($lcEntero, -6, 6)));
      } else {
         $lcNumero = $this->SubValLetra(intval($lcEntero));
      }
      if (substr($lcNumero,-9, 9) == "Millones " || substr($lcNumero,-7, 7) == "Millón ") {
         $lcNumero = $lcNumero . "de ";
      }
      if ($lcDecima != $this->lcVacio) {
       $lcNumero = $lcNumero . "CON " . $lcDecima. "/100";
      }
      $lcNumero = $lcNumero . $p_cDesMon;
      $letrass=$lcSigno . $lcNumero;
      return ($lcSigno . $letrass);
   }

   protected function SubValLetra($numero) {
      $Ptr="";
      $n=0;
      $i=0;
      $x ="";
      $Rtn ="";
      $Tem ="";
      $x = trim("$numero");
      $n = strlen($x);
      $Tem = $this->lcVacio;
      $i = $n;
      while($i > 0) {
         $Tem = $this->Parte(intval(substr($x, $n - $i, 1).str_repeat('0', $i - 1)));
         if ($Tem != "Cero") {
            $Rtn .= $Tem . ' ';
         }
         $i = $i - 1;
      }
      //--------------------- GoSub FiltroMil ------------------------------
      $Rtn = str_replace(" Mil Mil", " Un Mil", $Rtn);
      while (1) {
         $Ptr = strpos($Rtn, "Mil ");
         if (!($Ptr===false)) {
            if (! (strpos($Rtn, "Mil ",$Ptr + 1) === false)) {
               $this->ReplaceStringFrom($Rtn, "Mil ", "", $Ptr);
            } else {
               break;
            }
         } else {
            break;
         }
      }
      //--------------------- GoSub FiltroCiento ------------------------------
      $Ptr = -1;
      do {
         $Ptr = strpos($Rtn, "Cien ", $Ptr+1);
         if (!($Ptr===false)) {
            $Tem = substr($Rtn, $Ptr + 5 ,1);
            if ($Tem == "M" || $Tem == $this->lcVacio)
             ;
            else
               $this->ReplaceStringFrom($Rtn, "Cien", "Ciento", $Ptr);
         }
      } while(!($Ptr === false));
      //--------------------- FiltroEspeciales ------------------------------
      $Rtn=str_replace("Diez Un", "Once", $Rtn);
      $Rtn=str_replace("Diez Dos", "Doce", $Rtn);
      $Rtn=str_replace("Diez Tres", "Trece", $Rtn);
      $Rtn=str_replace("Diez Cuatro", "Catorce", $Rtn);
      $Rtn=str_replace("Diez Cinco", "Quince", $Rtn);
      $Rtn=str_replace("Diez Seis", "Dieciseis", $Rtn);
      $Rtn=str_replace("Diez Siete", "Diecisiete", $Rtn);
      $Rtn=str_replace("Diez Ocho", "Dieciocho", $Rtn);
      $Rtn=str_replace("Diez Nueve", "Diecinueve", $Rtn);
      $Rtn=str_replace("Veinte Un", "Veintiun", $Rtn);
      $Rtn=str_replace("Veinte Dos", "Veintidos", $Rtn);
      $Rtn=str_replace("Veinte Tres", "Veintitres", $Rtn);
      $Rtn=str_replace("Veinte Cuatro", "Veinticuatro", $Rtn);
      $Rtn=str_replace("Veinte Cinco", "Veinticinco", $Rtn);
      $Rtn=str_replace("Veinte Seis", "Veintiseís", $Rtn);
      $Rtn=str_replace("Veinte Siete", "Veintisiete", $Rtn);
      $Rtn=str_replace("Veinte Ocho", "Veintiocho", $Rtn);
      $Rtn=str_replace("Veinte Nueve", "Veintinueve", $Rtn);
      //--------------------- FiltroUn ------------------------------
      if (substr($Rtn,0,1) == "M") {
         $Rtn = "Un " . $Rtn;
      }
      //--------------------- Adicionar Y ------------------------------
      for ($i=65; $i<=88; $i++) {
          if ($i != 77) {
             $Rtn=str_replace("a " . Chr($i), "* y " . Chr($i), $Rtn);
          }
      }
      $Rtn=str_replace("*", "a" , $Rtn);
      return($Rtn);
   }


   protected function ReplaceStringFrom(&$x, $OldWrd, $NewWrd, $Ptr) {
      $x = substr($x, 0, $Ptr)  . $NewWrd . substr($x, strlen($OldWrd) + $Ptr);
   }

   protected function Parte($x) {
      $Rtn='';
      $t='';
      $i='';
      Do {
         switch($x) {
            Case 0:  $t = "Cero";break;
            Case 1:  $t = "Un";break;
            Case 2:  $t = "Dos";break;
            Case 3:  $t = "Tres";break;
            Case 4:  $t = "Cuatro";break;
            Case 5:  $t = "Cinco";break;
            Case 6:  $t = "Seis";break;
            Case 7:  $t = "Siete";break;
            Case 8:  $t = "Ocho";break;
            Case 9:  $t = "Nueve";break;
            Case 10: $t = "Diez";break;
            Case 20: $t = "Veinte";break;
            Case 30: $t = "Treinta";break;
            Case 40: $t = "Cuarenta";break;
            Case 50: $t = "Cincuenta";break;
            Case 60: $t = "Sesenta";break;
            Case 70: $t = "Setenta";break;
            Case 80: $t = "Ochenta";break;
            Case 90: $t = "Noventa";break;
            Case 100: $t = "Cien";break;
            Case 200: $t = "Doscientos";break;
            Case 300: $t = "Trescientos";break;
            Case 400: $t = "Cuatrocientos";break;
            Case 500: $t = "Quinientos";break;
            Case 600: $t = "Seiscientos";break;
            Case 700: $t = "Setecientos";break;
            Case 800: $t = "Ochocientos";break;
            Case 900: $t = "Novecientos";break;
            Case 1000: $t = "Mil";break;
            Case 1000000: $t = "Millón";break;
         }
         if ($t == $this->lcVacio) {
            $i = $i + 1;
            $x = $x / 1000;
            if ($x== 0) {
               $i = 0;
            }
         } else {
            break;
         }

      } while($i != 0);
      $Rtn = $t;
      Switch($i) {
         Case 0: $t = $this->lcVacio;break;
         Case 1: $t = " Mil";break;
         Case 2: $t = " Millones";break;
         Case 3: $t = " Billones";break;
      }
      return($Rtn.$t);
   }
}


function fxAlert($p_Message) {
   echo "<script type=\"text/javascript\">";
   echo "alert('$p_Message')";
   echo "</script>";  
}

function fxHeader($p_cLocation, $p_cMensaje = '') {
   if (empty($p_cMensaje)) {
      $lcScript = "window.location='$p_cLocation';";
   } else {
      $lcScript = "alert('$p_cMensaje');window.location='$p_cLocation';";
      //$lcScript = "window.location='$p_cLocation';alert('$p_cMensaje');";
   }
   echo '<script>'.$lcScript.'</script>';
}

function right($lcCadena, $count) {
   return substr($lcCadena, ($count * -1));
}

function left($lcCadena, $count) {
   return substr($lcCadena, 0, $count);
}

function fxNumber($p_nNumero, $p_nLength, $p_nDecimal) {
   $lcNumero = number_format($p_nNumero, $p_nDecimal);
   $lcCadena = str_repeat(' ', $p_nLength).$lcNumero;
   return right($lcCadena, $p_nLength);
}
        
function fxString($p_cCadena, $p_nLength) {
   #$i = substr_count($p_cCadena, 'Ñ');
   $lcCadena = $p_cCadena.str_repeat(' ', $p_nLength);
   #$lcCadena = substr($lcCadena, 0, $p_nLength + $i);
   $lcCadena = substr($lcCadena, 0, $p_nLength);
   return $lcCadena;
}

function fxInitSession() {
   if (!(isset($_SESSION["GCNOMBRE"]) and isset($_SESSION["GCCODUSU"]) and isset($_SESSION['GCCENCOS']))) {
      return false;
   }
   return true;
}
function fxInitSessionP() {
   if (!(isset($_SESSION["GCNRORUC"]) and isset($_SESSION["GCRAZSOC"]))) {
      return false;
   }
   return true;
}

function fxSubstrCount($p_cString) {
   $i = substr_count($p_cString, 'Á');
   $i += substr_count($p_cString, 'É');
   $i += substr_count($p_cString, 'Í');
   $i += substr_count($p_cString, 'Ó');
   $i += substr_count($p_cString, 'Ú');
   //$i = substr_count($lcNomAlu, 'Ñ');
   return $i;
}

function fxDocumento($FilRet){
   $lcTxt = "<script type=text/javascript>";
   $lcTxt .= "window.open('$FilRet', '', 'toolbar=yes, scrollbars=yes, resizable=yes, width=950, height=650');";
   $lcTxt .= "</script>";
   echo $lcTxt;
}

function fxDocumento2($FilRet){
   $lcTxt = "<script type=text/javascript>";
   $lcTxt .= "window.open('$FilRet', '', 'toolbar=no, scrollbars=no, resizable=no, width=589, height=273');";
   $lcTxt .= "</script>";
   echo $lcTxt;
}

?>
