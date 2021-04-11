<?php

declare(strict_types=1);

namespace App\Controller;

use App\Resources\Listing\Listing;
use App\Resources\Listing\Model\ResourceListingCollection;
use App\Service\PropertiesSerializer;
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
     * @Route("/listing/{resource}", methods={"GET"}, name="resources_listing")
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
     * @Route("/listings", methods={"POST"}, name="resources_listings")
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