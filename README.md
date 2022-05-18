# TP-Estructuras
## Trabajo practicó de estructuras de los lenguajes de programación de la FP-UNA.

### Colaboradores
* Junior Gutierrez [@jg2kpy](https://github.com/jg2kpy)
* Carlos Urdapilleta [@Curda27](https://github.com/Curda27)
* Guillermo Rodas [@grodasgomez](https://github.com/grodasgomez)


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


## Requerimientos para Javascript (Interprete: NodeJS)

### Entorno de ejecución

- Node.js (16.x)
- npm (8.x)

### Instalación

- Estando en la carpeta del proyecto, instalar las dependencias:

`npm install`

### Ejecución

- Ejecutar el ejercicio 1:
  
` npm run start1`

- Ejecutar el ejercicio 2:
  
` npm run start2`

Luego de la ejecución de cada ejercicio, se abrirá una ventana en el navegador con el resultado.

Los resultados se escriben en un archivo `results.txt` que se encuentra en la carpeta principal de cada ejercicio.



## Requerimientos para PHP

Para ejecutar los archivos .php se requiere tener instalado xampp https://www.apachefriends.org/es/download.html

En la instalacion se debe seleccionar el server Apache y el lenguaje PHP.

Una vez instalado, los archivos .php deben colocarse en la carpeta htdocs dentro de la carpeta donde se instalo xampp.

path por defecto: C:\xampp\htdocs

Para ejecutar los codigos se debe acceder en el navegador a los siguientes urls:

-localhost/ejercicio1.php

-localhost/ejercicio2.php



## Requerimientos para Python

Para ejecutar estos archivos .py es necesario tener instalado el intérprete de Python 3.6 o superior y pip

### Instalar Python

#### Debian Based-OS (Debian, Ubuntu, etc.)

```
 # apt install python3 pip
```

#### WindowsNT Based-OS (Windows 10, Windows 11, etc.)

Ir a la pagina oficial de Python y descargar e instalar mediante el setup: https://www.python.org/downloads/windows/

### Instalar dependencias

Necesitaremos BeautifulSoup4 (librería para analizar HTML) y matplotlib (librería para graficar)
Para instalar esto usaremos pip con el siguiente comando

```
 $ pip install -r requirements.txt
```
### Ejecutar
Para ejecutar, una terminal debe tener como directorio de trabajo el directorio donde se encuentra el ejercicio1.py o ejercicio2.py y ejecutamos con el siguiente comando

```
 $ python3 ejercicio1.py
```
Para ejercicio2.py: 
```
 $ python3 ejercicio2.py
```

### Configuracion

En el fichero conf.json podemos seleccionar ciertos atributos para la ejecucion del programa, es un archivo en formato JSON que originalmente viene asi

```
{
    "path": ".",
    "intentos": 3,
    "timeout": 2
    "interes": "python",
    "paginas": 10
}
```
En el atributo path, ponemos la direccion donde se guardara el archivo de salida,
el atributo de intentos es la cantidad de intentos cada vez que falla en obtener,
el atributo timeout es el tiempo entre cada intento,
el atributo interes es el topico de interes para el ejercicio 2,
el atributo paginas es la cantidad maxima de paginas que se va a recorrer.
