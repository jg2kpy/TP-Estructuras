import re
import requests
from bs4 import BeautifulSoup

def main():
    tiobe_index = ['Python','C','Java','Cpp','Csharp','VisualBasic','JavaScript','Assembly language','SQL','PHP','R','Delphi','Go','Swift','Ruby','ClassicVisualBasic','Objective-C','Perl','Lua','MATLAB']
    try:
        for language in tiobe_index:
            url = 'https://www.github.com/topics/' + language
            response = requests.get(url)
            responseHTML = BeautifulSoup(response.text, features='html.parser')
            
            match_topic = responseHTML.find(class_='h3 color-fg-muted').text.replace(',','')
            cantidad = re.findall('[0-9]+', match_topic)[0]
    except requests.exceptions.RequestException as e:
        eprint('Error al cargar la pagina de github.com')
        return -1

if __name__ == '__main__':
    main()