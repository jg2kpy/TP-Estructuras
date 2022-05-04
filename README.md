# TP-Estructuras
## Trabajo practicó la materia estructuras de los lenguajes de programación de la FP-UNA.

### Colaboradores
* Junior Gutierrez [@jg2kpy](https://github.com/jg2kpy)
* Carlos Urdapilleta [@Curda27](https://github.com/Curda27)
* Guillermo Rodas [@grodasgomez](https://github.com/grodasgomez)


### Requerimientos para Java

Como tenemos los codigos fuentes .java, debemos compilar a Java ByteCode y ejecutar a través de JVM (Java Virtual Machine), para realizar estas dos tareas debemos instalar JDK (Java Developer Kit) version 8 o superior.
También para descargar las dependencias necesitamos Apache Maven.

#### Instalar Java y Apache Maven

##### Debian Based-OS (Debian, Ubuntu, etc.)

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

##### WindowsNT Based-OS (Windows 10, Windows 11, etc.)

Ir a la página oficial de Oracle y descargar e instalar mediante el setup: https://www.oracle.com/java/technologies/downloads/

Ir a la página oficial de Apache y descargar e instalar mediante el setup:
https://maven.apache.org/install.html

#### Ejecutar

Para ejecutar, una terminal debe tener como directorio de trabajo el directorio donde se encuentra el proyecto junto al fichero POM.xml.
Para descargar dependencias, compilar y ejecutar debemos usar el siguiente comando

Ejercicio 1:

```
 $ mvn clean install compile exec:java -Dexec.mainClass="py.una.pol.webscrapping.Ejercicio1"
```

Ejercicio 2:

```
 $ mvn clean install compile exec:java -Dexec.mainClass="py.una.pol.webscrapping.Ejercicio2"
```

### Requerimientos para Python

Para ejecutar estos archivos .py es necesario tener instalado el intérprete de Python 3.6 o superior y pip

#### Instalar Python

##### Debian Based-OS (Debian, Ubuntu, etc.)

```
 # apt install python3 pip
```

##### WindowsNT Based-OS (Windows 10, Windows 11, etc.)

Ir a la pagina oficial de Python y descargar e instalar mediante el setup: https://www.python.org/downloads/windows/

#### Instalar dependencias

Necesitaremos BeautifulSoup4 (librería para analizar HTML) y matplotlib (librería para graficar)
Para instalar esto usaremos pip con el siguiente comando

```
 $ pip install -r requirements.txt
```
#### Ejecutar
Para ejecutar, una terminal debe tener como directorio de trabajo el directorio donde se encuentra el ejercicio1.py o ejercicio2.py y ejecutamos con el siguiente comando

```
 $ python3 ejercicio1.py
```
Para ejercicio2.py: 
```
 $ python3 ejercicio2.py
```

