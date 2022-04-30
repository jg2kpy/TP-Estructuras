import re
import requests
from bs4 import BeautifulSoup
import matplotlib.pyplot as plt



def main():
    tiobe_index = [['Python', 0, 0], ['C', 0, 0], ['Java', 0, 0], ['Cpp', 0, 0], ['Csharp', 0, 0], ['VisualBasic', 0, 0], ['JavaScript', 0, 0], ['Assembly language', 0, 0], ['SQL', 0, 0], ['PHP', 0, 0], ['R', 0, 0], ['Delphi', 0, 0], ['Go', 0, 0], ['Swift', 0, 0], ['Ruby', 0, 0], ['visual-basic-6', 0, 0], ['Objective-C', 0, 0], ['Perl', 0, 0], ['Lua', 0, 0], ['matlab', 0, 0]]

    MAX = 0
    MIN = 0
    try:
        for language in tiobe_index:
            tries = 0
            match_topic = None
            while match_topic == None and tries < 3:
                tries = tries + 1
                url = 'https://www.github.com/topics/' + language[0]
                response = requests.get(url)
                responseHTML = BeautifulSoup(response.text, features='html.parser')

                match_topic = responseHTML.find(class_='h3 color-fg-muted')

            if match_topic == None:
                print(f'Error al obtener el lenguaje {language[0]}')
                tiobe_index.pop(tiobe_index.index(language))
            else:
                match_topic = match_topic.text.replace(',', '')
                language[1] = int(re.findall('[0-9]+', match_topic)[0])  # apariciones

                with open('Resultados.txt', "a") as f:
                    f.write(f'{language[0]},{language[1]}\n')

                if MAX < language[1]:
                    MAX = language[1]

                if MIN > language[1] or MIN == 0:
                    MIN = language[1]

    except requests.exceptions.RequestException:
        print('Error al cargar la pagina de github.com')
        return -1

    dif = MAX - MIN
    for language in tiobe_index:
        language[2] = ((language[1] - MIN)/dif) * 100

    tiobe_index.sort(key=mySort)

    for language in tiobe_index:
        print(f"{language[0]}, {language[1]}, {language[2]}")


    ##Graficar
    left = []
    height = []
    tick_label = []

    n = len(tiobe_index)
    for i in range(1,11):
        left.append(i)
        height.append(tiobe_index[n-i][1])
        tick_label.append(tiobe_index[n-i][0])

    plt.bar(left, height, tick_label = tick_label)

    plt.xlabel('NOMBRE_LENGUAJE')
    plt.ylabel('NRO_APARICIONES')
    plt.title('GitHub')
    
    plt.show()


def mySort(e):
    return e[2]


if __name__ == '__main__':
    main()
