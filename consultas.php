<?php 
//Script para conexión con base de datos en Mysql
include("db_controller/mysql_script.php");

// Add Libreria Dompdf 
require_once("lib/dompdf/dompdf_config.inc.php");

// Obtenemos parametros
$obj = (object)$_REQUEST;

switch ($obj->action) {
  case 'exportToPDF':
    //Obtenemos los 100 primeros registros mediante la siguiente consulta
    $rows = query("SELECT * FROM personal ORDER BY nombres ASC LIMIT 100");
  	// Creamos una tabla con todos los datos obtenidos de MYSQL
  	$html = '
	  	<html lang="en">
		<head>
			<meta charset="UTF-8">
			<title>Reporte en PDF</title>
			<style>
				table, td, th {    
				    border: 1px solid #ddd;
				    text-align: left;
				}

				table {
				    border-collapse: collapse;
				    width: 100%;
				}

				th, td {
				    padding: 10px 5px;
				}
			</style>
		</head>
		<body>
			<table style="margin:auto;">
				<tr>
					<th colspan="4" align="center">NOMBRES MAS FRECUENTES DE MUJERES EN ESPAÑA</th>
				</tr>
				<tr>
					<th>Nro</th>
					<th>Nombres</th>
					<th>Fecuencia</th>
					<th>Serie</th>
				</tr>
		';
	    // Creamos los registros de la tabla de forma dinamica segun los registros obtenidos
    foreach ($rows as $key => $row) {
    	$html.= '<tr>
    				<td>'.( (int)$key+1 ).'</td>
    				<td>'.$row['nombres'].'</td>
    				<td>'.$row['frecuencia'].'</td>
    				<td>'.$row['serie'].'</td>
				</tr>
    			';
    }
			
	$html.='</table>
		</body>
		</html>
	</body>';
	
    $dhg=utf8_encode(utf8_decode($html));
    
	$dompdf=new DOMPDF();
	
	// $dompdf->set_paper('A4', 'landscape'); //Hoja en Horizontar
	$dompdf->set_paper('A4', 'portrait'); //Hoja en Vertical

	$dompdf->load_html($html);

	ini_set("memory_limit","128M");
	$dompdf->render();
	header('Content-Type: application/pdf; charset=utf-8');
	$dompdf->stream("Reportedeventas.pdf",array('Attachment'=>0));

  	break;

}
?>