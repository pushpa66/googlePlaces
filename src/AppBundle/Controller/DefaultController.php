<?php

namespace AppBundle\Controller;

use AppBundle\Config\Configuration;
use AppBundle\Entity\Place;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'base_dir' => realpath($this->container->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
        ));
    }

    /**
     * @Route("/list", name="list_places")
     */
    public function listPlaces(Request $request){
        $query = $request->get('query');

        $query = str_replace(" ", "+", $query);

        $places = $this->searchPlaces($query);

        return $this->render('default/list.html.twig',array(
                'query' => $query,
                'places'=> $places
            ));
    }

    private function searchPlaces($query){
        $query = str_replace(" ", "+", $query);

        $data = array();
        $places = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=".Configuration::SearchKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 2ff44bac-4a9b-44b1-ad2d-e8c8909b724f"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $data = json_decode($response, true);
            $data = $data['results'];

            foreach ($data as $item){
                $place = new Place();
                $place->setPlaceId($item['place_id']);
                $place->setName($item['name']);
                $place->setAddress($item['formatted_address']);
                $place = $this->placeIdSearch($item['place_id'],$place);
                $places[] = $place;
            }
        }
        return $places;
    }

    private function placeIdSearch($placeId,$place){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/details/json?placeid=$placeId&key=".Configuration::SearchKey,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Postman-Token: 4d983897-29ef-4b98-9a1c-1c08386e2197"
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
        } else {
            $response = json_decode($response, true);
            $data = $response['result'];
            if(isset($data['formatted_phone_number'])){
                $place->setPhone($data['formatted_phone_number']);
            }
            if(isset($data['opening_hours']['weekday_text'])){
                $place->setHours(implode(",", $data['opening_hours']['weekday_text']));
            }

            if(isset($data['website'])){
                $place->setWebsite($data['website']);
            }

        }

        return $place;
    }

}
