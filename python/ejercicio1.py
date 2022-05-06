# Made by Jose Gutierrez (@jg2kpy) https://github.com/jg2kpy https://juniorgutierrez.com/

# Importo librerias
import re  # Libreria de regex
import requests  # Libreria para realizar peticiones HTTP
from bs4 import BeautifulSoup  # BeautifulSoup4 Libreria para analizar HTML
import matplotlib.pyplot as plt  # Libreria para graficar
import time # Libreria para generar un cooldown

# Funcion principal
def main():
    # Lista de los 20 lenguajes mas usados segun tiobe index
    tiobe_index = [['Python', 0, 0], ['C', 0, 0], ['Java', 0, 0], ['Cpp', 0, 0], ['Csharp', 0, 0], ['VisualBasic', 0, 0], ['JavaScript', 0, 0], ['Assembly language', 0, 0], ['SQL', 0, 0], ['PHP', 0, 0], ['R', 0, 0], ['Delphi', 0, 0], ['Go', 0, 0], ['Swift', 0, 0], ['Ruby', 0, 0], ['visual-basic-6', 0, 0], ['Objective-C', 0, 0], ['Perl', 0, 0], ['Lua', 0, 0], ['matlab', 0, 0]]

    MAX = 0
    MIN = 0
    try:  # Empezamos el Web Scrapping
        print('Realizando WebScrapping a github.com y escribiendo resultados en Resultados.txt')
        print('Esta operacion puede tomar aproximadamente 1 minuto...')
        for language in tiobe_index:  # Iteramos la lista para obtener la cantidad de repositorios de github por cada topico
            tries = 0
            print(f'Scrapping...{language[0]}',end='')
            url = 'https://www.github.com/topics/' + language[0]
            response = requests.get(url)  # Solicitud HTTP a github.com/topics
            responseHTML = BeautifulSoup(response.text, features='html.parser')
            # Usamos el atributo HTML class para obtener la informacion que queremos
            match_topic = responseHTML.find(class_='h3 color-fg-muted')
            # Si no obtenemos la respuesta probamos otra 3 veces, si aun no tenemos respuesta se saca ese lenguaje de la lista
            while match_topic == None and tries < 3:
                tries = tries + 1
                time.sleep(2) # Se espera 2 segundos como cooldown entre cada intento
                response = requests.get(url)
                responseHTML = BeautifulSoup(response.text, features='html.parser')
                match_topic = responseHTML.find(class_='h3 color-fg-muted')

            if match_topic == None:
                print(f'\nError al obtener el lenguaje {language[0]}')
                tiobe_index.pop(tiobe_index.index(language))
            else:
                match_topic = match_topic.text.replace(',', '')
                # Usamos regex para obtener las apariciones
                language[1] = int(re.findall('[0-9]+', match_topic)[0])

                print(f': {language[1]}')
                # Guardamos los resultado en el archivo Resultados.txt
                with open('Resultados.txt', "a") as f:
                    f.write(f'{language[0]},{language[1]}\n')

                # Actualizamos el maximo y el minimo en cada iteracion
                if MAX < language[1]:
                    MAX = language[1]

                if MIN > language[1] or MIN == 0:
                    MIN = language[1]

    except requests.exceptions.RequestException:
        print('Error al cargar la pagina de github.com')
        return -1

    dif = MAX - MIN
    for language in tiobe_index:  # Aplicamos la formula de GitHub Rating
        language[2] = ((language[1] - MIN)/dif) * 100

    tiobe_index.sort(key=mySort)  # Ordenamos la lista

    for language in tiobe_index:  # Mostramos el resultado en Pantalla
        print(f"{language[0]}, {language[1]}, {language[2]}")

    # Graficamos mediante matplotlib
    left = []
    height = []
    tick_label = []

    n = len(tiobe_index)
    for i in range(1, 11):
        left.append(i)
        height.append(tiobe_index[n-i][1])
        tick_label.append(tiobe_index[n-i][0])

    plt.bar(left, height, tick_label=tick_label)

    plt.xlabel('NOMBRE_LENGUAJE')
    plt.ylabel('NRO_APARICIONES')
    plt.title('GitHub')

    plt.show()


def mySort(e):  # Funcion para que sort sepa por cual parametro ordenar
    return e[2]

# Con ese if verificamos que esta archivo esta siendo ejecutado y no importado como una libreria
if __name__ == '__main__':
    main()
