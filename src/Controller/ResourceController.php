<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Controller;

use BackendGenerator\Bundle\BackendGeneratorBundle\Provider\ResourceProvider;
use BackendGenerator\Bundle\BackendGeneratorBundle\Service\Resource\NewResourceService;
use BackendGenerator\Bundle\BackendGeneratorBundle\Service\Resource\ResourceService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ResourceController extends AbstractController
{

    /**
     * @Route("/resources", methods={"GET"}, name="backend_generator_get_resources")
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
     * @Route("/resource/{name}", methods={"GET"}, name="backend_generator_get_resource")
     * @param ResourceProvider $resourceProvider
     * @param ResourceService $resourceService
     * @param $name
     * @return Response
     * @throws \BackendGenerator\Bundle\BackendGeneratorBundle\Exception\UndefinedResourceException
     */
    public function getResource(ResourceProvider $resourceProvider, ResourceService $resourceService, $name): Response
    {
        $resource = $resourceProvider->get($name);
        $result = $resourceService->resourceAnalyzer($resource);
        return new Response(json_encode($result));
    }



}