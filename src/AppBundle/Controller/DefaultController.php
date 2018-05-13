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
        $reviews = array();

        $curl = curl_init();
        curl_setopt_array($curl, array(
//            CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$query&key=".Configuration::SearchKey,
            CURLOPT_URL => "https://maps.googleapis.com/maps/api/place/search/json?keyword=$query&location=36.778259,-119.417931&radius=50000&hasNextPage=true&nextPage()=true&sensor=false&key=".Configuration::SearchKey."&type=medical",
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
                $placeAndReviews = $this->placeIdSearch($item['place_id']);
                $places[] = $placeAndReviews;
            }
        }
        return $places;
    }

    private function placeIdSearch($placeId){
        $place = new Place();
        $place->setPlaceId($placeId);

        $placeAndReviews = array();
        $reviews = array();

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
            $place->setName($data['name']);
            $place->setAddress($data['formatted_address']);
            if(isset($data['formatted_phone_number'])){
                $place->setPhone($data['formatted_phone_number']);
            } else {
                $place->setPhone("-");
            }
            if(isset($data['opening_hours']['weekday_text'])){
                $place->setHours(implode(",", $data['opening_hours']['weekday_text']));
            } else {
                $place->setHours('-');
            }
            if(isset($data['website'])){
                $place->setWebsite($data['website']);
            } else {
                $place->setWebsite('-');
            }

            $place->setLng($data['geometry']['location']['lng']);
            $place->setLat($data['geometry']['location']['lat']);

            $placeAndReviews['place'] = $place;

            if(isset($data['reviews'])){
                $readReviews = $data['reviews'];

                foreach ($readReviews as $readReview){
                    $review = array();
                    $review['placeId'] = $placeId;
                    if(isset($readReview['author_name'])){
                        $review['authorName'] = $readReview['author_name'];
                    } else {
                        $review['authorName'] = '-';
                    }
                    if(isset($readReview['author_url'])){
                        $review['authorUrl'] = $readReview['author_url'];
                    } else {
                        $review['authorUrl'] = '-';
                    }
                    if(isset($readReview['language'])){
                        $review['language'] = $readReview['language'];
                    } else {
                        $review['language'] = '-';
                    }
                    if(isset($readReview['profile_photo_url'])){
                        $review['profilePhotoUrl'] = $readReview['profile_photo_url'];
                    } else {
                        $review['profilePhotoUrl'] = '-';
                    }
                    $review['rating'] = $readReview['rating'];
                    if (isset($readReview['relative_time_description'])){
                        $review['relativeTimeDescription'] = $readReview['relative_time_description'];
                    } else {
                        $review['relativeTimeDescription'] = '-';
                    }
                    if (isset($readReview['text'])){
                        $review['text'] = $readReview['text'];
                    } else {
                        $review['text'] = '-';
                    }
                    $review['time'] = $readReview['time'];

                    $reviews[] = $review;
                }
            }
            $placeAndReviews['reviews'] = json_encode($reviews);
        }

        return $placeAndReviews;
    }

}
