<?php
namespace JAPOS\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

use JAPOS\Resource\JamaahResource;

/**
 * Class JamaahController
 * @package JAPOS\Controller
 */
class JamaahController extends AbstractApiController {

    // Entity Manager
    // private $em;

    private $jamaahResource;

    public function __construct(JamaahResource $jamaahResource) {
        $this->jamaahResource = $jamaahResource;
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param $args
     * @return Response
     */
    public function fetch(Request $request, Response $response, $args) {
        $data = $this->jamaahResource->get();
        return $response->withJSON($data);
    }

    public function fetchOne($request, $response, $args) {
        $data = $this->jamaahResource->get($args['id']);
        if ($data) {
            return $response->withJSON($data);
        }
        return $response->withStatus(404, 'Tidak ditemukan data dengan spesifikas ID yang diberikan.');
    }
}

