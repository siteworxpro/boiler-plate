<?php declare(strict_types = 1);

namespace App\Controllers;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Interface ControllerInterface
 *
 * this is the main interface all controllers should implement
 *
 * @package App\Controllers
 */
interface ControllerInterface
{
    /**
     * the GET http verb, Used when you want to get data from the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function getAction(Request $request, Response $response, array $params): Response;

    /**
     * the POST http verb, Used when you want to create data in the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function postAction(Request $request, Response $response, array $params): Response;

    /**
     * the DELETE http verb, Used when you want to delete data in the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function deleteAction(Request $request, Response $response, array $params): Response;

    /**
     * the PUT http verb, Used when you want to update data in the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function putAction(Request $request, Response $response, array $params): Response;

    /**
     * the OPTIONS http verb, Used when you want to update data in the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function optionsAction(Request $request, Response $response, array $params): Response;

    /**
     * the PATCH http verb, Used when you want to perform a partial update of data in the app
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function patchAction(Request $request, Response $response, array $params): Response;

    /**
     * required fields for post request
     * @param Request $request
     * @return array
     */
    public static function postRequestSignature(Request $request): array;

    /**
     * signature for a put request
     * @param Request $request
     * @return array
     */
    public static function putRequestSignature(Request $request): array;

    /**
     * signature for a get request
     * @param Request $request
     * @return array
     */
    public static function getRequestSignature(Request $request): array;

    /**
     * signature for a delete request
     * @param Request $request
     * @return array
     */
    public static function deleteRequestSignature(Request $request): array;

    /**
     * signature for a options request
     * @param Request $request
     * @return array
     */
    public static function optionsRequestSignature(Request $request): array;

    /**
     * signature for a patch request
     * @param Request $request
     * @return array
     */
    public static function patchRequestSignature(Request $request): array;
}
