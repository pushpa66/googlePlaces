<?php
/**
 * Created by PhpStorm.
 * User: pushpe
 * Date: 5/12/18
 * Time: 9:23 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
use AppBundle\Entity\Review;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DataController extends Controller
{

    /**
     * @Route("/api/save", name="saveData")
     */
    public function saveDataAction(Request $request){
        $response = array();
        $entityManager = $this->getDoctrine()->getManager();

        $data = $this->getDoctrine()
            ->getRepository(Place::class)
            ->findOneBy(array('placeId' => $request->get('placeId')));

        if(!$data){
            $place = new Place();
            $place->setPlaceId($request->get('placeId'));
            $place->setAddress($request->get('address'));
            $place->setHours($request->get('hours'));
            $place->setName($request->get('name'));
            $place->setWebsite($request->get('website'));
            $place->setPhone($request->get('phone'));
            $place->setLat($request->get('lat'));
            $place->setLng($request->get('lng'));

            $reviews = json_decode($request->get('reviews'), true);

//        var_dump($reviews[0]['placeId']);
//        die("die");
            if(count($reviews) > 0){
                foreach ($reviews as $r){
                    $review = new Review();
                    $review->setPlace($place);
                    $review->setAuthorName($r['authorName']);
                    $review->setAuthorUrl($r['authorUrl']);
                    $review->setLanguage($r['language']);
                    $review->setProfilePhotoUrl($r['profilePhotoUrl']);
                    $review->setRating($r['rating']);
                    $review->setRelativeTimeDescription($r['relativeTimeDescription']);
                    $review->setText($r['text']);
                    $review->setTime($r['time']);
                    $entityManager->persist($review);
                }
            }

            $entityManager->persist($place);
            $entityManager->flush();
            $response['status'] = 'success';
        } else {
            $response['status'] = 'failed';
        }

//        $saveData = $entityManager->getRepository(Data::class)
//            ->findOneBy(array('id' => $readData['id']));
//        return $this->render(':default:addSuccess.html.twig');
        return new JsonResponse($response);
    }
}