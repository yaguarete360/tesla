<?php if(!isset($_SESSION)) {session_start();}

  $i_monto_lista = 0;
  
  $consulta_seleccion = 'SELECT descripcion, dato_1
  FROM agrupadores
  WHERE borrado = "no"
    AND agrupador LIKE "productos" 
  ORDER BY descripcion
  ASC';
  
  $query_seleccion = $conexion->prepare($consulta_seleccion);
  $query_seleccion->execute();
  
  while($rows_seleccion = $query_seleccion->fetch(PDO::FETCH_ASSOC))
  {
    $productos[$i_monto_lista] = $rows_seleccion['descripcion'];
    $precios[$i_monto_lista] = $rows_seleccion['dato_1'];

    $i_monto_lista++;
  }

?>

<script type="text/javascript">

<?php  
  $productos_js = json_encode($productos);
  $precios_js = json_encode($precios);
  echo "var productos = ". $productos_js . ";\n";
  echo "var precios = ". $precios_js . ";\n";
?>

  console.log(productos);
  console.log(precios);
  document.getElementById("producto").onchange=function(){
    var producto_seleccionado = this.value;
    var posicion_seleccionada = productos.indexOf(producto_seleccionado);
  console.log(producto_seleccionado);
  console.log(precios[posicion_seleccionada]);
    if(precios[posicion_seleccionada])
    {
      document.getElementById("monto_lista").value = precios[posicion_seleccionada];
    }
    else
    {
      document.getElementById("monto_lista").value = "0";
    }
  };

</script>
