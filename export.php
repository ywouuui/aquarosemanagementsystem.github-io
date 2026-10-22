<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
try {
    $user=Auth::user(); if (!$user) throw new AppError('Sign in before exporting reports.',401);
    Auth::owner();
    if ($user['role']==='OWNER' && $user['must_reset']) throw new AppError('Change your temporary password first.',403);
    if (($_GET['format'] ?? '')==='csv') throw new AppError('CSV exports are no longer available. Choose Excel or PDF.',404);
    $type=text_field($_GET,'type',40,true); $format=choice($_GET['format'] ?? '',['pdf','xlsx']);
    $report=Reports::result($type,true); $title=$report['title']; $rows=$report['rows']; $summary=$report['summary'];
    if($user['role']==='OWNER') audit('Report exported','reports',$type,Auth::branch(),null,['format'=>$format,'period'=>Reports::period()]);
    if($format==='xlsx') Reports::exportExcel($title,$rows,$summary);
    Reports::exportPdf($title,$rows,$summary);
} catch(AppError $e) { http_response_code($e->httpStatus); header('Content-Type: text/plain; charset=utf-8'); echo $e->getMessage(); }
catch(Throwable $e) { error_log((string)$e); http_response_code(500); echo 'The report could not be exported. Please contact the station owner.'; }
