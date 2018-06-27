$(function () {
   $("#header").load("Plantillas/xheader.html");
   $("#headerp").load("Plantillas/xheaderp.html");
   $("#footer").load("Plantillas/xfooter.html");
   $('[data-toggle="tooltip"]').tooltip();
   $(".datepicker").datepicker({dateFormat: 'yy-mm-dd'});
})

function UpperCaseF(a) {
   a.value = a.value.toUpperCase();
}

function isNumber(n) {
   return !isNaN(parseFloat(n)) && isFinite(n);
}

function f_validateNumber(e, decimals) {
   var num = e.value;
   if (!isNaN(parseFloat(num)) && isFinite(num)) {
      e.value = parseFloat(Math.round(num * 100) / 100).toFixed(decimals);
   } else {
      e.value = 0;
   }
}
