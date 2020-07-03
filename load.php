<?php
require_once('init.php');
use Sahtepetrucci\PlayTheWeb\Handler;

$rest_json = file_get_contents("php://input");
$data = json_decode($rest_json, true);
$url = urldecode($data['url']);

$url = strtolower($url);
$url = str_replace("https://", "", $url);
$url = str_replace("http://", "", $url);
$url = rtrim(trim($url), "/");

$cached_file_name = str_replace("/", "-", $url) . "_" . date('m-Y') . ".html";
$cache_path = __DIR__ . "/cache/" . $cached_file_name;

//$content = file_get_contents(__DIR__ . '/../samples/facebook_sample.html');
$handler = new Handler();

try {
    if (file_exists($cache_path)) {
        $content = file_get_contents($cache_path);
    } else {
        if ($content = file_get_contents("https://" . $url)) {
            file_put_contents($cache_path, $content);
        }
    }

    if (!$content) {
        throw new Exception('Something went wrong. Try a different URL', 400);
    }
    
    echo json_encode($handler->run($content, $data['tone'], $data['mode']));
    
} catch(\Exception $e) {
    http_response_code($e->getCode());

    echo json_encode(array(
        'error' => array(
            'message' => $e->getMessage(),
            'code' => $e->getCode(),
        ),
    ));
}
?>