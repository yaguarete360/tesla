<?php if (!isset($_SESSION)) {session_start();}

    $herramientas = explode("_", $campo_atributo['herramientas']);
    $carpeta = '../imagenes/'.$herramientas[0]."/";
    if(isset($_POST["grabar"]))
    {
        echo '<div class="div_cargando"></div>';
        $extension = explode(".", $_FILES[$campo_nombre.'-foto']["name"]);
        $tipo_de_imagen = end($extension);
        $uploadOk = 1;
        switch ($variable_nombre)
        {
            case 'organigrama_foto':
                $organigrama_foto_1 = str_replace('Ñ', 'ñ', $_POST[$herramientas[2]]);
                $funcionario_nombre = str_replace(', ', '-', strtolower($organigrama_foto_1));
                $nuevo_nombre = $funcionario_nombre.'.'.end($extension);
            break;
            
            default:
                $nuevo_nombre = $numero_a_usar.'-'.$herramientas[1].'.'.end($extension);
            break;
        }
        $_POST[$campo_nombre] = $nuevo_nombre;

        // Check if image file is a actual image or fake image
        if(isset($_FILES[$campo_nombre.'-foto']))
        {
            $check = getimagesize($_FILES[$campo_nombre.'-foto']["tmp_name"]);
            if($check !== false)
            {
                // echo "File is an image - " . $check["mime"] . ".";
                $uploadOk = 1;
            }
            else
            {
                echo "El archivo no es una foto.";
                $uploadOk = 0;
            }

            // Check if file already exists
            if (file_exists($nuevo_nombre))
            {
                echo "La foto ya existe.";
                $uploadOk = 0;
            }

            // Check file size
            if ($_FILES[$campo_nombre.'-foto']["size"] > 10000000)
            {
                echo "La foto es muy pesada. Maximo de 10mb";
                $uploadOk = 0;
            }

            // Allow certain file formats
            if($tipo_de_imagen != "jpg" && $tipo_de_imagen != "png" && $tipo_de_imagen != "jpeg")
            {
                echo "Solo se permiten archivos de formato JPG, PNG y JPEG.";
                $uploadOk = 0;
            }

            // Check if $uploadOk is set to 0 by an error
            if ($uploadOk == 0)
            {
                echo "Ha ocurrido un ERROR al subir.";
            // if everything is ok, try to upload file
            }
            else
            {
                if (move_uploaded_file($_FILES[$campo_nombre.'-foto']["tmp_name"], $carpeta.$nuevo_nombre))
                {
                    echo "La foto ".$nuevo_nombre." ha sido subida.";
                }
                else
                {
                    echo "Ha ocurrido un ERROR al subir.";
                }
            }
        }
        else
        {
            echo 'No se subio la foto '.$numero_a_usar;
        }
    }
    else
    {
        echo '<input type="file" name="'.$campo_nombre.'-foto" id="'.$campo_nombre.'-foto">';
        echo '<input type="hidden" name="'.$campo_nombre.'" id="'.$campo_nombre.'" value="">';
    }

?>

<script>

    $(window).on('load', function(){
        $( ".div_cargando" ).fadeOut(500, function() {
            $( ".div_cargando" ).remove();
        });  
    });

</script>
