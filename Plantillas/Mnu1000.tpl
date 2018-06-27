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
   <body>
      <main role="main">
         <div id="header"></div>
         <div class="container">
            <br>
            <div class="card mb-2">
               <div class="card-header text-center bg-sc-ucsm">
                  Menu de Usuarios
               </div>
               <div class="card-body">
                  <div id="accordion" role="tablist">
                     <div class="card">
                        {foreach from = $saRoles item = i}    
                           <div class="card-header" role="tab" id="{$i['CCODROL']}">
                              <h5 class="mb-0">
                                 <a data-toggle="collapse" href="#collapse-{$i['CCODROL']}" role="button" class="a-ucsm">
                                    {$i['CDESROL']}
                                 </a>
                              </h5>
                           </div>
                           <div id="collapse-{$i['CCODROL']}" class="collapse" role="tabpanel" aria-labelledby="heading-{$i['CCODROL']}" data-parent="#accordion">
                              <div class="card-body">
                                 <div class="list-group">
                                    {foreach from = $saOpcion item = j}
                                       {if $i['CCODROL'] eq $j['CCODROL']}
                                          <a href="{$j['CCODOPC']}.php" class="list-group-item list-group-item-action">{$j['CDESOPC']}</a>
                                       {/if}
                                    {/foreach}
                                 </div>
                              </div>
                           </div>
                        {/foreach}
                     </div>
                  </div>
               </div>
            </div>
            <br>
         </div> <!-- /container -->
      </main>
      <div id="footer"></div>
   </body>
</html>
