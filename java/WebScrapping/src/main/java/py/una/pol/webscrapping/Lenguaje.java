package py.una.pol.webscrapping;

/**
 *
 * @author jg2kpy https://github.com/jg2kpy https://juniorgutierrez.com/
 */
public class Lenguaje { // Clase para instancicar por cada lenguaje

    //Atributos de la clase
    String nombre;
    int cantidad;
    float rating;

    public Lenguaje(String nombre, int cantidad, float rating) {//Constructor de la clase
        this.nombre = nombre;
        this.cantidad = cantidad;
        this.rating = rating;
    }

    public String toFile() {
        return this.nombre + "," + this.cantidad;
    }

    @Override
    public String toString() {
        return this.nombre + "," + this.cantidad + "," + this.rating;
    }
}
