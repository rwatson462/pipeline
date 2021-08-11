<?php

include dirname(__DIR__) . '/Pipeline/Http/Response.php';
$response = new Pipeline\Http\Response;

$response->contentType('json');
$response->send([
   'name' => 'Pipeline',
   'purpose' => 'Provide an automated system for GIT deployments'
]);
