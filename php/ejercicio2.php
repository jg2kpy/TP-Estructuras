<?php
$fecha = strtotime("now");
$hoy = explode(" ", date('M d', $fecha));
$fecha = strtotime("-30 days");
$hace30Dias = explode(" ", date('M d', $fecha));
print_r($hoy);
print("<br>");
print_r($hace30Dias);
print("<br><br>");
$mapaTopics = [];
$fechaActualizacion = [];
$url="https://github.com/topics/visualbasic?o=desc&s=updated";
#variables
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#webscraping
curl_setopt($ch, CURLOPT_URL, $url);
$html = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
#parsing
if ($httpCode == 200) {
	$topics = [];
	$tiempo = [];
	$re = '/<article.*?\/article>/ms';
	preg_match_all($re, $html, $matches);
	foreach ($matches as $key1 => $value1) {
		foreach ($value1 as $key2 => $value2) {
			#extraer topics
			$re = '/href="\/topics\/.*?"/ms';
			preg_match_all($re, $value2, $topics);
			$fechaActualizacion = obtenerFechaActualizacion($value2);
			print_r($fechaActualizacion);
			print("<br>");
			if (strcmp($fechaActualizacion[0], $hoy[0]) == 0)
			{
				print("MISMO MES<br>");
			}
			elseif (strcmp($fechaActualizacion[0], $hace30Dias[0]) == 0) {
				print("MES PASADO");
				$dia1 = (int)$fechaActualizacion[1];
				$dia2 = (int)$hace30Dias[1];
				if ($dia1 >= $dia2) {
					print(" CUMPLE");
				}
				else {
					print(" NO CUMPLE");
				}
				print("<br>");
			}
			else print("NO CUMPLE<br>");
			// print_r($topics);
			// print("<br>");
			print("<br><br>");
		}
	}
}
else {
	print("ERROR EN ".$lengActual["lenguaje"]." NUMERO ".$httpCode."<br>");
}
curl_close($ch);

function obtenerFechaActualizacion($str) {
	$re = '/<relative-time.*?relative-time>/ms';
	preg_match_all($re, $str, $tiempo, PREG_SET_ORDER, 0);
	$re = '/,+/';
	$str = strip_tags($tiempo[0][0]);
	$str = preg_replace($re, "", $str);
	return explode(" ", $str);
}
?>