<?php
declare(strict_types=1);

namespace Enm\JsonApi\Server;

use Enm\JsonApi\Exception\BadRequestException;
use Enm\JsonApi\Exception\UnsupportedMediaTypeException;
use Enm\JsonApi\Exception\UnsupportedTypeException;
use Enm\JsonApi\JsonApiTrait;
use Enm\JsonApi\Model\Document\DocumentInterface;
use Enm\JsonApi\Model\Error\Error;
use Enm\JsonApi\Model\JsonApi;
use Enm\JsonApi\Model\Request\RequestInterface;
use Enm\JsonApi\Model\Resource\ResourceInterface;
use Enm\JsonApi\Model\Response\DocumentResponse;
use Enm\JsonApi\Model\Response\ResponseInterface;
use Enm\JsonApi\Serializer\Deserializer;
use Enm\JsonApi\Serializer\DocumentDeserializerInterface;
use Enm\JsonApi\Serializer\DocumentSerializerInterface;
use Enm\JsonApi\Serializer\Serializer;
use Enm\JsonApi\Server\RequestHandler\RequestHandlerInterface;
use Exception;
use Throwable;

/**
 * @author Philipp Marien <marien@eosnewmedia.de>
 */
class JsonApiServer
{
    use JsonApiTrait;

    protected DocumentDeserializerInterface $deserializer;
    protected DocumentSerializerInterface $serializer;

    /** @var RequestHandlerInterface[] */
    protected array $handlers = [];

    public function __construct(
        ?DocumentDeserializerInterface $deserializer = null,
        ?DocumentSerializerInterface $serializer = null
    ) {
        $this->deserializer = $deserializer ?? new Deserializer();
        $this->serializer = $serializer ?? new Serializer();
    }

    public function createRequestBody(?string $requestBody): ?DocumentInterface
    {
        return (string)$requestBody !== '' ?
            $this->deserializer->deserializeDocument(json_decode($requestBody, true)) : null;
    }

    public function addHandler(string $type, RequestHandlerInterface $handler): self
    {
        $this->handlers[$type] = $handler;
        return $this;
    }

    /**
     * @param RequestInterface $request
     * @return ResponseInterface
     * @throws BadRequestException
     * @throws UnsupportedTypeException
     * @throws UnsupportedMediaTypeException
     */
    public function handleRequest(RequestInterface $request): ResponseInterface
    {
        if ($request->headers()->getRequired('Content-Type') !== JsonApi::CONTENT_TYPE) {
            throw new UnsupportedMediaTypeException($request->headers()->getRequired('Content-Type'));
        }

        switch ($request->method()) {
            case 'GET':
                if ($request->id()) {
                    if ($request->relationship()) {
                        $response = $this->getHandler($request->type())->fetchRelationship($request);
                        break;
                    }
                    $response = $this->getHandler($request->type())->fetchResource($request);
                    break;
                }
                $response = $this->getHandler($request->type())->fetchResources($request);
                break;
            case 'POST':
                if ($request->relationship()) {
                    $response = $this->getHandler($request->type())->addRelatedResources($request);
                    break;
                }
                $response = $this->getHandler($request->type())->createResource($request);
                break;
            case 'PATCH':
                if ($request->relationship()) {
                    $response = $this->getHandler($request->type())->replaceRelatedResources($request);
                    break;
                }
                $response = $this->getHandler($request->type())->patchResource($request);
                break;
            case 'DELETE':
                if ($request->relationship()) {
                    $response = $this->getHandler($request->type())->removeRelatedResources($request);
                    break;
                }
                $response = $this->getHandler($request->type())->deleteResource($request);
                break;
            default:
                throw new BadRequestException('Something was wrong...');
        }

        $document = $response->document();
        if ($document) {
            foreach ($document->data()->all() as $resource) {
                $this->includeRelated($document, $resource, $request);
                $this->cleanUpResource($resource, $request);
            }

        }

        return $response;
    }

    /**
     * @param ResponseInterface $response
     * @return string
     * @throws Exception
     */
    public function createResponseBody(ResponseInterface $response): string
    {
        return $response->document() ? json_encode($this->serializer->serializeDocument($response->document())) : '';
    }

    public function handleException(Throwable $throwable, bool $debug = false): ResponseInterface
    {
        $apiError = Error::createFrom($throwable, $debug);

        $document = $this->singleResourceDocument();
        $document->errors()->add($apiError);


        return new DocumentResponse($document, null, $apiError->status());
    }

    /**
     * @param string $type
     * @return RequestHandlerInterface
     * @throws UnsupportedTypeException
     */
    private function getHandler(string $type): RequestHandlerInterface
    {
        if (!array_key_exists($type, $this->handlers)) {
            throw new UnsupportedTypeException($type);
        }

        return $this->handlers[$type];
    }

    protected function includeRelated(
        DocumentInterface $document,
        ResourceInterface $resource,
        RequestInterface $request
    ): void {
        foreach ($resource->relationships()->all() as $relationship) {
            $shouldIncludeRelationship = $request->requestsInclude($relationship->name());
            $subRequest = $request->createSubRequest($relationship->name(), $resource);
            foreach ($relationship->related()->all() as $related) {
                if ($shouldIncludeRelationship) {
                    $document->included()->merge($related);
                    $this->cleanUpResource($document->included()->get($related->type(), $related->id()), $subRequest);
                }
                $this->includeRelated($document, $related, $subRequest);
            }
        }
    }

    protected function cleanUpResource(ResourceInterface $resource, RequestInterface $request): void
    {
        foreach ($resource->attributes()->all() as $key => $value) {
            if (!$request->requestsAttributes() || !$request->requestsField($resource->type(), $key)) {
                $resource->attributes()->remove($key);
            }
        }

        if (!$request->requestsRelationships()) {
            foreach ($resource->relationships()->all() as $relationship) {
                $resource->relationships()->removeElement($relationship);
            }
        }
    }
}
