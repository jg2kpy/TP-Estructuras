package py.una.pol.webscrapping;

// Importamos librerias, librerias de ArrayList
import java.util.ArrayList;
import java.util.Arrays;
import java.util.Collections;

//Liberias para registrar excepciones
import java.util.logging.Level;
import java.util.logging.Logger;

//Libreria de Jsoup (analizador de HTML)
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.select.Elements;

//Librerias de entrada y salida a sistema de ficheros
import java.io.FileWriter;
import java.io.File;
import java.io.IOException;

//Libreria para graficar los datos
import org.jfree.chart.JFreeChart;
import org.jfree.chart.ChartUtils;
import org.jfree.chart.ChartFactory;
import org.jfree.chart.plot.PlotOrientation;
import org.jfree.data.category.DefaultCategoryDataset;
import java.awt.Desktop;

/**
 *
 * @author jg2kpy https://github.com/jg2kpy https://juniorgutierrez.com/
 */
public class Ejercicio1 {

    public static void main(String[] args) {// Funcion principal

        //ArrayList que contiene la lista de los 20 lenguajes mas usados segun tiobe index
        ArrayList<Lenguaje> tiobe_index = new ArrayList<Lenguaje>(Arrays.asList(new Lenguaje("Python", 0, 0), new Lenguaje("C", 0, 0), new Lenguaje("Java", 0, 0), new Lenguaje("Cpp", 0, 0), new Lenguaje("Csharp", 0, 0), new Lenguaje("VisualBasic", 0, 0), new Lenguaje("JavaScript", 0, 0), new Lenguaje("Assembly language", 0, 0), new Lenguaje("SQL", 0, 0), new Lenguaje("PHP", 0, 0), new Lenguaje("R", 0, 0), new Lenguaje("Delphi", 0, 0), new Lenguaje("Go", 0, 0), new Lenguaje("Swift", 0, 0), new Lenguaje("Ruby", 0, 0), new Lenguaje("visual-basic-6", 0, 0), new Lenguaje("Objective-C", 0, 0), new Lenguaje("Perl", 0, 0), new Lenguaje("Lua", 0, 0), new Lenguaje("matlab", 0, 0)));

        int MAX = 0;
        int MIN = 0;

        try { // Empezamos el Web Scrapping
            FileWriter salida = new FileWriter("Resultados.txt");

            System.out.println("Realizando WebScrapping a github.com y escribiendo resultados en Resultados.txt");
            System.out.println("Esta operacion puede tomar aproximadamente 1 minuto...");

            for (int i = 0; i < tiobe_index.size(); i++) {// Iteramos la lista para obtener la cantidad de repositorios de github por cada topico
                Lenguaje lenguaje = tiobe_index.get(i);
                System.out.print("Scrapping..." + lenguaje.nombre);
                int tries = 0;
                String match_topic = getCantidad(lenguaje.nombre); // Solicitud HTTP a github.com/topics
                while ("".equals(match_topic) && tries < 3) { // Si no obtenemos la respuesta probamos otra 3 veces, si aun no tenemos respuesta se saca ese lenguaje de la lista
                    Thread.sleep(3000); // Se espera 3 segundos como cooldown entre cada intento
                    tries = tries + 1;
                    match_topic = getCantidad(lenguaje.nombre);
                }

                if ("".equals(match_topic)) {
                    System.out.println("\nError al obtener el lenguaje " + lenguaje.nombre);
                    tiobe_index.remove(i);
                    i--;
                } else {

                    lenguaje.cantidad = procesarCadena(match_topic);
                    salida.write(lenguaje.toFile() + "\n"); // Guardamos los resultado en el archivo Resultados.txt
                    System.out.println(": " + lenguaje.cantidad);

                    if (MAX < lenguaje.cantidad) {
                        MAX = lenguaje.cantidad;
                    }

                    if (MIN > lenguaje.cantidad || MIN == 0) {
                        MIN = lenguaje.cantidad;
                    }
                }
            }

            salida.close();

            float dif = MAX - MIN;
            for (Lenguaje lenguaje : tiobe_index) {// Aplicamos la formula de GitHub Rating
                lenguaje.rating = ((float) (lenguaje.cantidad - MIN) / dif) * 100F;
            }

            Collections.sort(tiobe_index, (Lenguaje l1, Lenguaje l2) -> { // Ordenamos la lista
                return l1.cantidad - l2.cantidad;
            });

            System.out.println();

            for (Lenguaje lenguaje : tiobe_index) {
                System.out.println(lenguaje.toString());
            }

            DefaultCategoryDataset dataset = new DefaultCategoryDataset();

            int n = tiobe_index.size() - 1;
            for (int i = 0; i < 10; i++) {// Graficamos mediante JFreeChart
                dataset.addValue(tiobe_index.get(n - i).cantidad, tiobe_index.get(n - i).nombre, "");
            }

            JFreeChart barChart = ChartFactory.createBarChart("GitHub", "Lenguajes", "Apariciones", dataset, PlotOrientation.VERTICAL, true, true, false);

            ChartUtils.saveChartAsPNG(new File("grafica.png"), barChart, 650, 400);
            File file = new File("grafica.png");
            Desktop.getDesktop().open(file);

        } catch (IOException ex) {
            Logger.getLogger(Ejercicio1.class.getName()).log(Level.SEVERE, null, ex);
        } catch (InterruptedException ex) {
            Logger.getLogger(Ejercicio1.class.getName()).log(Level.SEVERE, null, ex);
        }
    }

    public static String getCantidad(String lenguaje) {
        String texto = "";
        try {
            Document doc = Jsoup.connect("https://github.com/topics/" + lenguaje).get(); // Solicitud HTTP a github.com/topics
            Elements repositories = doc.getElementsByClass("h3 color-fg-muted"); // Usamos el atributo HTML class para obtener la informacion que queremos
            texto = repositories.text();
        } catch (IOException ex) {
            //Logger.getLogger(WebScrapping.class.getName()).log(Level.SEVERE, null, ex);
        }
        return texto;
    }

    //Funcion para extraer la cantidad de repositorio por cada topico
    public static int procesarCadena(String cadena) {
        return Integer.parseInt(cadena.replace(",", "").substring(9, cadena.indexOf(" ", 9) - 1));
    }
}
