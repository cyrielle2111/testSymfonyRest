<?php

namespace AppBundle\Controller;


use AppBundle\Entity\IAPIObject;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AAPIObjectController extends Controller
{
    /**
     * @return JsonResponse
     */
    final protected function getObjects(){
        $objects = $this->getDoctrineRepository()->findAll();
        $data = [];
        foreach($objects as $object){
            $data[] = $this->serializeObject($object);
        }
        return new JsonResponse($data);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    final protected function postObject(Request $request){
        $object = $this->getNewObject();
        $this->updateObject($request, $object);
        $this->getDoctrine()->getManager()->persist($object);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($this->serializeObject($object));
    }

    /**
     * @param int $objectId
     * @return JsonResponse
     */
    final protected function deleteObject($objectId){
        $object = $this->getDoctrineRepository()->find($objectId);
        if (empty($object)){
            return new JsonResponse(null,404);
        }
        $this->getDoctrine()->getManager()->remove($object);
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse();
    }

    /**
     * @param Request $request
     * @param $objectId
     * @return JsonResponse
     */
    final protected function putObject(Request $request, $objectId){
        $object = $this->getDoctrineRepository()->find($objectId);
        if (empty($object)){
            return new JsonResponse(null,404);
        }
        $this->getDoctrine()->getManager()->persist($this->updateObject($request, $object));
        $this->getDoctrine()->getManager()->flush();
        return new JsonResponse($this->serializeObject($object));
    }

    /**
     * @return IAPIObject
     */
    abstract protected function getNewObject();

    /**
     * @return mixed
     */
    abstract protected function getDoctrineRepository();

    /**
     * Serialize the objects
     * @param IAPIObject $object
     * @return array
     */
    abstract protected function serializeObject(IAPIObject $object);

    /**
     * Update the object with data from request and return
     * @param Request $request
     * @param IAPIObject $brand
     */
    abstract protected function updateObject(Request $request, IAPIObject $brand);
}