<?php

namespace App\Service;
use Symfony\Component\Dotenv\Dotenv;
use App\Entity\Weather;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Config\Definition\Exception\Exception;




class WeatherService
{

    private $apiKey;

    public function __construct($apiWeatherKey)
    {
        $this->client = HttpClient::create();
        $this->apiKey = $apiWeatherKey;
    }


    /**
     * @return array
     */
    public function getWeather(String $givenTown)
    {

        $listWeather = [];
        $messageError= NULL;
        
        
        try{

            $response = $this->client->request('GET', 'https://api.openweathermap.org/data/2.5/forecast?q='.$givenTown.'&lang=fr&units=metric&cnt=8&appid='.$this->apiKey);
            $statusCode = $response->getStatusCode();
            
            if( $statusCode == 404 ) {
                throw new Exception('Nous ne trouvons aucun résultat, veuillez vérifier l\'orthographe de la ville et reessayez.');
            }

            if( $statusCode !== 200 ) {
                throw new Exception('Oops ! Il se pourrait que quelque chose n\'ai pas marché. Veuillez reessayer plus tard.');
            }
        }
        catch(Exception $e){

                $messageError = $e->getMessage();
                return array(
                    'list' => $listWeather,
                    'mes' =>$messageError
                );
        }


        $json= json_decode($response->getContent(), true);
   
        for( $i=0; $i<8; $i++ ){

          
            //Gestion de l'abreviation du pays
            $country = $json['city']['country'];

            //Gestion du nom de la ville
            $town = $json['city']['name'];

            //Gestion d la température
            $temperature = $json['list'][$i]['main']['temp'];

            //Gestion du vent
            $wind = $json['list'][$i]['wind']['speed'];

            //Gestion de la descritption
            $description = $json['list'][$i]['weather'][0]['description'];

            //Gestion de l'humidité
            $humidity = $json['list'][$i]['main']['humidity'];

            //Gestion de l'icône
            $icon = $json['list'][$i]['weather'][0]['main'];



            //gestion date et heure
            $dateTime = explode(" ", $json['list'][$i]['dt_txt']);

            $date= $dateTime[0];
            $hour=$dateTime[1];
            
            $listWeather [] = new Weather($country, $town, $temperature, $wind, $description, $humidity, $icon, $date, $hour); 

        }

        

        return array(
            'list' => $listWeather,
            'mes' =>$messageError
        );
    }
}
