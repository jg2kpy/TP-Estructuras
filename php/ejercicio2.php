<?php
set_time_limit(0); // para poder utilizar sleep
#variables
$fecha = strtotime("now");
$hoy = explode(" ", date('M d', $fecha));
$fecha = strtotime("-30 days");
$hace30Dias = explode(" ", date('M d', $fecha));
$mapaTopics = [];
$final = false;
$ignorados = 0;
$topic = "python";
print("Topic de interes: ".$topic."<br>");
#scrapear hasta 10 paginas, 10 ignorados o hasta que ocurra errores
for ($i = 1; $i <= 10 and $final == false; $i++) {
	scrapear("https://github.com/topics/".$topic."?o=desc&s=updated&page=".$i);
}
print("i:".$i."<br>");
print("ignorado:".$ignorados."<br>");
#ordenar mapa
arsort($mapaTopics);
escribirArchivo();
$listaTopics = obtenerListaGrafico();
#imprimir en pantalla
foreach ($listaTopics as $topicActual) {
	print($topicActual["label"].", ".$topicActual["y"]."<br>");
}
print("<br>");

#funciones
/*
	se realiza webscraping del url recibido y se
	guarda en un mapa la cantidad de repeticiones
	de los topics de cada repositorio actualizado
	hasta hace 30 dias
*/
function scrapear($url) {
	#variables
	$ch = curl_init();
	global $hoy, $hace30Dias, $final, $ignorados;
	#webscraping
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_URL, $url);
	$intentos = 0;
	// intentar 3 veces en caso de error
	while ($intentos < 3) {
		$html = curl_exec($ch);
		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$intentos++;
		if ($httpCode == 200) {
			$intentos = 0;
			break;
		}
		usleep(1000000); // esperar 1s si ocurre error
	}
	#parsing
	if ($intentos == 0) {
		$topics = [];
		$tiempo = [];
		$re = '/<article.*?\/article>/ms';
		preg_match_all($re, $html, $matches);
		foreach ($matches as $key1 => $value1) {
			foreach ($value1 as $key2 => $value2) {
				#al haber ignorado 10 repositorios se finaliza
				if ($ignorados == 10) {
					$final = true;
					curl_close($ch);
					return;
				}
				#extraer topics
				$re = '/href="\/topics\/.*?"/ms';
				preg_match_all($re, $value2, $topics);
				$fechaActualizacion = obtenerFechaActualizacion($value2);
				if ($fechaActualizacion) {
					if (strcmp($fechaActualizacion[0], $hoy[0]) == 0) {
						contarTopics($topics);
					}
					elseif (strcmp($fechaActualizacion[0], $hace30Dias[0]) == 0) {
						$dia1 = (int)$fechaActualizacion[1];
						$dia2 = (int)$hace30Dias[1];
						if ($dia1 >= $dia2) {
							contarTopics($topics);
						}
						else {
							$ignorados++;
						}
					}
					else {
						$ignorados++;
					}
				}
			}
		}
	}
	#finalizar debido a errores
	else {
		$final = true;
	}
	curl_close($ch);
}

function escribirArchivo () {
	global $mapaTopics;
	$archivo = fopen("resultados_ejer2.txt", "w");
	#escribir en el archivo
	foreach ($mapaTopics as $posicion => $topicActual) {
		fwrite($archivo, $posicion.", ".$topicActual."\n");
	}
	fclose($archivo);
}

/*
	se obtiene la fecha de actualizacion del repositorio
	actual, si no tiene fecha retorna null
*/
function obtenerFechaActualizacion($str) {
	$re = '/<relative-time.*?relative-time>/ms';
	if (preg_match_all($re, $str, $tiempo, PREG_SET_ORDER, 0)) {
		$re = '/,+/';
		$str = strip_tags($tiempo[0][0]);
		$str = preg_replace($re, "", $str);
		return explode(" ", $str);
	}
	return null;
}

/*
	se actualiza el contador del topic en el mapa de
	acuerdo a los topics del repositorio actual
*/
function contarTopics($topics) {
	global $mapaTopics;
	foreach ($topics[0] as $topic) {
		$topic = substr($topic, 14, -1);
		if (array_key_exists($topic, $mapaTopics))
			$mapaTopics[$topic]++;
		else
			$mapaTopics[$topic] = 1;
	}
	return;
}

/*
	transforma el mapa de los topics a un array 2d
	para realizar el grafico
*/
function obtenerListaGrafico() {
	global $mapaTopics;
	$listaTopics = [];
	$contador = 0;
	foreach ($mapaTopics as $key => $value) {
		array_push($listaTopics, array("label" => $key, "y" => $value));
		$contador++;
		if ($contador == 20)
			break;
	}
	return $listaTopics;
}
?>

<!-- grafico con canvasjs -->
<!DOCTYPE HTML>
<html>
<head>
<script>
window.onload = function() {

	var chart = new CanvasJS.Chart("chartContainer",
	{
		animationEnabled: true,
		axisY: {
			title: "Apariciones"
		},
		axisX: {
			title: "Topics"
		},
		data: [{
			type: "column",
			dataPoints: <?php echo json_encode($listaTopics, JSON_NUMERIC_CHECK); ?>
		}]
	});
	chart.render();
}
</script>
</head>
<body>
<div id="chartContainer" style="height: 370px; width: 100%;"></div>
<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>
</body>
</html>