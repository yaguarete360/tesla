<?php if (!isset($_SESSION)) {session_start();}

  if(!isset($valor)) $valor = "";
  if(isset($rows[$campo_nombre])) $valor = $rows[$campo_nombre];

  echo '<input type="text" name="'.$campo_nombre.'" class="datos" id="'.$campo_nombre.'" value="'.$valor.'" readonly/>';

?>

<script type="text/javascript">

var nacimiento_id = document.getElementsByName('<?php echo $campo_nacimiento; ?>')[0].getAttribute('id');
var defuncion_id = document.getElementsByName('<?php echo $campo_defuncion; ?>')[0].getAttribute('id');

  document.getElementById(nacimiento_id).onchange=function(){

    var nacimiento = document.getElementById(nacimiento_id).value;
    var nacimiento_desglosado = nacimiento.split("-");
    var nacimiento_ano = nacimiento_desglosado[0];
    var nacimiento_mes = nacimiento_desglosado[1];
    var nacimiento_dia = nacimiento_desglosado[2];
    
    var defuncion = document.getElementById(defuncion_id).value;
    var defuncion_desglosado = defuncion.split("-");
    var defuncion_ano = defuncion_desglosado[0];
    var defuncion_mes = defuncion_desglosado[1];
    var defuncion_dia = defuncion_desglosado[2];

    var edad_anos = defuncion_ano - nacimiento_ano;

    if(nacimiento_mes > defuncion_mes)
    {
      edad_final = edad_anos - 1;
    }
    else if(nacimiento_mes = defuncion_mes)
    {
      if(nacimiento_dia > defuncion_dia)
      {
        edad_final = edad_anos - 1;
      }
      else
      {
        edad_final = edad_anos;
      }
    }
    else
    {
      edad_final = edad_anos;
    }
    
    if(edad_final < 0)
    {
      edad_final = 0;
    }
    
    document.getElementById('<?php echo $campo_nombre; ?>').value = edad_final;

  };
    
  document.getElementById(defuncion_id).onchange=function(){
    
    var nacimiento = document.getElementById(nacimiento_id).value;
    var nacimiento_desglosado = nacimiento.split("-");
    var nacimiento_ano = nacimiento_desglosado[0];
    var nacimiento_mes = nacimiento_desglosado[1];
    var nacimiento_dia = nacimiento_desglosado[2];
    
    var defuncion = document.getElementById(defuncion_id).value;
    var defuncion_desglosado = defuncion.split("-");
    var defuncion_ano = defuncion_desglosado[0];
    var defuncion_mes = defuncion_desglosado[1];
    var defuncion_dia = defuncion_desglosado[2];

    var edad_anos = defuncion_ano - nacimiento_ano;

    if(nacimiento_mes > defuncion_mes)
    {
      edad_final = edad_anos - 1;
    }
    else if(nacimiento_mes = defuncion_mes)
    {
      if(nacimiento_dia > defuncion_dia)
      {
        edad_final = edad_anos - 1;
      }
      else
      {
        edad_final = edad_anos;
      }
    }
    else
    {
      edad_final = edad_anos;
    }
    
    if(edad_final < 0)
    {
      edad_final = 0;
    }
    document.getElementById('<?php echo $campo_nombre; ?>').value = edad_final;

  };
  
</script>
