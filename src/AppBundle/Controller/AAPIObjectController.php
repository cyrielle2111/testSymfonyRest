<?php

namespace AppBundle\Controller;


use AppBundle\Entity\IAPIObject;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

abstract class AAPIObjectController extends Controller
{
    // TODO Checks on parameters for POST, PUT, DELETE
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
    final protected function getObject($objectId){
        $closure = \Closure::bind(
            function ($object) {
                return new JsonResponse($this->serializeObject($object));
            },
            $this
        );
        return $this->checkObjectAndExecute($closure, $objectId);
    }

    /**
     * @param int $objectId
     * @return JsonResponse
     */
    final protected function deleteObject($objectId){
        $closure = \Closure::bind(
            function ($object) {
                $this->getDoctrine()->getManager()->remove($object);
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse();
            },
            $this
        );
        return $this->checkObjectAndExecute($closure, $objectId);
    }

    /**
     * @param Request $request
     * @param $objectId
     * @return JsonResponse
     */
    final protected function putObject(Request $request, $objectId){
        $closure = \Closure::bind(
            function ($object) use ($request){
                $this->updateObject($request, $object);
                $this->getDoctrine()->getManager()->persist($object);
                $this->getDoctrine()->getManager()->flush();
                return new JsonResponse($this->serializeObject($object));
            },
            $this
        );
        return $this->checkObjectAndExecute($closure, $objectId);
    }

    final private function checkObjectAndExecute(\Closure $callBack, $objectId){
        $object = $this->getDoctrineRepository()->find($objectId);
        if (empty($object)){
            return new JsonResponse(null,404);
        }
        return $callBack($object);
    }

    /**
     * @return IAPIObject
     */
    abstract protected function getNewObject();

    /**
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    abstract protected function getDoctrineRepository();

    /**
     * TODO : better to put the serializing function either in the doctrine object or in a service...
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