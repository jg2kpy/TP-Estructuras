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
$final = false;
#parsing
if ($httpCode == 200) {
	$topics = [];
	$tiempo = [];
	$re = '/<article.*?\/article>/ms';
	preg_match_all($re, $html, $matches);
	foreach ($matches as $key1 => $value1) {
		foreach ($value1 as $key2 => $value2) {
			if ($final) break;
			#extraer topics
			$re = '/href="\/topics\/.*?"/ms';
			preg_match_all($re, $value2, $topics);
			$fechaActualizacion = obtenerFechaActualizacion($value2);
			print_r($fechaActualizacion);
			print("<br>");
			if (strcmp($fechaActualizacion[0], $hoy[0]) == 0)
			{
				print("MISMO MES<br>");
				contarTopics($topics, $mapaTopics);
			}
			elseif (strcmp($fechaActualizacion[0], $hace30Dias[0]) == 0) {
				print("MES PASADO");
				$dia1 = (int)$fechaActualizacion[1];
				$dia2 = (int)$hace30Dias[1];
				if ($dia1 >= $dia2) {
					print(" CUMPLE<br>");
					contarTopics($topics, $mapaTopics);
				}
				else {
					print(" NO CUMPLE<br>");
					$final = true;
				}
			}
			else {
				print("NO CUMPLE<br>");
				$final = true;
			}
			print("<br><br>");
		}
	}
	arsort($mapaTopics);
	print_r($mapaTopics);
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

#se pasa el mapa por referencia y se actualiza para cada repositorio
function contarTopics($topics, &$map) {
	foreach ($topics[0] as $topic) {
		$topic = substr($topic, 14, -1);
		if (array_key_exists($topic, $map)) $map[$topic]++;
		else $map[$topic] = 1;
	}
	return;
}
?>