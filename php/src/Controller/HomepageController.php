<?php
namespace JAPOS\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class HomepageController
 * @package JAPOS\Controller
 */
class HomepageController extends AbstractController
{
    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function hp(Request $request, Response $response, $args)
    {
        $body = $this->view->fetch('website/pages/homepage.twig');
        return $response->write($body);
    }
}

