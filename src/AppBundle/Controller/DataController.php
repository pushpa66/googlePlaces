<?php
/**
 * Created by PhpStorm.
 * User: pushpe
 * Date: 5/12/18
 * Time: 9:23 PM
 */

namespace AppBundle\Controller;

use AppBundle\Entity\Place;
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
//        $readData = $request->request->all();
        $entityManager = $this->getDoctrine()->getManager();

        $place = new Place();
        $place->setPlaceId($request->get('placeId'));
        $place->setAddress($request->get('address'));
        $place->setHours($request->get('hours'));
        $place->setName($request->get('name'));
        $place->setWebsite($request->get('website'));
        $place->setPhone($request->get('phone'));
        $entityManager->persist($place);
        $entityManager->flush();
//        $saveData = $entityManager->getRepository(Data::class)
//            ->findOneBy(array('id' => $readData['id']));
//        return $this->render(':default:addSuccess.html.twig');
        $response = array('status' => 'success');
        return new JsonResponse($response);
    }
}