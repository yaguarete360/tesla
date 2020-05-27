<?php if (!isset($_SESSION)) {session_start();}

    $unidades_s = ',un,dos,tres,cuatro,cinco,seis,siete,ocho,nueve';
    $unidades_a = explode(',', $unidades_s);

    $decimos_10_s = ',once,doce,trece,catorce,quince,dieciseis,diecisiete,dieciocho,diecinueve';
    $decimos_10_a = explode(',', $decimos_10_s);

    $decimos_20_s = ",veintiun,veintidos,veintitres,veinticuatro,veinticinco,veintiseis,veintisiete,veintiocho,veintinueve";
    $decimos_20_a = explode(",", $decimos_20_s);

    $decimos_s = ",diez,veinte,treinta,cuarenta,cincuenta,sesenta,setenta,ochenta,noventa";
    $decimos_a = explode(",", $decimos_s);

    $cientos_s = ",ciento,doscientos,trescientos,cuatrocientos,quinientos,seiscientos,setecientos,ochocientos,novecientos";
    $cientos_a = explode(",", $cientos_s);

    $millones_s = ",mil,millones,mil,billones,mil,trillones,mil,cuatrillones,mil,quintillones";
    $millones_a = explode(",", $millones_s);

    $string_final = '';
    $palabras_finales = array();
    $parte_palabras = array();

    $monto_a_usar = number_format(round($monto_a_usar)); // redondear, formatear con comas para explode;
    $monto_a_usar_partes_r = array_reverse(explode(',', $monto_a_usar));

    $tiene_millon = false;
    foreach ($monto_a_usar_partes_r as $pos => $parte)
    {
        $parte_palabras = array();
        $parte = str_pad($parte, 3, '0', STR_PAD_LEFT);
        
        switch ($parte)
        {
            case '000':
                if($millones_a[$pos] != 'mil' and $tiene_millon) $parte_palabras[] = $millones_a[$pos];
                if(strpos($millones_a[$pos], 'illon') !== false) $tiene_millon = true;
            break;
            
            case '001':
                $parte_palabras[] = 'un';
                if(!empty($millones_a[$pos])) $parte_palabras[] = rtrim($millones_a[$pos], 'es');
            break;

            case '100':
                $parte_palabras[] = 'cien';
                if(!empty($millones_a[$pos])) $parte_palabras[] = $millones_a[$pos];
            break;
            
            default:
                if(!empty($cientos_a[$parte[0]])) $parte_palabras[] = $cientos_a[$parte[0]];
                if(($parte[1] == 1 or $parte[1] == 2) and $parte[2] != 0)
                {
                    $decimos_a_usar = ($parte[1] == 1) ? $decimos_10_a : $decimos_20_a;
                    if(!empty($decimos_a_usar[$parte[2]])) $parte_palabras[] = $decimos_a_usar[$parte[2]];
                }
                else
                {
                    if(!empty($decimos_a[$parte[1]])) $parte_palabras[] = $decimos_a[$parte[1]];
                    $usar_y = ($parte[2] != 0 and !empty($decimos_a[$parte[1]])) ? 'y ' : '';
                    if(!empty($unidades_a[$parte[2]])) $parte_palabras[] = $usar_y.$unidades_a[$parte[2]];
                }
                if(!empty($millones_a[$pos])) $parte_palabras[] = $millones_a[$pos];
            break;
        }

        $palabras_finales[] = implode(' ', $parte_palabras);
    }

    $string_final = str_replace('  ', ' ', implode(' ', array_reverse($palabras_finales)));

?>
