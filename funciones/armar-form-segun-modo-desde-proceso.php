<?php if(!isset($_SESSION)) {session_start();}
?>

<script type="text/javascript">
  $(document).ready(function()
  {
   
    $('#modo-prepago,#modo-particular').change(function()
    {

      modoActual = this.value;

      if(modoActual == "prepago")
      {
        document.getElementById("armar-contrato-destino").value = "";

        document.getElementById("td_cooperativa_o_asociacion").style.display = 'table-cell';
        document.getElementById("td_label_cooperativa_o_asociacion").style.display = 'table-cell';

        document.getElementById("td_contrato_prepago").style.display = 'table-cell';
        document.getElementById("td_label_contrato_prepago").style.display = 'table-cell';

        linea = "1";//este es el valor default
        if(document.getElementById("armar-contrato-linea").value) linea = document.getElementById("armar-contrato-linea").value;

        centro = "psm";//este es el valor default
        if(document.getElementById("armar-contrato-centro").value) centro = document.getElementById("armar-contrato-centro").value;

        numero = "0000000";//este es el valor default
        if(document.getElementById("armar-contrato-numero").value) numero = document.getElementById("armar-contrato-numero").value;
        if(numero == "")
        {
          numero = "0";
        }
        numero = ('0000000'+numero).substring(numero.length);
        con_final = linea+"-"+centro+"-"+numero;
      
        document.getElementById("armar-contrato-destino").value = con_final;

      }
      else if(modoActual == "particular")
      {
        document.getElementById("td_cooperativa_o_asociacion").style.display = 'none';
        document.getElementById("td_label_cooperativa_o_asociacion").style.display = 'none';

        document.getElementById("td_contrato_prepago").style.display = 'none';
        document.getElementById("td_label_contrato_prepago").style.display = 'none';
        
        document.getElementById("armar-contrato-destino").value = "no aplicable";
      }
    });
  });

</script>
