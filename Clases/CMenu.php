<?php
//      ECHO $lcSql .'<BR>';
require_once "Clases/CBase.php";
require_once "Clases/CSql.php";

class CMenu extends CBase {

   public $paData, $paDatos, $paRoles, $paOpcion;

   public function __construct() {
      parent::__construct();
      $this->paData = $this->paDatos = $this->paRoles = $this->paOpcion = null;
   }
   
   //MI PRIMER MENU :V
   public function omBuscarOpciones() {
      $loSql = new CSql();
      $llOk = $loSql->omConnect();
      if (!$llOk) {
         $this->pcError = $loSql->pcError;
         return false;
      }
      $llOk = $this->mxBuscarOpciones($loSql);
      $loSql->omDisconnect();
      return $llOk;
   }

   protected function mxBuscarOpciones($p_oSql) {
      $lcCodUsu = $this->paData['CCODUSU'];
      $lcSql = "SELECT DISTINCT cCodRol, cDesRol FROM V_S01TUSU_2 WHERE cCodUsu = '$lcCodUsu'";
      $RS = $p_oSql->omExec($lcSql);
      $i = 0;
      while ($laFila = $p_oSql->fetch($RS)) {
         $this->paRoles[] = ['CCODROL' => $laFila[0], 'CDESROL' => $laFila[1]];
         $i++;
      }
      if ($i == 0) {
         $this->pcError = "NO HAY ROLES ASIGNADOS A ESTE USUARIO";
         return false;
      }
      
      
      $lcSql = "SELECT cCodRol, cDesRol, cCodOpc, cDesOpc FROM V_S01TUSU_2 WHERE cCodUsu = '$lcCodUsu'";
      $RS = $p_oSql->omExec($lcSql);
      $i = 0;
      while ($laFila = $p_oSql->fetch($RS)) {
         $this->paOpcion[] = ['CCODROL' => $laFila[0], 'CDESROL' => $laFila[1],
                       'CCODOPC' => $laFila[2], 'CDESOPC' => $laFila[3]];
         $i++;
      }
      if ($i == 0) {
         $this->pcError = "NO HAY ROLES ASIGNADOS A ESTE USUARIO";
         return false;
      }
      return true;
      /*$laTmp = $laDatos[0];
      foreach ($laDatos as $laFila) {
         if ($laFila['CCODROL'] != $lcRol){
            $lcRol = $laFila['CCODROL'];
         } else {
            //$this->paDatos[];
         }
      }
      return true;*/
   }  
}
?>