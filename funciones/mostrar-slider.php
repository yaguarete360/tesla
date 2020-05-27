<?php if (!isset($_SESSION)) {session_start();}

echo '<ol class="carousel-indicators">';   
    
    $first = true; 
    $count = 0; 
    
    // if($orden == "des") 
    // {
    //     $archivos = scandir($url.'imagenes/'.$directorio.'/',SCANDIR_SORT_DESCENDING);
    // }
    // else
    // {   
        $archivos = scandir($url.'imagenes/'.$directorio.'/',SCANDIR_SORT_ASCENDING);
    // }
    
    foreach($archivos as $archivo_nombre => $archivo)
    {
        if($archivo != '.' and 
           $archivo != '..' and 
           $archivo != '.DS_Store' and
           $archivo != 'error_log'
        )
        {               
            $slides[$archivo_nombre] = $archivo;
        }
    }

    foreach($slides as $slide_nombre => $slide)
    {
        echo '<li data-target="#slider-interna" data-slide-to="'.$count++.'" class="'.($first ? 'active' : '').'"></li>';
        if($first) $first = false; 
    }
        
echo '</ol>';

echo '<div class="carousel-inner" role="listbox">';
    
    $first = true;
    // $vuelta_numero = 0;
    // $vueltas_con_href = array(0,1);
    // $poner_href = ($directorio == "acceso-principal");
    foreach($slides as $vuelta => $slide)
    {
        $slide_explotado = explode('-', $slide)[0];
        $poner_href = ($slide_explotado[(strlen($slide_explotado)-1)] == 'w' or $slide_explotado[(strlen($slide_explotado)-1)] == 'x');
        if($slide_explotado[(strlen($slide_explotado)-1)] == 'w') $href = 'https://api.whatsapp.com/send?phone=+595986106382';
        if($slide_explotado[(strlen($slide_explotado)-1)] == 'x') $href = 'https://www.parqueserenidad.com/funciones/firmar-libro-de-firmas.php';
        echo '<div class="item'.($first ? ' active' : '').'">';
            // if(in_array($vuelta_numero, $vueltas_con_href) and $poner_href) echo '<a href="https://api.whatsapp.com/send?phone=+595986106382">';
            if($poner_href) echo '<a href="'.$href.'">';
                echo '<img src="'.$url.'imagenes/'.$directorio.'/'.$slide.'" alt="Parque Serenidad">';
            // if(in_array($vuelta_numero, $vueltas_con_href) and $poner_href) echo '</a>';
            if($poner_href) echo '</a>';
        echo '</div>';
        if($first) $first = false; 
        // $vuelta_numero++;
    };

echo '</div>';

?>
