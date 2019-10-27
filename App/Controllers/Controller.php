<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Library\App;
use Slim\Exception\NotFoundException;
use Slim\Http\{Request, Response, StatusCode};

/**
 * Class Controller
 * @package App\Controllers
 */
abstract class Controller extends App implements ControllerInterface
{

    protected $code = StatusCode::HTTP_OK;

    private $jsonResponse = [
        'payload' => []
    ];

    private $errorMessage = false;

    private $errorFields = [];


    /**
     * @param array $payload
     * @return Controller
     */
    public function setPayload(array $payload): self
    {
        $this->jsonResponse['payload'] = $payload;

        return $this;
    }

    private function reset(): void
    {
        $this->code = StatusCode::HTTP_OK;
        $this->errorMessage = false;
        $this->errorFields = [];
        $this->jsonResponse = [
            'payload' => []
        ];
    }

    /**
     * @param string $errorMessage
     * @param int    $code the HTTP return code
     * @param array  $errorFields
     * @return self
     */
    public function setError(
        string $errorMessage,
        int $code = StatusCode::HTTP_BAD_REQUEST,
        array $errorFields = []
    ): self {
        $this->errorMessage = $errorMessage;
        $this->code = $code;
        $this->errorFields = $errorFields;

        return $this;
    }

    /**
     * @param Response|static $response
     * @return Response
     */
    public function formatResponse(Response $response): Response
    {

        $this->jsonResponse['time'] = time();

        if ($this->errorMessage === false) {
            $this->jsonResponse['status'] = 'ok';
        } else {
            $this->jsonResponse['status'] = 'error';
            $this->jsonResponse['errorMessage'] = $this->errorMessage;

            if (!empty($this->errorFields)) {
                $this->jsonResponse['errorFields'] = $this->errorFields;
            }
        }

        $sha256 = hash('sha256', json_encode($this->jsonResponse['payload'] ?? []));
        $this->jsonResponse['sha256'] = $sha256;

        $this->jsonResponse['version'] = App::di()->config->get('app_version', 'development');

        $response =  $response->withStatus($this->code)->withJson($this->jsonResponse);
        $this->reset();

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function getAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function postAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function deleteAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function putAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function patchAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @param string $action
     * @return Response
     * @throws NotFoundException
     */
    private function unimplementedAction(Request $request, Response $response, array $params, string $action): Response
    {
        App::di()
            ->log
            ->warning(static::class . ':' . $action . ' is declared as a route but has no controller');

        throw new NotFoundException($request, $response); // Should never get here
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws NotFoundException
     */
    public function optionsAction(Request $request, Response $response, array $params): Response
    {
        $this->unimplementedAction($request, $response, $params, __FUNCTION__);

        return $response;
    }

    /**
     * default if no fields are required
     *
     * @param Request $request
     * @return array
     */
    public static function postRequestSignature(Request $request): array
    {
        return [];
    }

    /**
     * default if no fields are required
     *
     * @param Request $request
     * @return array
     */
    public static function putRequestSignature(Request $request): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function getRequestSignature(Request $request): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function deleteRequestSignature(Request $request): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function optionsRequestSignature(Request $request): array
    {
        return [];
    }

    /**
     * @param Request $request
     * @return array
     */
    public static function patchRequestSignature(Request $request): array
    {
        return [];
    }
}
