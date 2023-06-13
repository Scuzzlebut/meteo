<?php
/**
 * (PHP 4.0.6) potrebbe risultare lenta ed andare in timeout se non è stato aumentato il max_execution_time sul server
 * (PHP 4.1.2) testata e stabile
 **/

define('_DEFAULT_CITY', "milano");
define('_DEFAULT_LAT', "45.46427");
define('_DEFAULT_LONG', "9.18951");
define('_BASE_GEOCODING_URL', "https://geocoding-api.open-meteo.com/v1/");
define('_BASE_FORECAST_URL', "https://api.open-meteo.com//v1/");
define('_MAX_API_CALLS', 10);

class Main
{
    var $city;
    var $lat;
    var $long;
    var $forecast;

    function setDefaults()
    {
        $this->city = _DEFAULT_CITY;
        $this->lat = _DEFAULT_LAT;
        $this->long = _DEFAULT_LONG;
    }
    /**
     * semplice curl setup con url in entrate e response in uscita
     * @param string $url
     * url da chiamare
     * @return string
     * risposta della chiamata non elaborata
    **/
    function api_call($url){
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($curl, CURLOPT_VERBOSE, 0);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }
    /**
     * chiamata all'api di open meteo per recuperare le coordinate della città cercata
     * popola direttamente le variabili della classe
     * @param string $city
     * stringa ricercata, filtra esclusivamente città italiane
     * @return bool
     * false se non trova una città
     * true in caso di almeno un riscontro
     */
    function searchCity($city)
    {
        $normalized_city=str_replace(" ","+",$city);
        $response = $this->api_call(_BASE_GEOCODING_URL . "search?count=" . _MAX_API_CALLS . "&language=it&format=json&name=" . $normalized_city);

        if ($response === false)
            return false;

        $data = custom_json_decode($response, true);
        if (!isset($data["results"]))
            return false;

        foreach ($data["results"] as $d) {
            if ($d["country_code"] == "IT") {
                $this->city = $city;
                $this->lat = $d["latitude"];
                $this->long = $d["longitude"];
            }
        }
        if (!$this->city)
            return false;
        return true;
    }

    /**
     * chiamata all'api di open meteo per recuperare le previsioni per la città selezionata
     * popola direttamente $forecast se trova le previsioni
     * @return bool
     * false se si verifica un errore di collegamento API o programmazione
     * true se va a buon fine
     */
    function getForecast()
    {
        if(!$this->lat || !$this->long)
            return false;
        $response = $this->api_call(_BASE_FORECAST_URL . "forecast?latitude=" . $this->lat . "&longitude=" . $this->long."&hourly=precipitation_probability&current_weather=true&daily=weathercode,temperature_2m_max,temperature_2m_min,precipitation_probability_mean&forecast_days=6&timezone=auto");

        if ($response === false)
            return false;


        $data = custom_json_decode($response, true);
        if(isset($data["error"]))
            return false;
        $data["current_weather"]["winddirection_cardinal"] = $this->degreeToCardinal($data["current_weather"]["winddirection"]);
        $data["current_weather"]["wmo_icon"] = $this->wmoToIcon($data["current_weather"]["weathercode"]);
        $data["daily"]["wmo_icon"] = array();
        foreach ($data["daily"]["weathercode"] as $i=>$d) {
            $data["daily"]["wmo_icon"][$i]=$this->wmoToIcon($d);
        }
        $this->forecast = $data;
        return true;
    }
    /**
     * restituisce nome icona a partire dal codice wmo
     * @param int
     * codice wmo secondo documentazione open-meteo
     * @return string
     * ritorna sempre un'icona, fallback al cielo sereno
     */
    function wmoToIcon($code){
        switch ($code) {
            case 0:
            default:
                $icon = "icon-2.svg";
                break;
            case 1:
            case 2:
                $icon = "icon-3.svg";
                break;
            case 3:
                $icon = "icon-5.svg";
                break;
            case 45:
            case 48:
                $icon = "icon-8.svg";
                break;
            case 51:
            case 53:
            case 55:
            case 56:
            case 57:
                $icon = "icon-9.svg";
                break;
            case 71:
            case 73:
            case 75:
            case 77:
                $icon = "icon-13.svg";
                break;
            case 61:
            case 63:
            case 65:
            case 66:
            case 67:
            case 80:
            case 81:
            case 82:
                $icon = "icon-10.svg";
                break;
            case 85:
            case 86:
                $icon = "icon-14.svg";
                break;
            case 95:
                $icon = "icon-12.svg";
                break;
            case 96:
            case 99:
                $icon = "icon-11.svg";
                break;
        }
        return $icon;
    }
    /**
     * converte i gradi in punto cardinale
     * @param int
     * gradi da convertire in direzione dev'essere tra 0 e 360
     * @return string
     * ritorna stringa vuota se fuori dal range consentito
     */
    function degreeToCardinal($gradi){
        $direzione = '';
        if ($gradi >= 0 && $gradi <= 360) {
            if ($gradi >= 338 || $gradi <= 22) {
                $direzione = 'Nord';
            } elseif ($gradi >= 23 && $gradi <= 67) {
                $direzione = 'Nord-Est';
            } elseif ($gradi >= 68 && $gradi <= 112) {
                $direzione = 'Est';
            } elseif ($gradi >= 113 && $gradi <= 157) {
                $direzione = 'Sud-Est';
            } elseif ($gradi >= 158 && $gradi <= 202) {
                $direzione = 'Sud';
            } elseif ($gradi >= 203 && $gradi <= 247) {
                $direzione = 'Sud-Ovest';
            } elseif ($gradi >= 248 && $gradi <= 292) {
                $direzione = 'Ovest';
            } elseif ($gradi >= 293 && $gradi <= 337) {
                $direzione = 'Nord-Ovest';
            }
        }
        return $direzione;
    }
    /**
     * funzione per ricavare le label dei giorni della settimana a venire
     * @param int
     * giorni voluti
     * @return array
     * ritorna array con le label dei giorni della settimana richiesti
     */
    function getForecastedDaysLabels($daysToForecast = 6) {
        $forecasted_days = array();
        for ($day = 0; $day < $daysToForecast; $day++){
            $forecasted_days[$day] = date("l", mktime(0, 0, 0, date("m"), date("d")+$day+1, date("Y")));
        }
        return $forecasted_days;
    }

    function loadCity()
    {
        require 'php4_json_decode.php';
        if (isset($_GET["city"])) {
            if (!$this->searchCity($_GET["city"]))
                $this->setDefaults();
        } else
            $this->setDefaults();
        if(!$this->getForecast())
        {
            header('HTTP/1.1 500 Internal Server Error', true, 500);
            trigger_error('Impossibile reperire le previsioni', E_USER_ERROR);
        }
        $city = ucfirst(strtolower($this->city));
        $forecast = $this->forecast;
        $today = date("l");
        $forecasted_days = $this->getForecastedDaysLabels();
        require "home.php";
    }
}

$main = new Main();
$main->loadCity();

?>