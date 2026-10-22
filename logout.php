<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
if ($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); header('Allow: POST'); exit; }
try { check_csrf(); $user=Auth::user(); if ($user) audit('Signed out','users',$user['id'],$user['branch_id']); Auth::clear(); redirect(base_url('login.php')); }
catch (AppError $e) { http_response_code($e->httpStatus); echo e($e->getMessage()); }
