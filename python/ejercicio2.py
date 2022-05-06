# Made by Jose Gutierrez (@jg2kpy) https://github.com/jg2kpy https://juniorgutierrez.com/

# Importo librerias
import re  # Libreria de regex
import requests  # Libreria para realizar peticiones HTTP
from bs4 import BeautifulSoup  # BeautifulSoup4 Libreria para analizar HTML
import matplotlib.pyplot as plt  # Libreria para graficar
import time  # Libreria para generar un cooldown
from collections import Counter  # Libreria para contar en una lista en python
# Libreria para obtener la fecha y hora actual
from datetime import datetime, timedelta

interes = 'c'  # Topico de interes que buscaremos
date_time_30days_ago = str((datetime.today() - timedelta(days=30)).replace(microsecond=0).isoformat()) + 'Z'  # Fecha y hora hace 30 dias

# Funcion principal
def main():
    try:  # Empezamos el Web Scrapping
        print(f'Realizando WebScrapping a github.com/topics/{interes}')
        print('Esta operacion puede tomar aproximadamente 1 minuto...\n')

        print('Pagina 1', end='')
        lista_total = click_load_more(1)  # Verificamos la primera pagina
        print('...completado')
        print(f'Topicos en pagina 1: {len(lista_total)}\n')

        for i in range(2, 11):
            print(f'Pagina {i}', end='')
            # hacemos click en load more para obtener mas repositorios
            lista_parcial = click_load_more(i)
            # recopilamos el resultado de todas las paginas
            lista_total.extend(lista_parcial)
            print('...completado')
            print(f'Topicos en pagina {i}: {len(lista_parcial)}, Topicos en total {len(lista_total)}\n')

        # ccontamos las aparciones de cada topico en un diccionario
        cuenta = dict(Counter(lista_total))
        # ordenamos el diccionario por el numero de apariciones
        ordenado = sorted(cuenta.items(), key=lambda item: -item[1])

        print()
        # Graficamos mediante matplotlib
        left = []
        height = []
        tick_label = []

        n = len(ordenado)
        for i in range(1, 22):
            left.append(i)
            height.append(ordenado[i][1])
            tick_label.append(ordenado[i][0])
            print(f'{ordenado[i][0],ordenado[i][1]}')

        plt.bar(left, height, tick_label=tick_label)

        plt.xlabel('TOPIC')
        plt.ylabel('NRO_APARICIONES')
        plt.title(f'github.com/topics/{interes}:')

        plt.show()

    except requests.exceptions.RequestException:
        print('Error al cargar la pagina de github.com/topics/{interes}')
        return -1


def click_load_more(i):
    retorno = []
    url = f'https://github.com/topics/{interes}?o=desc&s=updated&page={i}'
    # Solicitud HTTP a github.com/topics/{interes}
    response = requests.get(url)
    responseHTML = BeautifulSoup(response.text, features='html.parser')
    # Usamos el elmento HTML para obtener la informacion que queremos
    articles = responseHTML.find_all('article')
    for article in articles:
        if article != None:
            date_time = article.find('relative-time')
            if date_time != None:
                # Verificamos que esta en el rango de la fecha
                if date_time.attrs['datetime'] > date_time_30days_ago:
                    lista_topicos = article.find_all('a', class_='topic-tag topic-tag-link f6 mb-2')
                    for topico in lista_topicos:
                        # Añadimos a la lista que retornaremos
                        retorno.append(topico.text.strip())

    return retorno


# Con ese if verificamos que este archivo esta siendo ejecutado y no importado como una libreria
if __name__ == '__main__':
    main()
