<?php if (!isset($_SESSION)) {session_start();}

$lineas = array("1", "2");

$valores = array();
$x = 0;
foreach ($lineas as $linea)
{   
    $consultaPoblacion = 'SELECT *
    FROM '.$tabla_seleccion.'
    WHERE borrado = "no"
    ORDER BY "'.$campo_seleccion.'"
    ASC
    ';
    $queryPoblacion = $conexion->prepare($consultaPoblacion);
    $queryPoblacion->execute();
    while($rowsPoblacion = $queryPoblacion->fetch(PDO::FETCH_ASSOC))
    {
        $valores[$x] = $rowsPoblacion[$campo_nombre];
        $x++;
    }
}

array_multisort($valores);
$posicion1 = array_search("2-ncs-0000001", $valores);
$NCS_1 = array_slice($valores, 0, $posicion1);
$NCS_2 = array_slice($valores, $posicion1);

//print_r($NCS_1);
//print_r($NCS_2);

$sumador1 = end($NCS_1);
$sumador1N = str_pad(substr(strstr(substr(strstr($sumador1, "-"),1),"-"),1) + 1, "0", 7,STR_PAD_LEFT);
$sumador2 = end($NCS_2);
$sumador2N = str_pad(substr(strstr(substr(strstr($sumador2, "-"),1),"-"),1) + 1, "0", 7,STR_PAD_LEFT);
$valor_a_traer =  '1-ncs-'.str_pad($sumador1N, 7, "0",STR_PAD_LEFT);
?>

<script type="text/javascript">

var sumador1N = '<?php echo $sumador1N; ?>';
var sumador2N = '<?php echo $sumador2N; ?>';
$(document).ready(function()
{
    $('#refrescado').change(function()
    {
        var seleccion = this.value;
        if(seleccion === "1")
        {
            var numerador = sumador1N;
            var numFin = "1-ncs-"+("0000" + numerador).slice(-7);
        }
        else
        {
            var numerador = sumador2N;
            var numFin = "2-ncs-"+("0000" + numerador).slice(-7);
        }
    document.getElementById("refrescado-objetivo").setAttribute('value', numFin);
    });
});

</script>
