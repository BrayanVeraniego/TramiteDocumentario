<?php
// Conexion a Base de Datos
class CSql {
   public $pcError;
   protected $h;

   public function __construct() {
      $this->pcError = null;
   }

   public function omDisconnect() {
      $this->omExec("COMMIT;");
      pg_close($this->h);
   }

   public function omConnect($lnFlag = 0) {
      if ($lnFlag == 1) {
         $lcConStr = "host=localhost dbname=UCSMListener port=5432 user=postgres password=postgres";
         //$lcConStr = "host=10.0.7.160 dbname=UCSMListener port=5432 user=postgres password=12Listen34";
      } else {
         //$lcConStr = "host=10.0.102.170 dbname=UCSMERP port=5432 user=postgres password=postgres";
         $lcConStr = "host=localhost dbname=UCSMERP port=5432 user=postgres password=postgres";
      }
      try {
         @$this->h = pg_connect($lcConStr);
      } catch (Exception $ex) {
         $this->pcError = "No se pudo conectar a la base de datos";
         return false;
      }
      $this->omExec("BEGIN;");
      return true;
   }

   public function omExec($p_cSql) {
      $lcSql = substr(strtoupper(trim($p_cSql)), 0, 6);
      if ($lcSql === "SELECT") {
         $this->pnNumRow = 0;
         $RS = pg_query($this->h, $p_cSql);
         if (!($RS)) {
            $this->pcError = "Error al ejecutar comando SQL";
            return false;
         }
         $this->pnNumRow = pg_num_rows($RS);
         return $RS;
      } else {
         @$RS = pg_query($this->h, $p_cSql);
         if (pg_affected_rows($RS) == 0)
            if (!($RS)) {
               $this->pcError = "La operacion no afecto a ninguna fila";
               return false;
            }
         return true;
      }
   }

   public function fetch($RS) {
      return pg_fetch_row($RS);     
   }
   
   public function rollback() {
      $this->omExec("ROLLBACK;");
   }
}

?>