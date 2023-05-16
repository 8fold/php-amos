<?php
declare(strict_types=1);

ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

ini_set('realpath_cache_size', '4096');
ini_set('realpath_cache_ttl', '600');

require_once(__DIR__ . '/../vendor/autoload.php');

$psr17Factory = new Nyholm\Psr7\Factory\Psr17Factory();

$request = (new Nyholm\Psr7Server\ServerRequestCreator(
    $psr17Factory, // ServerRequestFactory
    $psr17Factory, // UriFactory
    $psr17Factory, // UploadedFileFactory
    $psr17Factory  // StreamFactory
))->fromGlobals();

$site = Eightfold\Amos\Site::init(
    Eightfold\Amos\FileSystem\Directories\Root::fromString(__DIR__ . '/../docs'),
    $request
);

if ($site->contentRoot()->notFound() or $site->publicRoot()->notFound()) {
    die('return 500 response');
}

$request_path = $site->requestPath();
if (str_ends_with($request_path, 'sitemap.xml')) {
    die('sitemap');
}

die('here');
$request = Eightfold\Amos\Http\ServerRequestGet::usingDefault();

$uri = Eightfold\Amos\Http\Uri::fromPsr7($request);

$requestedPath = Eightfold\Amos\Http\UriPath::fromUri($uri);

die(var_dump(
    (string) $requestedPath
));
//
// $requestedPublicDirectory = $publicRoot . $requestedPath;
//
// $metadataFilename = 'meta.json';
//
// $metadataPath = $requestedPublicDirectory . $metadataFilename;
// if (file_exists($metadataPath) === false) {
//     die('return 404 response: no meta file');
// }
//
// $metadataContent = file_get_contents($metadataPath);
// if ($metadataContent === false) {
//     die('return 404 response: no meta content');
// }
//
// $metadata = json_decode($metadataContent);
// if (
//     is_object($metadata) === false or
//     is_a($metadata, \StdClass::class) === false
// ) {
//     die('return 404 response: no json');
// }
//
// $contentFilename = 'content.md';
//
// $contentPath = $requestedPublicDirectory . $contentFilename;
// if (file_exists($contentPath) === false) {
//     die('return 404 response: content file not found');
// }
//
// $markdown = file_get_contents($contentPath);
// if ($markdown === false) {
//     die('return 404 response: no content content');
// }
//
// $markdownConverter = Eightfold\Markdown\Markdown::create();
//
// $html = $markdownConverter->convert($markdown);
//
// $stream = Nyholm\Psr7\Stream::create($html);
//
// $response = new Nyholm\Psr7\Response(
//     body: $stream
// );
//
// $emitter = new Laminas\HttpHandlerRunner\Emitter\SapiEmitter();
// $emitter->emit($response);
// exit();
