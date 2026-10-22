<?php
declare(strict_types=1);
require __DIR__.'/app/bootstrap.php';
if (!$config['installed']) redirect(base_url('setup.php'));
try {
    $user=Auth::user(); if (!$user) redirect(base_url('login.php'));
    $page=(string)($_GET['page'] ?? 'dashboard');
    // Existing account-management links open the combined management page.
    if ($page==='staff') { $page='branches'; $_GET['page']=$page; }
    $pageTitles=['dashboard'=>'Station overview','pos'=>'POS Cashier','orders'=>'Sales & orders','deliveries'=>'Deliveries',
        'customers'=>'Customers','ledger'=>'Gallon ledger & tracking','payments'=>'Payments',
        'expenses'=>'Expenses','products'=>'Products & pricing',
        'branches'=>'Branches & accounts','reports'=>'Reports & exports','audit'=>'Audit trail','settings'=>'Station settings','profile'=>'Your profile'];
    if (empty($config['product_management_enabled'])) unset($pageTitles['products']);
    if (!isset($pageTitles[$page])) throw new AppError('Page not found.',404);
    if ($user['role']==='OWNER' && $user['must_reset'] && $page!=='profile') { flash('warning','Please replace your temporary password before starting your shift.'); redirect(url('profile')); }
    if (in_array($page,['expenses','products','branches','staff','reports','audit','settings'],true)) Auth::owner();
    if ($_SERVER['REQUEST_METHOD']==='POST') {
        $result=null;
        try {
            check_csrf(); $action=text_field($_POST,'action',60,true);
            if ($user['role']==='OWNER' && $user['must_reset'] && $action!=='profile') throw new AppError('Change your temporary password first.',403);
            if ($action==='switch_branch') {
                Auth::owner(); $id=whole($_POST['branch_id'] ?? 0,0,2);
                if ($id && !Database::one('SELECT id FROM branches WHERE id=?',[$id])) throw new AppError('Branch not found.');
                $_SESSION['branch']=$id ?: null;
            } else {
                $result=perform($action,$_POST);
                unset($_SESSION['old_form']);
                flash('success',match($action) { 'return'=>'Gallon return recorded. The customer gallon balance is updated.',
                    'expense'=>'Expense saved. Your financial reports are updated.','profile'=>$user['role']==='OWNER'?'Your password has been updated.':'Your profile has been updated.',
                    'delivery'=>($_POST['status'] ?? '')==='Delivered'?'Delivery confirmed. Actual returns are recorded; unreturned station gallons stay on the customer account.':'Delivery updated.',
                    'payment'=>'Payment recorded.','void_order'=>'Order cancelled. Customer gallon balances and payments have been reversed.',
                    default=>'Saved successfully.' });
            }
        } catch (AppError $e) { flash('danger',$e->getMessage()); $_SESSION['old_form']=array_diff_key($_POST,array_flip(['_csrf','password','password_confirmation','current_password','access_code'])); }
        catch (Throwable $e) { error_log((string)$e); flash('danger','This change could not be saved. Check for duplicate values and try again. No partial transaction was committed.'); }
        $back=(string)($_POST['back'] ?? $page);
        $back=isset($pageTitles[$back]) ? $back : $page;
        $deliveryId=filter_var($_POST['id'] ?? null,FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
        $destination=$back==='deliveries' && ($_POST['action'] ?? '')==='delivery' && $deliveryId ? ['id'=>$deliveryId] : [];
        if ($back==='customers' && ($_POST['action'] ?? '')==='customer') {
            $customerId=filter_var($result ?? ($_POST['id'] ?? null),FILTER_VALIDATE_INT,['options'=>['min_range'=>1]]);
            if ($customerId) $destination=['id'=>$customerId]+($result===null?['edit'=>1]:[]);
        }
        redirect(url($back,$destination));
    }
    require __DIR__.'/app/View.php';
    // Validate reporting dates before producing a partial response.
    Reports::period();
    ob_start(); require __DIR__.'/app/views/'.$page.'.php'; $content=ob_get_clean();
    require __DIR__.'/app/views/layout-top.php'; echo $content; require __DIR__.'/app/views/layout-bottom.php';
} catch (Throwable $e) {
    if (ob_get_level()) ob_end_clean();
    $code=$e instanceof AppError ? $e->httpStatus : 500;
    if (!$e instanceof AppError) error_log((string)$e);
    http_response_code($code);
    $message=$e instanceof AppError ? $e->getMessage() : 'The workspace could not load. Please check the server connection or contact the owner.';
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Aqua Rose</title><link rel="stylesheet" href="'.e(base_url('assets/css/app.css')).'"></head><body class="auth-body"><main class="setup-card"><span class="eyebrow">AQUA ROSE</span><h1>Let’s get you back.</h1><p>'.e($message).'</p><a class="btn primary" href="'.e(url('dashboard')).'">Back to workspace</a></main></body></html>';
}
