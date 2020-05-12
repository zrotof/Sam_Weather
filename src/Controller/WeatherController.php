<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\WeatherService;
use Symfony\Component\HttpFoundation\Request;
use App\Form\SearchBarType;

class WeatherController extends AbstractController
{
   
    private $weatherService;

    public function __construct(WeatherService $weather)
    {
        $this->weatherService = $weather;
    }

    /**
     * @Route("/", name="home")
     */
    public function home()
    {
       
        return $this->render('weather/home.html.twig');
    }

    /**
     * @Route("/weather", name="weather")
     */
     public function weather(Request $request){

        $town;
        $searchForm= $this->createForm(SearchBarType::class);
        $searchForm->handleRequest($request);

        if($searchForm->isSubmitted() && $searchForm->isValid()){

            $contact= $searchForm->getData();

            $this->town=$contact['town'];
        }
        else{
            $this->town='Toulouse';
        }


        $result = $this->weatherService->getWeather($this->town);

            return $this->render('weather/weather.html.twig', [
                'message' => $result['mes'],
                'weathers' => $result['list'],
                'searchBarForm' =>$searchForm->createView() 
               ]); 

         
     }
}
