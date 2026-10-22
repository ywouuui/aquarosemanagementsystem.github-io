<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
try {
    $user=Auth::user(); if(!$user) throw new AppError('Sign in to view a receipt.',401); Auth::owner();
    if($user['role']==='OWNER' && $user['must_reset']) throw new AppError('Change your temporary password first.',403);
    $expense=Auth::record('expenses',whole($_GET['id'] ?? 0,1));
    if(!$expense['receipt_path'] || !preg_match('/^[a-f0-9]{48}\.dat$/D',$expense['receipt_path'])) throw new AppError('Receipt not found.',404);
    $path=$GLOBALS['storage_path'].'/'.$expense['receipt_path']; if(!is_file($path)) throw new AppError('Receipt file is unavailable.',404);
    $extension=match($expense['receipt_mime']) { 'image/png'=>'png','image/jpeg'=>'jpg',default=>'pdf' };
    header('Content-Type: '.$expense['receipt_mime']); header('Content-Disposition: attachment; filename="expense-'.$expense['id'].'.'.$extension.'"');
    header('Content-Length: '.filesize($path)); readfile($path);
} catch(AppError $e) { http_response_code($e->httpStatus); echo e($e->getMessage()); }
catch(Throwable $e) { error_log((string)$e); http_response_code(500); echo 'Receipt could not be retrieved.'; }
