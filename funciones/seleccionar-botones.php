<?php if(!isset($_SESSION)) {session_start();}
echo '<select class="datos" name="'.$variable_nombre.'" id="'.$variable_nombre.'" value="'.$valor.'"/>';
	switch ($campo_atributo['herramientas']) 
	{
		case 'tipo_de_documento':
			$selecciones = array(
			'administrativos',
			'bancarios',
			'compras',
			'contables',
			'contractuales',
			'identificaciones',
			'laborales',
			'ventas',
			''
			);
		break;
		case 'motivo_reposicion':
			$selecciones = array(
			'El Feretro estaba podrido',
			''
			);
		break;
		case 'mortaja_color':
			$selecciones = array(
			'',
			'Blanco',
			'Marron',
			'Beige'
			);
		break;
		case 'mortaja_tipo':
			$selecciones = array(
			'',
			'Sencilla',
			'Plus'
			);
		break;
		case 'caracter_de':
			$selecciones = array(
			'',
			'Padre',
			'Madre',
			'Hijo/a',
			'Esposo/a',
			'Hermano/a',
			'Sobrino/a',
			'Primo/a',
			'Nieto/a',
			'Abuelo/a',
			'Amigo/a',
			'Apoderado/a'
			);
		break;
		case 'capilla':
			$selecciones = array(
			'',
			'Crematorio, Villa Elisa',
			'Domicilio',
			'En espera',
			'España y Boquerón, Celestial',
			'España y Boquerón, Internacional',
			'España y Boquerón, La Piedad',
			'España y Boquerón, Serenidad',
			'Mariano, Capilla 1',
			'Mariano, Capilla 2',
			'Mariscal López, Capilla 1',
			'Mariscal López, Capilla 2',
			'Mariscal López, Capilla 3',
			'Mariscal López, Privado 1',
			'Mariscal López, Privado 2',
			'Otro',
			'Parque, Villa Elisa',
			'S/D',
			'Sajonia, Capilla 1',
			'Sajonia, Capilla 2',
			'San Lorenzo, Capilla 1',
			'San Lorenzo, Capilla 2'
			);
		break;
		case 'marcas':
			$selecciones = array(
			'',
			'Chevrolet',
			'Daihatsu',
			'Ford',
			'Fiat',
			'Hyundai',
			'Jeep',
			'Kia',
			'Nissan',
			'Mahindra',
			'Mercedes Benz',
			'Suzuki',
			'Ssanyong',
			'Toyota',
			'Volvo',
			'Audi',
			'Mitsubishi',
			'Isuzu',
			'BMW',
			'Renault',
			'Mazda',
			'Peugeot',
			'Porsche',
			'Subaru',
			'Volkswagen'
			);
		break;
		case 'colores':
			$selecciones = array(
			'',
			'Amarillo',
			'Azul',
			'Blanco',
			'Bordo',
			'Dorado',
			'Gris',
			'Lila',
			'Negro',
			'Plateado',
			'Rojo',
			'Rosado'
			);
		break;
		case 'locales':
			$selecciones = array(
			'Estacionamiento Boqueron',
			'Estacionamiento Mariscal Lopez',
			'Estacionamiento Mariano',
			'Estacionamiento Sajonia',
			'Estacionamiento San Lorenzo'
			);
		break;
		case 'origen':
			$selecciones = array(
			'PSM',
			'PSV',
			'Particular'
			);
		break;
		case 'adicional':
			$selecciones = array(
			'no',
			'si'
			);
		break;
		case 'motivos':
			$selecciones = array(
			'Reparacion de feretros',
			'Reparacion de vehiculos',
			'Mantenimiento de vehiculos',
			'Visita para ventas',
			'Trasporte para cremacion',
			'Busqueda de sepelios',
			'Busqueda de feretros',
			'Tramites de sepelios',
			'Cortejo funebre',
			'Traslado de inhumaciones',
			'Entrega de sepelios',
			'Entrega de feretros',
			'Asistencia mecanica',
			'Recorrida de taller',
			'Recorrida de obras',
			'Depsoitos bancarios',
			'Gestion administrativa',
			'Usos de Gerencia Tecnica',
			'Usos de Gerencia General',
			'Usos de Direccion Ejecutiva',
			'Cargas de combustible',
			'Otros motivos'
			);
		break;
		case 'agrupadores':
			$selecciones = array(
			'edificios',
			'entornos',
			'muebles',
			'vehiculos',
			'maquinas',
			'equipos'
			);
		break;
		case 'calificacion':
			$selecciones = array(
			'No contesta',
			'5-excelente',
			'4-bueno',
			'3-regular',
			'2-bajo',
			'1-pesimo'
			);
		break;
		case 'ciclo':
			$selecciones = array(
			'puntual',
			'diario',
			'semanal',
			'mensual',
			'trimestral',
			'semestral',
			'anual'
			);
		break;
		case 'dia':
			$selecciones = array(
			'lunes',
			'martes',
			'miercoles',
			'jueves',
			'viernes',
			'sabado',
			'domingo'
			);
		break;
		case 'inoperatividad':
			$selecciones = array(
			'',
			'no hay repuestos en plaza',
			'se esta importando unidad',
			'se esta importando repuesto',
			'incumplimiento de proveedores',
			'incumplimiento de funcionarios',
			'incumplimiento de tercerizados',
			'no se sabe la causa'
			);
		break;
		case 'rubros':
			$selecciones = array(
			'',
			'gastos',
			'labores',
			'materiales',
			'salida',
			'entrada'
			);
		break;
		case 'subrubros':
			$selecciones = array(
			'',
			'reparaciones',
			'mantenimientos',
			'busqueda',
			'tramites',
			'cortejo',
			'translado',
			'entrega',
			'asistencia',
			'recorrida',
			'administracion',
			'tecnica',
			'gerencia',
			'direccion'
			);
		break;
		case 'subsubrubros':
			$selecciones = array(	
			'',
			'motor',
			'transmicion',
			'suspencion',
			'chaperia',
			'pintura',
			'aire',
			'electricidad',
			'tapizado',
			'decorado',
			'accesorios'
			);
		break;
		case 'sexo':
			$selecciones = array(
			'femenino',
			'masculino'
			);
		break;
		case 'relacion':
			$selecciones = array(
			'dependiente',
			'tercerizado',
			'externa',
			);
		break;
		case 'estado':
			$selecciones = array(
			'soltero/a',
			'casado/a',
			'divorciado/a',
			'viudo/a'
			);
		break;
	}
	natsort($selecciones);
	foreach($selecciones as $seleccion)
	{
		if(strtolower($seleccion) === strtolower($valor))
		{
			echo '<option value="'.strtolower($seleccion).'"selected>'.ucwords($seleccion).'</option>';		
		}
		else
		{
		echo '<option value="'.strtolower($seleccion).'">'.ucwords($seleccion).'</option>';
		}
	}	
echo '</select>';
?>