## Requerimientos para Java

Como tenemos los codigos fuentes .java, debemos compilar a Java ByteCode y ejecutar a través de JVM (Java Virtual Machine), para realizar estas dos tareas debemos instalar JDK (Java Developer Kit) version 8 o superior.
También para descargar las dependencias necesitamos Apache Maven o usar un IDE de Java que soporte proyectos Maven.

### Instalar Java, Apache Maven o un IDE

#### Debian Based-OS (Debian, Ubuntu, etc.)

Para instalar JDK en última versión:

```
 # apt install default-jdk
```

O instalar JDK para Java 8:

```
 # apt install openjdk-8-jdk
```

Para instalar Maven:

```
 # apt install maven
```
O instalar un IDE como Eclipse, IntelliJ o NetBeans:
```
 # apt install netbeans
```

#### WindowsNT Based-OS (Windows 10, Windows 11, etc.)

Ir a la página oficial de Oracle y descargar e instalar mediante el setup: https://www.oracle.com/java/technologies/downloads/

Ir a la página oficial de Apache y descargar e instalar mediante el setup: https://maven.apache.org/install.html

Ir a la página oficial de NetBeans y descargar e instalar mediante el setup: https://netbeans.apache.org/download/index.html


### Ejecutar

Para ejecutar, podemos abrir el proyecto con un IDE y dar al boton ejecutar.
Si no tenemos el IDE instalado y solo Maven entonces debemos abrir una terminal en el directorio donde se encuentra el proyecto junto al fichero POM.xml.
Para descargar dependencias, compilar y ejecutar debemos usar el siguiente comando

Ejercicio 1:

```
 $ mvn clean install compile exec:java -Dexec.mainClass="py.una.pol.webscrapping.Ejercicio1"
```

Ejercicio 2:

```
 $ mvn clean install compile exec:java -Dexec.mainClass="py.una.pol.webscrapping.Ejercicio2"
```

### Configuracion

En el fichero conf.json podemos seleccionar ciertos atributos para la ejecucion del programa, es un archivo en formato JSON que originalmente viene asi

```
{
    "path": ".",
    "intentos": 3,
    "timeout": 2000
    "interes": "java",
    "paginas": 10
}
```
En el atributo path, ponemos la direccion donde se guardara el archivo de salida,
el atributo de intentos es la cantidad de intentos cada vez que falla en obtener,
el atributo timeout es el tiempo entre cada intento,
el atributo interes es el topico de interes para el ejercicio 2,
el atributo paginas es la cantidad maxima de paginas que se va a recorrer.
