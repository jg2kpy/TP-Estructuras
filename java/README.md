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
