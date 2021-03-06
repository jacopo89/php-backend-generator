<?php

declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Controller;

use BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Listing;
use BackendGenerator\Bundle\BackendGeneratorBundle\Resources\Listing\Model\ResourceListingCollection;
use BackendGenerator\Bundle\BackendGeneratorBundle\Service\PropertiesSerializer;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/resources")
 */
class ResourceListingController
{
    /**
     * @Route("/listing/{resource}", methods={"GET"}, name="backend_generator_resources_listing")
     * @return Response
     */
    public function listings(string $resource, Listing $listing, PropertiesSerializer $serializer, Request $request): Response
    {
        $searchTerm = $request->query->get('search');
        $listingCollection = $listing->getListing($resource, $searchTerm);

        if($listingCollection instanceof ResourceListingCollection) {
            return new JsonResponse(
                $serializer->json($listingCollection->getResourcesListing()),
                Response::HTTP_OK,
                [],
                true);
        }

        throw new NotFoundHttpException();
    }

    /**
     * @Route("/listings", methods={"POST"}, name="backend_generator_resources_listings")
     * @return Response
     */
    public function listingsBlock(Listing $listing, PropertiesSerializer $serializer, Request $request): Response
    {
        $content = json_decode($request->getContent(), true);
        $resourceNames = $content["resources"];
        $searchTerm = $request->query->get('search');
        $jsonResponse = [];
        foreach($resourceNames as $resourceName){
            if(is_string($resourceName)){
                $listingCollection = $listing->getListing($resourceName, $searchTerm);
                if($listingCollection instanceof ResourceListingCollection) {
                    $jsonResponse[$resourceName] = $listingCollection->getResourcesListing();
                    continue;
                }
                throw new NotFoundHttpException();
            }
            throw new BadRequestException("Resources should contain an array of strings");
        }
        return new JsonResponse(
            $serializer->json($jsonResponse),
            Response::HTTP_OK,
            [],
            true);


    }
}