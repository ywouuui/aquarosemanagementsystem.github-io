<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
header('Content-Type: application/json; charset=utf-8');
try {
    if (!$config['installed']) throw new AppError('Complete station setup first.',503);
    $user=Auth::user(); if (!$user) throw new AppError('Your session expired. Please sign in again.',401);
    if ($user['role']==='OWNER' && $user['must_reset']) throw new AppError('Change your temporary password before using the station.',403);
    if ($_SERVER['REQUEST_METHOD']!=='POST') throw new AppError('Use POST for this endpoint.',405);
    $raw=json_decode(file_get_contents('php://input'),true);
    if (!is_array($raw)) throw new AppError('The request is not valid JSON.',400);
    check_csrf();
    $action=text_field($raw,'action',60,true); $result=perform($action,$raw);
    $response=match($action) {
        'customer_search'=>['ok'=>true,'customers'=>$result],
        'record_search'=>['ok'=>true,'results'=>$result],
        'product_catalog'=>['ok'=>true,'products'=>$result],
        default=>['ok'=>true,'id'=>$result,'receipt_url'=>$action==='sale' ? base_url('receipt.php?id='.(int)$result) : null],
    };
    if ($action==='customer') $response['customer']=Admin::customerSummary((int)$result);
    echo json_encode($response,JSON_UNESCAPED_UNICODE);
} catch (AppError $e) { http_response_code($e->httpStatus); echo json_encode(['ok'=>false,'message'=>$e->getMessage()]); }
catch (PDOException $e) {
    error_log((string)$e);
    http_response_code(409);
    echo json_encode(['ok'=>false,'message'=>$e->getCode()==='23000' ? 'This record was already submitted or conflicts with an existing record. Refresh before trying again.' : 'The transaction could not be saved. No partial changes were committed. Please retry.']);
} catch (Throwable $e) { error_log((string)$e); http_response_code(500); echo json_encode(['ok'=>false,'message'=>'The request could not be completed. Please contact the station owner.']); }
