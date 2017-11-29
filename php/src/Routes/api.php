<?php
// $app->get('/api', 'HELLO\Controller\ApiController:apiExample')->setName('hello.api.apiExample');

// Version group
$app->group('/v1', function () use ($app) {
    $app->get('/jamaah', 'JAPOS\Controller\JamaahController:fetch')->setName('api.japos.jamaah.fetch');
    $app->get('/jamaah/{id}', 'JAPOS\Controller\JamaahController:fetchOne')->setName('api.japos.jamaah.fetchOne');
});