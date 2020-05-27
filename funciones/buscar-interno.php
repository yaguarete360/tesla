<?php if (!isset($_SESSION)) {session_start();}

    echo '<div>';
        echo '<table id="busqueda_de_internos">';
        echo '</table>';
    echo '</div>';

    $funcionarios = array();
    $consulta_busqueda_de_internos = 'SELECT organigrama, numero_interno FROM organigrama WHERE borrado LIKE "no" ORDER BY organigrama ASC';//AND numero_interno NOT LIKE "0"
    $query_busqueda_de_internos = $conexion->prepare($consulta_busqueda_de_internos);
    $query_busqueda_de_internos->execute();

    while($rows_b_d_i = $query_busqueda_de_internos->fetch(PDO::FETCH_ASSOC))
    {       
        $funcionarios[] = ucwords($rows_b_d_i['organigrama'])."-".$rows_b_d_i['numero_interno'];
    }

?>

<script type="text/javascript">
  
    var funcionarios_1 = '<?php echo json_encode($funcionarios); ?>';
    var funcionarios = JSON.parse(funcionarios_1);
  // var funcionarios = [
  //   'Mirna Brizuela - 100',
  //   'Miguel Ibañez - 102',
  //   'Luis Ricardo Romero - 122',
  //   'Hugo Maidana - 138',
  //   'Lic. Alberto Lezcano - 117',
  //   'Laura Jara - 114',
  //   'Sandra Marinez - 115',
  //   'Nancy Diaz - 116',
  //   'Gustavo Gimenez - 135',
  //   'Roberto Leon - 136',
  //   'Victor Martinez - 140',
  //   'Patricia Nuñez - 146',
  //   'Cecilia Ortega - 147',
  //   'Javier Nuñez - 155',
  //   'Marina Prieto - 157',
  //   'Ofelia Caceres - 159',
  //   'Juan Benitez - 160',
                                        //   'Dr. Fernando Hellmers - 145',
  //   'Gisela Ocampos - 128',
  //   'Renato Samaniego - 118',
  //   'Francisco Peralta - 119',
  //   'Isabel Picco/Joel Vargas - 123',
  //   'Sergio Baez - 129',
  //   'Lic. Dora Otazu - 141',
                                        //   'Sr. Fernando Ibarra - 200',
  //   'Carlos Bobadilla - 144',
  //   'Roberto Gonzalez - 124',
  //   'Cantina - 125',
  //   'Gustavo Espinola - 151',
  //   'Habitacion de Servicios - 154',
  //   'Fax de Servicios - 155',
  //   'Fax de Recepcion - 104',
  //   'Vendedor de Guardia - 156',
  //   'Gilda Hellmers - 103',
  //   'Vendedor de Guardia en Administracion - 120',
  //   'Aurora Apesteguia - 130',
  //   'Maria Gloria Peralta - 131',
  //   'Cinthia Ovelar - 132',
  //   'Sala de Ventas - 133/137',
  //   'Stephanie Hellmers - 139',
  //   'Arq. Enrique Hellmers / Parque - 101',
  //   'Arq. Enrique Hellmers / Dream - 201',
  //   'Lic. Paola Enciso - 112',
  //   'Lic. Gustavo Alvariza - 105',
  //   'Guadalupe Fernandez - 142',
  //   'Martin Bernal/Jorge Amarilla/Jorge Olmedo - 203',
  //   'Oscar Saucedo/Alberto B./Ronnie V. - 161'
  // ];

    $('#q').keyup(function(){

        var interno_a_buscar = this.value.split(' ');

        var tabla_busqueda_de_internos = $('#busqueda_de_internos');
        tabla_busqueda_de_internos.empty();

        if(interno_a_buscar[0] && interno_a_buscar[0].length > 2)
        {
            for (var i = 0; i < funcionarios.length; i++) {
                
                var encontro_el_funcionario = 0;
                for (var x = 0; x < interno_a_buscar.length; x++) {
                    
                    if (funcionarios[i].toLowerCase().indexOf(interno_a_buscar[x]) >= 0) encontro_el_funcionario++;

                };

                if(encontro_el_funcionario == interno_a_buscar.length)
                {
                    var este_funcionario = funcionarios[i].split('-');
                    tabla_busqueda_de_internos.append($('<tr>')
                        .append($('<td style="padding-left:150px;white-space:nowrap;">').text(este_funcionario[0]))
                        .append($('<td style="padding-left:15px;white-space:nowrap;">').text(este_funcionario[1]))
                    );
                }

            };
        }
    });

</script>
