<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Brand;
use AppBundle\Entity\Category;
use AppBundle\Entity\IAPIObject;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CategoriesController extends AAPIObjectController
{
    /**
     * @ApiDoc(
     *     section = "Categories",
     *     description="Gets all the Categories available",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     }
     *  )
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getCategoriesAction(){
        return $this->getObjects();
    }

    /**
     * @ApiDoc(
     *     section = "Categories",
     *     description="Create a new category wth the given name",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The category new name"}
     *     },
     *  )
     * @param Request $request
     * @return JsonResponse
     */
    public function postCategoriesAction(Request $request){
        return $this->postObject($request);
    }

    /**
     * @ApiDoc(
     *     section = "Categories",
     *     description="Delete a given category",
     *     requirements={
     *          {"name"="categoryId",
     *          "dataType"="int",
     *          "description"="The category Id"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param $categoryId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteCategoryAction($categoryId){
        return $this->deleteObject($categoryId);
    }

    /**
     * @ApiDoc(
     *     section = "Categories",
     *     description="Delete a given category",
     *     requirements={
     *          {"name"="categoryId",
     *          "dataType"="int",
     *          "description"="The category Id"}
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The category new name"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param Request $request
     * @param $categoryId
     * @return JsonResponse
     */
    public function putCategoryAction(Request $request, $categoryId){
        return $this->putObject($request, $categoryId);
    }

    /**
     * @inheritdoc
     */
    protected function getNewObject(){
        return new Category();
    }

    /**
     * @inheritdoc
     */
    protected function getDoctrineRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Category');
    }

    /**
     * @inheritdoc
     */
    protected function serializeObject(IAPIObject $category){
        /** @var Category $category */
        return [
            'id' => $category->getId(),
            'name' => $category->getName(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function updateObject(Request $request,IAPIObject $category){
        /** @var Category $category */
        $category->setName($request->get('name'));
    }

}