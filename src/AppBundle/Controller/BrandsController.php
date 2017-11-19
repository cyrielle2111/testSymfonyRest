<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
use AppBundle\Entity\IAPIObject;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BrandsController extends AAPIObjectController
{
    /**
     * @ApiDoc(
     *     section = "Brands",
     *     description="Gets all the brands available",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     }
     *  )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getBrandsAction(){
        return $this->getObjects();
    }

    /**
     * @ApiDoc(
     *     section = "Brands",
     *     description="Create a new brand wth the given name",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The brand new name"}
     *     },
     *  )
     * @param Request $request
     * @return JsonResponse
     */
    public function postBrandsAction(Request $request){
        return $this->postObject($request);
    }

    /**
     * @ApiDoc(
     *     section = "Brands",
     *     description="Delete a given brand",
     *     requirements={
     *          {"name"="brandId",
     *          "dataType"="int",
     *          "description"="The brand Id"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param $brandId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteBrandAction($brandId){
        return $this->deleteObject($brandId);
    }

    /**
     * @ApiDoc(
     *     section = "Brands",
     *     description="Delete a given brand",
     *     requirements={
     *          {"name"="brandId",
     *          "dataType"="int",
     *          "description"="The brand Id"}
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The brand new name"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param Request $request
     * @param $brandId
     * @return JsonResponse
     */
    public function putBrandAction(Request $request, $brandId){
        return $this->putObject($request, $brandId);
    }

    /**
     * @inheritdoc
     */
    protected function getNewObject(){
        return new Brand();
    }

    /**
     * @inheritdoc
     */
    protected function getDoctrineRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Brand');
    }

    /**
     * @inheritdoc
     */
    protected function serializeObject(IAPIObject $brand){
        /** @var Brand $brand */
        return [
            'id' => $brand->getId(),
            'name' => $brand->getName(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function updateObject(Request $request,IAPIObject $brand){
        /** @var Brand $brand */
        $brand->setName($request->get('name'));
    }

}