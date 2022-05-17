<?php
set_time_limit(0); // para poder utilizar sleep
#variables
$listaLenguajes = [
	["lenguaje"=>"python", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"ruby", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"c", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"cpp", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"csharp", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"objective-c", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"visualbasic", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"visual-b", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"java", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"javascript", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"assembly-language", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"sql", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"php", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"r", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"delphi", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"go", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"swift", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"perl", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"lua", "apariciones"=>0, "rating"=>0 ],
	["lenguaje"=>"matlab", "apariciones"=>0, "rating"=>0 ]
];
$ch = curl_init();
$min = PHP_INT_MAX;
$max = PHP_INT_MIN;
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
#webscraping
foreach ($listaLenguajes as $posicion => $lengActual) {
	$intentos = 0;
	$url = "https://github.com/topics/".$lengActual["lenguaje"];
	curl_setopt($ch, CURLOPT_URL, $url);
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
		$re = '/<h2 class="h3 color-fg-muted">\n.*\n.*\n.*\n.*<\/h2>/m';
		preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);
		$re = '/[^0-9]+/';
		$string = strip_tags($matches[0][0]);
		$apariciones = preg_replace($re, "", $string);
		$listaLenguajes[$posicion]["apariciones"] = $apariciones;
		$min = min($min, $apariciones);
		$max = max($max, $apariciones);
	}
	#error
	else {
		print($lengActual["lenguaje"]." ignorado<br>");
		unset($listaLenguajes[$posicion]); // quitar de la lista
	}
}
curl_close($ch);
escribirArchivo();
obtenerRatingOrdenado($min, $max);
#imprimir en pantalla
foreach ($listaLenguajes as $lengActual) {
	print($lengActual["lenguaje"].", ".round($lengActual["rating"], 3).", ".$lengActual["apariciones"]."<br>");
}
print("<br>");
obtenerListaGrafico();

#funciones
function escribirArchivo () {
	global $listaLenguajes;
	$archivo = fopen("resultados.txt", "w");
	#escribir en el archivo
	foreach ($listaLenguajes as $posicion => $lengActual) {
		fwrite($archivo, $lengActual["lenguaje"].", ".$lengActual["apariciones"]."\n");
	}
	fclose($archivo);
}

#se obtiene el rating de cada lenguaje y se ordena descendentemente
function obtenerRatingOrdenado($min, $max) {
	global $listaLenguajes;
	#calcular rating
	foreach ($listaLenguajes as $posicion => $lengActual) {
		$listaLenguajes[$posicion]["rating"] = ($lengActual["apariciones"] - $min)/($max - $min)*100;
	}
	#ordenar descendentemente por rating
	uasort($listaLenguajes, function($a, $b) {
		return $b["rating"] > $a["rating"];
	});
}

#corta la lista al top 10 y transforma al formato del grafico
function obtenerListaGrafico() {
	global $listaLenguajes;
	#preparar datos para el grafico
	$listaLenguajes = array_slice($listaLenguajes, 0, 10); // se agarran los 10 primeros
	$listaLenguajes = array_map(function($listaLenguajes) { // se modifican las claves del array para graficar
		return array(
		'label' => $listaLenguajes["lenguaje"],
		'y' => $listaLenguajes["apariciones"]
		);
	}, $listaLenguajes);
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
			title: "Lenguajes"
		},
		data: [{
			type: "column",
			dataPoints: <?php echo json_encode($listaLenguajes, JSON_NUMERIC_CHECK); ?>
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