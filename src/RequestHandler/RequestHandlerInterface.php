<?php
declare(strict_types=1);

namespace Enm\JsonApi\Server\RequestHandler;

use Enm\JsonApi\Model\Request\RequestInterface;
use Enm\JsonApi\Model\Response\ResponseInterface;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
interface RequestHandlerInterface
{
    public function fetchResource(RequestInterface $request): ResponseInterface;

    public function fetchResources(RequestInterface $request): ResponseInterface;

    public function fetchRelationship(RequestInterface $request): ResponseInterface;

    public function createResource(RequestInterface $request): ResponseInterface;

    public function patchResource(RequestInterface $request): ResponseInterface;

    public function deleteResource(RequestInterface $request): ResponseInterface;

    public function addRelatedResources(RequestInterface $request): ResponseInterface;

    public function replaceRelatedResources(RequestInterface $request): ResponseInterface;

    public function removeRelatedResources(RequestInterface $request): ResponseInterface;
}
