<?php

namespace AppBundle\Controller;

use AppBundle\Entity\IAPIObject;
use AppBundle\Entity\Product;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends AAPIObjectController
{
    // TODO : filters
    /**
     * @ApiDoc(
     *     section = "Products",
     *     description="Gets all the Products available",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     },
     *  )
     * @return \Symfony\Component\HttpFoundation\Response
     */
//    public function getProductsAction(Request $request){
//        return $this->getObjects($request);
//    }


    /**
     * @ApiDoc(
     *     section = "Products",
     *     description="Gets all the Products available",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     },
     *  )
     * @param $productId
     * @return JsonResponse
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getProductAction($productId){
        return $this->getObject($productId);
    }

    /**
     * @ApiDoc(
     *     section = "Products",
     *     description="Create a new product wth the given name",
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The new product name"},
     *          {"name"="description",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The new product description"},
     *          {"name"="url",
     *          "dataType"="string",
     *          "required"=false,
     *          "description"="The new product url"},
     *          {"name"="categories[]",
     *          "dataType"="int",
     *          "required"=false,
     *          "description"="The new product category ids"},
     *          {"name"="brand",
     *          "dataType"="int",
     *          "required"=true,
     *          "description"="The new product brand id"},
     *     },
     *  )
     * @param Request $request
     * @return JsonResponse
     */
    public function postProductsAction(Request $request){
        $categories = $request->get('categories');
        if (!empty($categories) && !is_array($categories)) {

            return new JsonResponse(null, 400);
        }
        else if (!empty($categories)){
            // TODO check Category existency, return 404 if not found
        }
        // TODO check on brand existency
        return $this->postObject($request);
    }


    /**
     * @ApiDoc(
     *     section = "Products",
     *     description="Delete a given product",
     *     requirements={
     *          {"name"="productId",
     *          "dataType"="int",
     *          "description"="The product Id"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param $productId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteProductAction($productId){
        return $this->deleteObject($productId);
    }

    /**
     * @ApiDoc(
     *     section = "Products",
     *     description="Delete a given product",
     *     requirements={
     *          {"name"="productId",
     *          "dataType"="int",
     *          "description"="The product Id"}
     *     },
     *     parameters={
     *          {"name"="name",
     *          "dataType"="string",
     *          "required"=true,
     *          "description"="The product new name"}
     *     },
     *     statusCodes={
     *          200="Success",
     *          401="Authentication failed",
     *          404="Entity not found",
     *     }
     *  )
     * @param Request $request
     * @param $productId
     * @return JsonResponse
     */
    public function putProductAction(Request $request, $productId){
        return $this->putObject($request, $productId);
    }

    /**
     * @inheritdoc
     */
    protected function getNewObject(){
        return new Product();
    }

    /**
     * @inheritdoc
     */
    protected function getDoctrineRepository(){
        return $this->getDoctrine()->getRepository('AppBundle:Product');
    }

    /**
     * @inheritdoc
     */
    protected function serializeObject(IAPIObject $product){
        /* TODO: serialization of linked brand and categories,
         * ideally move this function in new classes for each object implementing an interface with a serialize function
         */
        /** @var Product $product */
        return [
            'name' => $product->getName(),
            'description' => $product->getDescription(),
            'id' => $product->getId(),
        ];
    }

    /**
     * @inheritdoc
     */
    protected function updateObject(Request $request,IAPIObject $product){
        /** @var Product $product */
        $product->setName($request->get('name'));
        $product->setDescription($request->get('description'));
        $product->setCategories($request->get('categories'));
    }

}