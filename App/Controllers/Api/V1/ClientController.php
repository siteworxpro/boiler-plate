<?php declare(strict_types = 1);

namespace App\Controllers\Api\V1;

use App\Controllers\Controller;
use Slim\Http\{Request, Response};

/**
 * Class ClientController
 * @package App\Controllers\Api\V1
 */
final class ClientController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function getAction(Request $request, Response $response, array $params): Response
    {
        $this->setPayload(self::di()->client->toArray());

        return $this->formatResponse($response);
    }
}
