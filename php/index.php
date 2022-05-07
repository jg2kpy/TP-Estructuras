<?php
ob_start();
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
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
foreach ($listaLenguajes as $posicion => $datos) {
	#web scraping
	$url = "https://github.com/topics/".$datos["lenguaje"];
	curl_setopt($ch, CURLOPT_URL, $url);
	$html = curl_exec($ch);
	$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	#parsing
	if ($httpcode == 200) {
		echo $datos["lenguaje"].": ";
		$re = '/<h2 class="h3 color-fg-muted">\n.*\n.*\n.*\n.*<\/h2>/m';
		preg_match_all($re, $html, $matches, PREG_SET_ORDER, 0);
		$re = '/[^0-9]+/';
		$string = strip_tags($matches[0][0]);
		$string = preg_replace($re, "", $string);
		$listaLenguajes[$posicion]["apariciones"] = $string;
		output($string);
		echo "<br>";
	}
	else {
		print("ERROR EN ".$datos["lenguaje"]." NUMERO ".$httpcode."<br>");
	}
	usleep(100000); // esperar 100ms entre cada peticion para evitar bloqueos
}
curl_close($ch);

#archivo
$archivo = fopen("resultados.txt", "w");
foreach ($listaLenguajes as $lengActual) {
	fwrite($archivo, $lengActual["lenguaje"].", ".$lengActual["apariciones"]."\n");
}
fclose($archivo);

function output($str) {
	echo $str;
	ob_end_flush();
	ob_flush();
	flush();
	ob_start();
}
?>