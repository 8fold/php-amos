<?php
declare(strict_types=1); // We don't want PHP to do automatic type juggling.

/**
 * This is our local environment, therefore, we want errors to be displayed.
 */
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

/**
 * We use a a flat-file system for content and metadata. Sometimes memory
 * constraints become problematic, therefore, we will increase the size
 * available.
 */
ini_set('realpath_cache_size', '4096');
ini_set('realpath_cache_ttl', '600');

/**
 * We use third-party packages and PSR4 autoloading. Requiring this file gives
 * us access to the third-party packages and classes.
 */
require __DIR__ . '/../../vendor/autoload.php';

/**
 * Emits the Response to the server running our App to then be sent to the
 * Client requesting a Response.
 */
use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;

/**
 * A data object for the domain and content root.
 */
use Eightfold\Amos\Site;
use Eightfold\Amos\Content;

/**
 * The default request generator we use.
 */
use Nyholm\Psr7Server\ServerRequestCreator;
use Nyholm\Psr7\Factory\Psr17Factory;

$psr17Factory = new Psr17Factory();

$request = (new ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
))->fromGlobals();

(new SapiEmitter())->emit(
    Site::init(
        withDomain: 'http://com.php-amos:8889',
        contentIn: Content::init(__DIR__ . '/../../content-example')
    )->response(for: $request)
);
exit();



use Eightfold\Amos\Environment;

use Eightfold\Amos\Documents\Page;

$environment = Environment:: init(
    site: 'root',
    domain: 'http://com.php-amos',
    contentRoot: __DIR__ . '/../../content-example'
);

$path = $request->getUri()->getPath();

if ($environment->site(named: 'root')->hasContent(for: $path) === false) {
    die('404');
}

(new SapiEmitter())->emit(
    (new Response(
        status: 200,
        headers: ['content-type' => 'text/html'],
        body: Stream::create(
            Page::create()->build()
        )
    ))
);
