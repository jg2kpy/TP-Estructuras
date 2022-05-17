package py.una.pol.webscrapping;

//Importamos librerias, librerias para operar con fechas y horas
import java.time.Period;
import java.time.ZoneOffset;
import java.time.LocalDateTime;
import java.time.format.DateTimeFormatter;

//Librerias de tipos de datos de listas, mapas, hashmap
import java.util.Map;
import java.util.Set;
import java.util.List;
import java.util.HashMap;
import java.util.HashSet;
import java.util.ArrayList;
import java.util.LinkedList;
import java.util.Collections;

//Liberias para registrar excepciones
import java.util.logging.Level;
import java.util.logging.Logger;

//Libreria de Jsoup (analizador de HTML)
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

//Librerias de entrada y salida a sistema de ficheros
import java.io.File;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;

//Libreria para graficar los datos
import org.jfree.chart.JFreeChart;
import org.jfree.chart.ChartUtils;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.data.category.DefaultCategoryDataset;
import java.awt.Desktop;

//Libreria para abrir archivo de configuracion JSON
import org.json.simple.JSONObject;
import java.io.FileNotFoundException;
import org.json.simple.parser.JSONParser;
import org.json.simple.parser.ParseException;

/**
 *
 * @author jg2kpy https://github.com/jg2kpy https://juniorgutierrez.com/
 */
public class Ejercicio2 {

    static String interes = "java"; // Topico de interes que buscaremos
    static String dateTime30DaysAgo = getDateTime30DaysAgo(); // Fecha y hora hace 30 dias

    public static void main(String[] args) {

        JSONParser parser = new JSONParser();
        int paginas = 11;
        String path = ".";
        try {
            Object obj = parser.parse(new FileReader("conf.json"));
            JSONObject jsonObject = (JSONObject) obj;
            interes = (String)jsonObject.get("interes");
            path = (String)jsonObject.get("path");
            paginas = Math.toIntExact((Long)jsonObject.get("paginas"));
            paginas++;
        } catch (FileNotFoundException ex) {
            Logger.getLogger(Ejercicio1.class.getName()).log(Level.SEVERE, null, ex);
        } catch (IOException ex) {
            Logger.getLogger(Ejercicio1.class.getName()).log(Level.SEVERE, null, ex);
        } catch (ParseException ex) {
            Logger.getLogger(Ejercicio1.class.getName()).log(Level.SEVERE, null, ex);
        }

        try {
            // Empezamos el Web Scrapping
            System.out.println("Realizamos WebScrapping a github.com/topics/" + interes);
            System.out.println("Esta operacion puede tomar aproximadamente 1 minuto...\n");

            System.out.print("Pagina 1");
            ArrayList listaTotal = click_load_more(1); // Verificamos la primera pagina
            System.out.println("...completado");
            System.out.println("Topicos en pagina 1: " + listaTotal.size());

            ArrayList<String> listaParcial;
            for (int i = 2; i < paginas; i++) {
                System.out.print("Pagina " + i);
                listaParcial = click_load_more(i);
                // hacemos click en load more para obtener mas repositorios
                listaTotal.addAll(listaParcial); // recopilamos el resultado de todaslas paginas 
                System.out.println("...completado");
                System.out.printf("Topicos en pagina %d: %d, Topicos en total: %d", i, listaParcial.size(), listaTotal.size());
                System.out.println();
            }

            FileWriter salida = new FileWriter(path + "/Resultados2.txt");
            for(Object topic : listaTotal){
                salida.write(topic + "\n");
            }
            salida.close();

            //contamos las aparciones de cada topico en un hashmap
            Map<String, Integer> mapa = new HashMap<>();
            Set<String> set = new HashSet<>(listaTotal);
            set.forEach(r -> {
                mapa.put(r, Collections.frequency(listaTotal, r));
            });

            // ordenamos el hashmap por el numero de apariciones
            List<Map.Entry<String, Integer>> list = new LinkedList<>(mapa.entrySet());
            Collections.sort(list, (Map.Entry<String, Integer> o1,
                    Map.Entry<String, Integer> o2)
                    -> -(o1.getValue()).compareTo(o2.getValue()));

            // Graficamos mediante JFreeChart
            DefaultCategoryDataset dataset = new DefaultCategoryDataset();
            System.out.println("\nTOP 20 de topicos mencionados en https://github.com/topics/" + interes);
            for (int i = 0; i < 20; i++) {
                System.out.println(list.get(i));
                dataset.addValue(list.get(i).getValue(), list.get(i).getKey(), "");
            }

            JFreeChart barChart = ChartFactory.createBarChart("github.com/topics/" + interes, "TOPIC", "NRO_APARICIONES", dataset, PlotOrientation.VERTICAL, true, true, false);

            ChartUtils.saveChartAsPNG(new File("grafica.png"), barChart, 1280, 720);
            File file = new File("grafica.png");
            Desktop.getDesktop().open(file);

        } catch (IOException ex) {
            Logger.getLogger(Ejercicio2.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static ArrayList click_load_more(int pagina) {
        ArrayList retorno = new ArrayList();
        try {
            String url = "https://github.com/topics/" + interes + "?o=desc&s=updated&page=" + pagina;
            Document doc = Jsoup.connect(url).get(); // Solicitud HTTP a github.com/topics/{interes}
            // Usamos el elmento HTML para obtener la informacion que queremos
            Elements articles = doc.select("article.border.rounded.color-shadow-small.color-bg-subtle.my-4");
            for (Element article : articles) {
                if (article.select("relative-time") != null) {
                    Elements date_time = article.select("relative-time");
                    if (date_time != null) {
                        if (date_time.attr("datetime").compareTo(dateTime30DaysAgo) > 0) { // Verificamos que esta en el rango de la fecha
                            Elements listaTopicos = article.select("a.topic-tag.topic-tag-link.f6.mb-2");
                            for (Element topico : listaTopicos) {
                                retorno.add(topico.text().trim()); // Añadimos a la lista que retornaremos
                            }
                        }
                    }
                }
            }
        } catch (IOException ex) {
            Logger.getLogger(Ejercicio2.class.getName()).log(Level.SEVERE, null, ex);
        }
        return retorno;
    }

    // Funcion para obtener la fecha y hora de hace 30 dias atras
    public static String getDateTime30DaysAgo() {
        LocalDateTime date = LocalDateTime.now().minus(Period.ofDays(30));
        DateTimeFormatter dtf = DateTimeFormatter.ofPattern("uuuu-MM-dd'T'HH:mm:ssX");
        return date.atOffset(ZoneOffset.UTC).format(dtf);
    }
}
