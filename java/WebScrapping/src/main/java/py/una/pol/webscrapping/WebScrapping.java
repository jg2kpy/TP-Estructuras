package py.una.pol.webscrapping;

import java.io.IOException;
import java.util.logging.Level;
import java.util.logging.Logger;

import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.select.Elements;

/**
 *
 * @author jg2kpy https://github.com/jg2kpy https://juniorgutierrez.com/
 */
public class WebScrapping {

    public static void main(String[] args) {

        Lenguaje[] tiobe_index = {new Lenguaje("Python", 0, 0), new Lenguaje("C", 0, 0)};

        int MAX = 0;
        int MIN = 0;

        for (Lenguaje lenguaje : tiobe_index) {
            int tries = 0;
            String match_topic = "";
            while ("".equals(match_topic) && tries < 3) {
                tries = tries + 1;
                match_topic = getCantidad(lenguaje.nombre);
            }
            System.out.println(match_topic);
        }
    }

    public static String getCantidad(String lenguaje) {
        String texto = "";
        try {
            Document doc = Jsoup.connect("https://github.com/topics/" + lenguaje).get();
            Elements repositories = doc.getElementsByClass("h3 color-fg-muted");
            texto = repositories.text();
        } catch (IOException ex) {
            Logger.getLogger(WebScrapping.class.getName()).log(Level.SEVERE, null, ex);
        }
        return texto;
    }
}
