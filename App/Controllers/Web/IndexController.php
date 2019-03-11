<?php declare(strict_types = 1);

namespace App\Controllers\Web;

use App\Controllers\Controller;
use Slim\Http\{Request, Response};

/**
 * Class IndexController
 * @package App\Controllers\Web
 */
final class IndexController extends Controller
{
    /**
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     */
    public function getAction(Request $request, Response $response, array $params): Response
    {
        return $response->write(self::di()->view->render('Index'));
    }
}
