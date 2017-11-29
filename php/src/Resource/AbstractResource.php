<?php
namespace JAPOS\Resource;

use Slim\Container;

abstract class AbstractResource {
    
    protected $entityManager = null;

    public function __construct(Container $c) {
        $this->entityManager = $c->get('db');
    }
}