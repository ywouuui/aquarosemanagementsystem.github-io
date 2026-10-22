<?php
declare(strict_types=1);
// Development/QA router: php -S 127.0.0.1:8088 router.php
// Apache production hosting uses deployment/apache.conf, never this development server.
$path=rawurldecode(parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH) ?: '/');
if(str_contains($path,'..') || str_contains($path,"\0") || str_contains($path,'\\')) { http_response_code(403); exit; }
if(preg_match('#^/assets/(?:css/[a-z0-9.-]+\.css|js/[a-z0-9.-]+\.js|images/[a-z0-9.-]+\.svg|fonts/[a-z0-9.-]+\.woff2|vendor/[a-z0-9.-]+\.(?:css|js))$#D',$path) && is_file(__DIR__.$path)) return false;
$allowed=['/'=>'index.php','/index.php'=>'index.php','/login.php'=>'login.php','/logout.php'=>'logout.php','/setup.php'=>'setup.php','/api.php'=>'api.php','/receipt.php'=>'receipt.php','/export.php'=>'export.php','/download.php'=>'download.php'];
if(!isset($allowed[$path])) { http_response_code(403); exit('Access denied.'); }
require __DIR__.'/'.$allowed[$path];
