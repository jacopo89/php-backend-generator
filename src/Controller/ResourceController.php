<?php
declare(strict_types=1);

namespace App\Controller;

use App\Provider\ResourceProvider;
use App\Service\Resource\NewResourceService;
use App\Service\Resource\ResourceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{

    /**
     * @Route("/resources", methods={"GET"}, name="get-resources")
     * @param ResourceProvider $resourceProvider
     * @param ResourceService $resourceService
     * @return Response
     */
    public function getResources(ResourceProvider $resourceProvider, ResourceService $newResourceService): Response
    {
        $resources = $resourceProvider->getResources();
        $results = [];

        foreach ($resources as $resource){
            $result = $newResourceService->resourceAnalyzer($resource);
            $results[$resource->getResourceName()] = $result;
        }
        return new Response(json_encode($results));
    }


    /**
     * @Route("/resource/{name}", methods={"GET"}, name="get-resource")
     * @param ResourceProvider $resourceProvider
     * @param ResourceService $resourceService
     * @param $name
     * @return Response
     * @throws \App\Exception\UndefinedResourceException
     */
    public function getResource(ResourceProvider $resourceProvider, ResourceService $resourceService, $name): Response
    {
        $resource = $resourceProvider->get($name);
        $result = $resourceService->resourceAnalyzer($resource);
        return new Response(json_encode($result));
    }



}