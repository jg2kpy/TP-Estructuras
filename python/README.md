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
