<?php
declare(strict_types=1);

namespace BackendGenerator\Bundle\BackendGeneratorBundle\Controller;

use BackendGenerator\Bundle\BackendGeneratorBundle\Exception\FileAttributeNotFoundException;
use BackendGenerator\Bundle\BackendGeneratorBundle\Provider\ResourceRepositoryProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/delete-file")
 */
class DeleteFileController extends AbstractController
{
    /**
     * @Route("/{resourceName}/{resourceId}/{attribute}/{fileId}", name="backend_generator_delete_resource_file", methods={"DELETE"})
     */
    public function deleteFile(ResourceRepositoryProvider $resourceRepositoryProvider, string $resourceName, string $resourceId, string $attribute, string $fileId): Response
    {
        $resource = $resourceRepositoryProvider->get($resourceName);
        try {
            $resource->deleteFileByAttribute((int)$resourceId, $attribute, (int)$fileId);
        } catch (FileAttributeNotFoundException $e) {
            return new JsonResponse("Attribute {$attribute} not found for resource {$resourceName}", Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse();
    }
}