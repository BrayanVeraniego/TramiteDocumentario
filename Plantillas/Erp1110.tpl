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
      <script>
         function f_Init() {
            f_cargarArticulo();
            f_cambiarMoneda();
            f_showCompraDirecta();
            f_EditarPrecio();
         }

         function f_cargarArticulo() {
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  var lcMoneda = document.getElementById("pcMoneda").value;
                  var laJson = JSON.parse(this.responseText);
                  document.getElementById("pcDesArt").value = laJson.CDESART;
                  document.getElementById("pcUnidad").value = laJson.CUNIDAD;
                  document.getElementById("pnRefSol").value = laJson.NREFSOL;
                  document.getElementById("pnRefDol").value = laJson.NREFDOL;
                  if (lcMoneda === '1') {
                     document.getElementById("pnPreRef").value = laJson.NREFSOL;
                  } else if (lcMoneda === '2') {
                     document.getElementById("pnPreRef").value = laJson.NREFDOL;
                  }
               }
            }
            var lcCodArt = document.getElementById("pcCodArt");
            var lcSend = "Id=cargarArticulo&pcCodArt=" + lcCodArt.value;
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_buscarArticulo() {
            var lcBusArt = document.getElementById("pcBusArt");
            if (lcBusArt.value.length < 5) {
               alert("DEBE INGRESAR AL MENOS 5 CARACTERES PARA LA BUSQUEDA");
               return;
            }
            var lcCodArt = document.getElementById("pcCodArt");
            lcCodArt.innerHTML = '';
            $('#pcCodArt').selectpicker('refresh');
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  var laJson = JSON.parse(this.responseText);
                  for (var i = 0; i < laJson.length; i++) {
                     var option = document.createElement("option");
                     option.text = laJson[i].CCODART + " - " + laJson[i].CDESART;
                     option.value = laJson[i].CCODART;
                     lcCodArt.add(option);
                  }
                  $('#pcCodArt').selectpicker('refresh');
                  f_cargarArticulo();
               }
            }
            var lcSend = "Id=buscarArticulo&pcBusArt=" + lcBusArt.value;
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_agregarDetalle() {
            var lcCodArt = document.getElementById("pcCodArt");
            var lcDesArt = document.getElementById("pcDesArt");
            var lcDesDet = document.getElementById("pcDesDet");
            var lcUnidad = document.getElementById("pcUnidad");
            var lnCantid = document.getElementById("pnCantid");
            if (lnCantid.value === "" || lnCantid.value <= 0) {
               alert("DEBE INGRESAR UNA CANTIDAD MAYOR A CERO");
               return;
            }
            var lnPreRef = document.getElementById("pnPreRef");
            /*
             * if (lnPreRef.value === "" || lnPreRef.value <= 0) {
             alert("DEBE INGRESAR UN PRECIO REFERENCIAL MAYOR A CERO");
             return;
             }
             *
             */
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  $('#nuevoDetalle').modal('hide');
                  document.getElementById("detallesRequerimiento").innerHTML = this.responseText;
                  lcDesDet.value = "";
                  lnCantid.value = "";
               }
            };
            var lcSend = "Id=agregarDetalle" +
                    "&paData[CCODART]=" + lcCodArt.value +
                    "&paData[CDESART]=" + lcDesArt.value +
                    "&paData[CDESDET]=" + lcDesDet.value +
                    "&paData[CUNIDAD]=" + lcUnidad.value +
                    "&paData[NCANTID]=" + lnCantid.value +
                    "&paData[NSTOTAL] =" + (lnPreRef.value * lnCantid.value).toFixed(2) +
                    "&paData[NPREREF]=" + Number(lnPreRef.value).toFixed(4);
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_eliminarDetalle() {
            var xhttp = new XMLHttpRequest();
            var lnIndice = document.querySelector('input[name="pnIndice"]:checked').value;
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("detallesRequerimiento").innerHTML = this.responseText;
               }
            };
            var lcSend = "Id=eliminarDetalle&pnIndice=" + lnIndice;
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_buscarRUC() {
            var lcNroRuc = document.getElementById("pcNroRuc");
            if (lcNroRuc.value.length < 11) {
               alert("DEBE INGRESAR UN NUMERO DE RUC VALIDO");
               return;
            }
            var lcRazSoc = document.getElementById("pcRazSoc");
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  var laJson = JSON.parse(this.responseText);
                  lcRazSoc.value = laJson.CRAZSOC;
               }
            }
            var lcSend = "Id=buscarRUC&pcNroRuc=" + lcNroRuc.value;
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_cambiarMoneda() {
            var tipoMoneda = document.getElementById("tipoMoneda");
            var lcMoneda = document.getElementById("pcMoneda").value;
            if (lcMoneda === '1') {
               tipoMoneda.innerHTML = "S/.";
               document.getElementById("pnPreRef").value = document.getElementById("pnRefSol").value;
            } else if (lcMoneda === '2') {
               tipoMoneda.innerHTML = "USD";
               document.getElementById("pnPreRef").value = document.getElementById("pnRefDol").value;
            }
         }

         function f_showCompraDirecta() {
            var llComDir = document.getElementById('pcComDir').checked;
            if (llComDir) {
               $('#pcNroRuc').prop('required', true);
               $('#pcNroCom').prop('required', true);
               $('#pnMonto').prop('required', true);
               $('#pdFecCom').prop('required', true);
               $('#divComDir').show();
            } else {
               $('#pcNroRuc').prop('required', false);
               $('#pcNroCom').prop('required', false);
               $('#pnMonto').prop('required', false);
               $('#pdFecCom').prop('required', false);
               $('#divComDir').hide();
            }
         }

         function f_hideCompraDirecta() {
            var llDestin = document.getElementById('pcDestin').checked;
            if (llDestin) {
               $('#pcNroRuc').prop('required', false);
               $('#pcNroCom').prop('required', false);
               $('#pnMonto').prop('required', false);
               $('#pdFecCom').prop('required', false);
               $('#pcComDir').prop('checked', false);
               $('#divComDir').hide();
            }
         }

         $(document).ready(function () {
            $('#pcNroRuc').keypress(function (e) {
               if (e.keyCode == 13) {
                  $('#btn_buscarRUC').click();
                  $('#pcNroRuc').focus();
                  return false;
               }
            });

            $('#nuevoDetalle').on('shown.bs.modal', function () {
               $('#pcBusArt').trigger('focus');
            });

            $('#pcBusArt').keypress(function (e) {
               if (e.keyCode == 13) {
                  $('#btn_buscarArt').click();
                  $('#pcBusArt').trigger('focus');
                  return false;
               }
            });
         });

         function f_validarCampos() {
            var lcDescri = document.getElementById("pcDescri").value;
            if (lcDescri.trim() === "") {
               alert("DEBE INGRESAR UNA DESCRIPCION PARA EL REQUERIMIENTO");
               return;
            }
         }

         function f_CambiarCenCos() {
            var lcCenCos = document.getElementById("pcCenCos");
            var xhttp = new XMLHttpRequest();
            xhttp.onreadystatechange = function () {
               if (this.readyState == 4 && this.status == 200) {
                  document.getElementById("requerimientos").innerHTML = this.responseText;
               }
            };
            var lcSend = "Id=cambiarCentroCosto" +
                    "&pcCenCos=" + lcCenCos.value + "&pcDesCco=" + lcCenCos.options[lcCenCos.selectedIndex].text;
            xhttp.open("POST", "Erp1110.php", true);
            xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
            xhttp.send(lcSend);
         }

         function f_EditarPrecio() {
            var lcAlmacen = document.getElementById("pcDestin");
            if (lcAlmacen.checked == true) {
               document.getElementById("pnPreRef").readOnly = true;
            } else {
               document.getElementById("pnPreRef").readOnly = false;
            }
         }
         
         function f_confirmarEnvio() {
            var lnRango = document.getElementById("pnRango");
            var lnTotal = document.getElementById("pnTotal");
            var llOk = confirm('¿Desea enviar el requerimiento para que sea procesado?\nEsta operación no podrá ser revertida.');
            if (llOk && lnTotal.value > lnRango.value) {
               return confirm('El monto total del requerimiento (S/. '+ lnTotal.value +') supera el tope predeterminado, será enviado para ser aprobado previamente por el Vicerrectorado Administrativo.\n¿Desea enviar el requerimiento de todos modos?');
            }
            return llOk;
         }

      </script>
   </head>
   <body onload="f_Init();">
      <div id="header"></div>
      <main role="main" class="container-fluid">
         <form action="Erp1110.php" method="post">
            <div class="row d-flex justify-content-center">
               <div class="col-sm-12">
                  <div class="card text-center">
                     <div class="card-header bg-ucsm">
                        <div class="input-group input-group-sm d-flex justify-content-between">
                           <b>REGISTRO DE REQUERIMIENTOS</b>
                           <div class="input-group-prepend px-2">{$scNombre} :
                              <select id="pcCenCos" name="paData[CCENCOS]" onchange ="f_CambiarCenCos();" {if $snBehavior eq 1}disabled{/if} class="custom-select custom-select-sm col-10">
                                 {foreach from=$saCenCos item=i}
                                    <option value="{$i['CCENCOS']}" {if $scCenCos eq $i['CCENCOS']}selected{/if}>{$i['CDESCRI']}</option>
                                 {/foreach}
                              </select>
                           </div>
                        </div>
                     </div>
                     {if $snBehavior eq 0}
                        <div class="card-body">
                           <div class="row d-flex justify-content-center">
                              <div class="col-sm-11">
                                 <div class="card text-center">
                                    <div>
                                       <div class="table-responsive">
                                          <table class="table table-sm table-hover table-bordered">
                                             <thead>
                                                <tr>
                                                   <th scope="col">ID</th>
                                                   <th scope="col">Descripción</th>
                                                   <th scope="col">Estado</th>
                                                   <th scope="col">Tipo</th>
                                                   <th scope="col">Fecha Generación</th>
                                                   <th scope="col"><img src="css/feather/check-square-white.svg"></th>
                                                </tr>
                                             </thead>
                                             <tbody id = "requerimientos">
                                                {foreach from=$saRequer item=i}
                                                   <tr>
                                                      <th scope="row">{$i['CIDREQU']}</th>
                                                      <td class="text-left">{$i['CDESCRI']}</td>
                                                      <td>{$i['CDESTIP']}</td>
                                                      <td align="center"><input type="radio" name="pcIdRequ" value="{$i['CIDREQU']}" required></td>
                                                   </tr>
                                                {/foreach}
                                             </tbody>
                                          </table>
                                       </div>
                                    </div>
                                    <div class="card-footer text-muted">
                                       <button type="submit" name="Boton0" value="Nuevo" class="btn bg-ucsm col-sm-2 col-md-3" formnovalidate>Nuevo</button>
                                       {if $saRequer neq NULL}
                                          <button type="submit" name="Boton0" value="Editar" class="btn btn-info col-sm-2 col-md-3">Editar</button>
                                       {/if}
                                       <button type="submit" name="Boton0" value="Salir" class="btn btn-danger col-sm-2 col-md-3" formnovalidate>Salir</button> 
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     {else if $snBehavior eq 1}
                        <div>
                           <input type="hidden" id="pnRango" value="{$snRango}">
                           <div class="form-row d-flex justify-content-center">
                              <div class="col-sm-12">
                                 <div class="card text-center">
                                    <div class="p-1">
                                       <div class="form-row mb-1">
                                          <div class="col-12 ">
                                             <div class="input-group input-group-sm mb-1">
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   ID de Requerimiento
                                                </div>
                                                <input type="text" name="paData[CIDREQU]" value="{$saData['CIDREQU']}" placeholder="NUEVO REQ." class="form-control" readonly>
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Tipo de Requerimiento
                                                </div>
                                                <select name="paData[CTIPO]" class="custom-select custom-select-sm">
                                                   {foreach from=$saTipReq item=i}
                                                      <option value="{$i['CCODIGO']}" {if $saData['CTIPO'] eq $i['CCODIGO']}selected{/if}>{$i['CDESCRI']}</option>
                                                   {/foreach}
                                                </select>
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Moneda
                                                </div>
                                                <select name="paData[CMONEDA]" id="pcMoneda" onchange="f_cambiarMoneda();" class="custom-select custom-select-sm form-control col-3">
                                                   {foreach from=$saMoneda item=i}
                                                      <option value="{$i['CCODIGO']}" {if $saData['CMONEDA'] eq $i['CCODIGO']}selected{/if}>{$i['CDESCRI']}</option>
                                                   {/foreach}
                                                </select>
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Compra Directa
                                                </div>
                                                <div class="form-check mx-2 d-flex align-items-center">
                                                   <input type="checkbox" name="paData[CCOMDIR]" id="pcComDir" value="S" {if $saData['CCOMDIR'] eq 'S'} checked {/if} onchange="f_showCompraDirecta()" class="form-check-input position-static">
                                                </div>
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Almacén
                                                </div>
                                                <div class="form-check mx-2 d-flex align-items-center">
                                                   <input type="checkbox" name="paData[CDESTIN]" id="pcDestin" value="F" {if $saData['CDESTIN'] eq 'F'} checked {/if} onchange="f_hideCompraDirecta(); f_EditarPrecio();" class="form-check-input position-static">
                                                </div>
                                             </div>

                                             <div class="input-group input-group-sm mb-1">
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Descripción
                                                </div>
                                                <input type="text" id="pcDescri" name="paData[CDESCRI]" value="{$saData['CDESCRI']}" class="form-control uppercase" onkeyup="UpperCaseF(this);" autofocus required>
                                             </div>
                                             <div class="input-group input-group-sm mb-1">
                                                <div class="input-group-prepend bg-ucsm px-2">
                                                   Actividad
                                                </div>
                                                <select name="paData[CIDACTI]" class="selectpicker form-control form-control-sm col-8" data-live-search="true">
                                                   {foreach from = $saActivi item = i}
                                                      {*<option value="{$i['CIDACTI']}">{$i['CDESCRI']}</option>*}
                                                      <option value="{$i['CIDACTI']}" {if $i['CIDACTI'] eq $saData['CIDACTI']} selected {/if}>{$i['CDESCRI']}</option>
                                                   {/foreach}
                                                </select>
                                             </div>
                                             <div class="row" id="divComDir">
                                                <div class="col-12">
                                                   <div class="input-group input-group-sm mb-1">
                                                      <div class="input-group-prepend bg-ucsm px-2">
                                                         Nro. RUC
                                                      </div>
                                                      <input type="text" id="pcNroRuc" name="paData[CNRORUC]" value="{$saData['CNRORUC']}" maxlength="11" class="form-control" required>
                                                      <div class="input-group-append">
                                                         <button id="btn_buscarRUC" tabindex="-1" class="btn btn-outline-primary" onclick="f_buscarRUC();">Buscar</button>
                                                      </div>
                                                      <div class="input-group-prepend bg-ucsm px-2">
                                                         Razón Social
                                                      </div>
                                                      <input type="text" id="pcRazSoc" name="paData[CRAZSOC]" value="{$saData['CRAZSOC']}" class="form-control w-25" placeholder="RUC INVALIDO" required readonly>
                                                      <div class="input-group-prepend bg-ucsm px-2">
                                                         Nro. Factura
                                                      </div>
                                                      <input type="text" id="pcNroCom" name="paData[CNROCOM]" value="{$saData['CNROCOM']}" class="form-control" required>
                                                      <div class="input-group-prepend bg-ucsm px-2">
                                                         Monto
                                                      </div>
                                                      <input type="text" id="pnMonto" name="paData[NMONTO]" value="{$saData['NMONTO']}" class="form-control" onblur="f_validateNumber(this, 2);" required>
                                                      <div class="input-group-prepend bg-ucsm px-2">
                                                         Fecha Compra
                                                      </div>
                                                      <input type="text" id="pdFecCom" name="paData[DFECCOM]" value="{$saData['DFECCOM']}" class="form-control datepicker" required readonly>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>          
                                       </div>
                                       <div class="form-row">
                                          <div class="col-sm-12">
                                             <div class="card text-center ">
                                                <div>
                                                   <div class="table-responsive">
                                                      <table class="table table-sm table-hover table-bordered">
                                                         <thead class="bg-ucsm">
                                                            <tr>
                                                               <th scope="col">#</th>
                                                               <th scope="col">Artículo</th>
                                                               <th scope="col">Unidad</th>
                                                               <th class="text-right" scope="col">Cantidad</th>
                                                               <th class="text-right" scope="col">Precio Referencial</th>
                                                               <th class="text-right" scope="col">SubTotal</th>
                                                               <th scope="col"><img src="css/feather/check-square-white.svg"></th>
                                                            </tr>
                                                         </thead>
                                                         <tfoot>
                                                            <tr>
                                                               <td colspan="7" class="text-center">
                                                                  <div class="btn-group btn-group-sm" role="group">
                                                                     <button type="button" class="btn btn-outline-primary"  data-toggle="modal" data-target="#nuevoDetalle">Agregar</button>
                                                                     <button type="button" class="btn btn-outline-primary" onclick="f_eliminarDetalle();">Eliminar</button>
                                                                  </div>
                                                               </td>
                                                            </tr>
                                                         </tfoot>
                                                         <tbody id="detallesRequerimiento">
                                                            {$k = 0}
                                                            {$nTotal = 0}
                                                            {foreach from = $saDatos item = i}
                                                               <tr>
                                                                  <th scope="row">{$k+1}</th>
                                                                  <td class="text-left" data-toggle="tooltip" data-placement="top" title="{$i['CDESDET']}">{$i['CDESART']}</td>
                                                                  <td>{$i['CUNIDAD']}</td>
                                                                  <td class="text-right">{number_format($i['NCANTID'],4)}</td>
                                                                  <td class="text-right">{number_format($i['NPREREF'],4)}</td>
                                                                  <td class="text-right">{number_format($i['NSTOTAL'],2)}</td>
                                                                  <td align="center"><input id="pcIndice" type="radio" name="pnIndice" value="{$k}"></td>
                                                               </tr>
                                                               {$k = $k + 1}
                                                               {$nTotal = $nTotal + $i['NSTOTAL']}
                                                            {/foreach}
                                                            <tr>
                                                               <td></td>
                                                               <td colspan="4" class="text-left">TOTAL</td>
                                                               <td align="right"><input type="hidden" id="pnTotal" name="paData[NTOTAL]" value="{number_format($nTotal,2)}">{number_format($nTotal,2)}</td>
                                                               <td></td>
                                                            </tr>
                                                         </tbody>
                                                      </table>
                                                   </div>
                                                </div>
                                             </div>
                                          </div>
                                       </div>      
                                    </div>
                                    <div class="card-footer text-muted">
                                       <button type="submit" name="Boton1" value="Guardar" class="btn bg-ucsm col-sm-2 col-md-3" onclick="f_validarCampos();">Guardar</button>
                                       <button type="submit" name="Boton1" value="Enviar" class="btn bg-sc-ucsm col-sm-2 col-md-3" onclick="f_validarCampos(); return f_confirmarEnvio();">Enviar</button>
                                       <button type="submit" name="Boton1" value="Cancelar" class="btn btn-danger col-sm-2 col-md-3" formnovalidate>Cancelar</button> 
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>
                     {/if}
                  </div>
               </div>
            </div>
         </form>
      </main>
      <div id="footer"></div>
      <!-- Modal -->
      <div class="modal fade" id="nuevoDetalle" tabindex="-1">
         <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
               <div class="modal-header bg-sc-ucsm">
                  <h5 class="modal-title">Nuevo Detalle de Requerimiento</h5>
                  <button type="button" class="close" data-dismiss="modal">
                     <span aria-hidden="true">&times;</span>
                  </button>
               </div>
               <div class="modal-body">
                  <div class="input-group mb-1 row">
                     <div class="input-group-prepend col-4">
                        Artículo
                     </div>
                     <input type="text" id="pcBusArt" class="form-control uppercase" autofocus>
                     <div class="input-group-append">
                        <button id="btn_buscarArt" class="btn btn-outline-primary" onclick="f_buscarArticulo();" tabindex="-1">Buscar</button>
                     </div>
                  </div>
                  <div class="input-group mb-3 row">
                     <div class="input-group-prepend col-4"></div>
                     <input type="hidden" id="pcDesArt">
                     <select id="pcCodArt" class="selectpicker form-control form-control-sm col-8" data-live-search="true" onchange="f_cargarArticulo();"></select>
                  </div>
                  <div class="input-group mb-3 row">
                     <div class="input-group-prepend col-4">
                        Información Adicional
                     </div>
                     <textarea id="pcDesDet" placeholder="Marca / Modelo / Otros aspectos" class="form-control" rows="3" maxlength="1000"></textarea>
                  </div>
                  <div class="input-group mb-3 row">
                     <div class="input-group-prepend col-4">
                        Unidad
                     </div>
                     <input type="text" id="pcUnidad" class="form-control-plaintext col-8" disabled>
                  </div>
                  <div class="input-group mb-3 row">
                     <div class="input-group-prepend col-4">
                        Cantidad
                     </div>
                     <input type="number" step="0.01" min="0" id="pnCantid" class="form-control" >
                  </div>
                  <div class="input-group mb-3 row">
                     <div class="input-group-prepend col-4">
                        Precio Referencial (Por Unidad)
                     </div>
                     <div class="input-group-prepend">
                        <span id="tipoMoneda" class="input-group-text">S/.</span>
                     </div>
                     <input type="number" step="0.001" min="0" id="pnPreRef" class="form-control" >
                     <input type="hidden" id="pnRefSol">
                     <input type="hidden" id="pnRefDol">
                  </div>
               </div>
               <div class="modal-footer">
                  <button type="button" class="btn bg-ucsm" onclick="f_agregarDetalle();">Agregar Detalle</button>
                  <button type="button" class="btn btn-danger" data-dismiss="modal">Cerrar</button>
               </div>
            </div>
         </div>
      </div>
   </body>
</html>
