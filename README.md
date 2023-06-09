# Premessa
L'attività proposta ha solo scopo dimostrativo. Tutte le informazioni riguardanti le attività sottostanti sono da ritenersi riservate e non divulgabili.
La prova consiste nello sviluppo di due piccole applicazioni, una in PHP e una in un framework Javascript a scelta (Angular, VueJS, React, ec...), da poter essere lanciate e testate, e corredate da una documentazione essenziale sul funzionamento.

# Prima di iniziare
Clonare il progetto e creare un nuovo ramo da master nominandolo con il proprio nome e cognome (nome_cognome). 
I commit devono essere fatti solo sul ramo appena creato.
Ad ogni fase deve corrispondere almeno un commit su Git, con una descrizione sintetica ed esplicativa dell’attività.

# Attività PHP

## 1
**Come utente che arriva sul sito vedo una pagina statica con le informazioni essenziali per le previsioni meteorologiche fittizie di una località**

### Requisiti
Il progetto dev’essere composto da una sola pagina navigabile. 
La pagina deve contenere esclusivamente le previsioni, la ricerca, il nome del sito e il logo.
Dal progetto devono essere esclusi gli elementi e file superflui.
![Mockup](/images/mockup.png)


## 2
**Come utente che arriva sul sito vedo le previsioni correnti fino a 7 giorni su Milano**

### Requisiti
Le previsioni predefinite di atterraggio devono essere per la città di Milano ed essere caricate dinamicamente lato server utilizzando esclusivamente PHP come linguaggio di programmazione backend. I dati previsionali giornalieri devono essere recuperati da [OpenMeteo](https://open-meteo.com)
*. 
L’endpoint delle API principale da utilizzare è:
https://api.open-meteo.com/v1/forecast


## 3
**Come utente posso cercare le previsioni di una località a scelta**

### Requisiti
Rendere funzionante la ricerca per le località italiane, visualizzando le previsioni della località cercata. 


# Attività in framework Javascript

**Come utente che arriva sul sito vedo una pagina statica con un menu dropdown di selezione località, che mostra le previsioni meteorologiche orarie della località selezionata**

### Requisiti
Il progetto dev’essere composto da una sola pagina navigabile. Questa homepage svolge la seguente funzionalità:
- visualizza un menu dropdown con una selezione di 5 città a tua scelta;
- per la città selezionata legge le previsioni orarie disponibili tramite le API gratuite di Open-Meteo (vedi URL di esempio di seguito), con i valori di temperatura, umidità relativa e velocità del vento;
- mostra in forma tabellare le previsioni orarie della città sotto il menu dropdown.

Esempio di URL di lettura previsioni orarie:
https://api.open-meteo.com/v1/forecast?latitude=52.52&longitude=13.41&current_weather=true&hourly=temperature_2m,relativehumidity_2m,windspeed_10m




# Attività Facoltative (a scelta)

## 4
**Come utente vedo un suggerimento per la località che sto cercando**

### Requisiti
Aggiungere un completamento automatico alla ricerca dopo avere digitato almeno 3 caratteri.

## 5
**Come sviluppatore posso utilizzare il progetto usando Docker**

### Requisiti
Aggiungere un Dockerfile che permetta di costruire un’immagine Docker funzionante del progetto visibile sulla porta 80.

## 6
**Come sviluppatore posso utilizzare versioni non recenti di PHP**

### Requisiti
Permettere di utilizzare versioni datate di PHP, inferiori alla 5.3. Più retroattiva sarà la compatibilità, migliore sarà il beneficio.

# Conclusione**
Il progetto deve essere consegnato sotto forma di Pull Request sul ramo master.
La consegna può essere anche parziale.



# Note
*Open-Meteo e OpenWeather sono servizi indipendenti da ilMeteo.it

**Il risultato finale del test avrà solo finalità dimostrative. In nessun modo iLMeteo prevede finalità di lucro o riutilizzo dello stesso.