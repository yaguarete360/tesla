<?php if(!isset($_SESSION)) {session_start();}
?>

<script type="text/javascript">
  $(document).ready(function()
  {
    cooperativa_defecto = document.getElementById("cooperativa_o_asociacion").selectedIndex;
    
    $('input[type=radio][name=modo]').change(function() {
      if (this.value == 'prepago')
      {
        document.getElementById("armar-contrato-destino").value = "";

        document.getElementById("td_capitulo_cooperativa_o_asociacion").style.display = 'table-cell';
        document.getElementById("label_cooperativa_o_asociacion").style.display = 'initial';
        document.getElementById("td_label_cooperativa_o_asociacion").style.display = 'table-cell';

        document.getElementById("td_capitulo_contrato_prepago").style.display = 'table-cell';
        document.getElementById("label_contrato_prepago").style.display = 'initial';
        document.getElementById("td_label_contrato_prepago").style.display = 'table-cell';

        linea = "1";//este es el valor default
        linea = document.getElementById("armar-contrato-linea").value;

        centro = "PSM";//este es el valor default
        centro = document.getElementById("armar-contrato-centro").value;

        numero = "0000000";//este es el valor default
        numero = document.getElementById("armar-contrato-numero").value;
        if(numero == "")
        {
          numero = "0";
        }
        numero = ('0000000'+numero).substring(numero.length);
        con_final = linea+"-"+centro+"-"+numero;
      
        document.getElementById("armar-contrato-destino").value = con_final;
        
        document.getElementById("cooperativa_o_asociacion").selectedIndex = cooperativa_defecto;
        var coop_o_aso = document.getElementById("cooperativa_o_asociacion");
        if (coop_o_aso.length > 0) {
            coop_o_aso.remove(coop_o_aso.length-1);
        }
      }
      else if (this.value == 'particular')
      {
        document.getElementById("td_capitulo_cooperativa_o_asociacion").style.display = 'none';
        document.getElementById("label_cooperativa_o_asociacion").style.display = 'none';
        document.getElementById("td_label_cooperativa_o_asociacion").style.display = 'none';

        document.getElementById("armar-contrato-destino").value = "no aplicable";
        document.getElementById("td_capitulo_contrato_prepago").style.display = 'none';
        document.getElementById("label_contrato_prepago").style.display = 'none';
        document.getElementById("td_label_contrato_prepago").style.display = 'none';
        
        cooperativa_defecto = document.getElementById("cooperativa_o_asociacion").selectedIndex;
        document.getElementById("cooperativa_o_asociacion").add(new Option('No aplicable', 'no aplicable', true));
      }
    });
  });

</script>
