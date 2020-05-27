<?php if(!isset($_SESSION)) {session_start();}

$nombresNormales = ",un,dos,tres,cuatro,cinco,seis,siete,ocho,nueve";
$nombresNormales = explode(",", $nombresNormales);

$nombresDecimos10 = ",once,doce,trece,catorce,quince,dieciseis,diecisiete,dieciocho,diecinueve";
$nombresDecimos10 = explode(",", $nombresDecimos10);

$nombresDecimos20 = ",veintiun,veintidos,veintitres,veinticuatro,veinticinco,veintiseis,veintisiete,veintiocho,veintinueve";
$nombresDecimos20 = explode(",", $nombresDecimos20);

$nombresDecimos = ",diez,veinte,treinta,cuarenta,cincuenta,sesenta,setenta,ochenta,noventa";
$nombresDecimos = explode(",", $nombresDecimos);

$nombresCientos = "cien,ciento,doscientos,trescientos,cuatrocientos,quinientos,seiscientos,setecientos,ochocientos,novecientos";
$nombresCientos = explode(",", $nombresCientos);

if(isset($montoAUsar) and !empty($montoAUsar) and $montoAUsar != "N/A")
{
    $montoAUsar = str_replace(".", "", $montoAUsar);
    $montoAUsar = str_replace(",", "", $montoAUsar);

    $largo = strlen($montoAUsar);
    $montoFinal = "";

    $montoAUsar0 = ltrim($montoAUsar, '0');
    $montoAUsarA = strrev($montoAUsar0);
    $montoAUsarB = chunk_split($montoAUsarA,3,"-");
    $montoAUsarC = strrev($montoAUsarB);
    $montoAUsarF = explode("-", $montoAUsarC);
    $vecesTres = count($montoAUsarF);

    $q = $vecesTres-1;
    
    $palabrasFinales = array();
    foreach ($montoAUsarF as $montoAUsarFI)
    {
        if(!empty($montoAUsarFI))
        {
            $largoInd = strlen($montoAUsarFI);
            if($largoInd == "3")
            {
                if($montoAUsarFI[1] == "1")
                {
                    if($montoAUsarFI[2] == "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[1];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos10[$montoAUsarFI[2]];
                    }
                }
                elseif($montoAUsarFI[1] == "2")
                {
                    if($montoAUsarFI[2] == "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[2];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos20[$montoAUsarFI[2]];
                    }
                }
                elseif($montoAUsarFI != "000")
                {
                    if($montoAUsarFI[2] != "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[$montoAUsarFI[1]]." y ".$nombresNormales[$montoAUsarFI[2]];
                    }
                    elseif($montoAUsarFI[1] == "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[$montoAUsarFI[1]]." ".$nombresNormales[$montoAUsarFI[2]];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos[$montoAUsarFI[1]];
                    }
                }
                else
                {
                    $palabrasFinales[$q] = "";
                }

                if($montoAUsarFI == "100")
                {
                    $palabrasFinales[$q] = "cien";
                }
                elseif($montoAUsarFI[0] != "0")
                {
                    $palabrasFinales[$q] = $nombresCientos[$montoAUsarFI[0]]." ".$palabrasFinales[$q];
                }
            }

            if($largoInd == "2")
            {
                if($montoAUsarFI[0] == "1")
                {
                    if($montoAUsarFI[1] == "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[1];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos10[$montoAUsarFI[1]];
                    }
                }
                elseif($montoAUsarFI[0] == "2")
                {
                    if($montoAUsarFI[1] == "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[2];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos20[$montoAUsarFI[1]];
                    }
                }
                else
                {
                    if($montoAUsarFI[1] != "0")
                    {
                        $palabrasFinales[$q] = $nombresDecimos[$montoAUsarFI[0]]." y ".$nombresNormales[$montoAUsarFI[1]];
                    }
                    else
                    {
                        $palabrasFinales[$q] = $nombresDecimos[$montoAUsarFI[0]];
                    }
                }
            }

            if($largoInd == "1")
            {
                $palabrasFinales[$q] = $nombresNormales[$montoAUsarFI[0]];
            }
        }

        if($q == "0") break;
        $q--;
    }

    $mil = " mil ";
    $millon = " millon ";
    $millones = " millones ";
    $largoFinal = count($palabrasFinales);

    if($largoFinal == "4")
    {
        array_splice($palabrasFinales, 1, 0, $mil);

        if($palabrasFinales[2] == "un")
        {
            array_splice($palabrasFinales,3, 0, $millon);
        }
        else
        {
            array_splice($palabrasFinales, 3, 0, $millones);
        }

        if(!empty($palabrasFinales[4]))
        {
            array_splice($palabrasFinales, 5, 0, $mil);
        }
    }

    if($largoFinal == "3")
    {
        if($palabrasFinales[2] == "un")
        {
            array_splice($palabrasFinales, 1, 0, $millon);
        }
        else
        {
            array_splice($palabrasFinales, 1, 0, $millones);
        }

        if(!empty($palabrasFinales[2]))
        {
            array_splice($palabrasFinales, 3, 0, $mil);
        }
    }

    if($largoFinal == "2")
    {
        array_splice($palabrasFinales, 1, 0, $mil);
    }

    foreach ($palabrasFinales as $palabraFinal)
    {
        $montoFinal = $montoFinal." ".$palabraFinal;
    }
    //echo '<br/>';

    $montoFinalLetras = trim($montoFinal);

    //echo $montoAUsar0.": ".$montoFinalLetras;
}
else
{
    $montoFinalLetras = "";
}

?>