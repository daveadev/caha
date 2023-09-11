<?php
App::import('Vendor','receipts/payment_plan');
$ppr= new PaymentPlanReceipt($details);
$ppr->agreement();
$ppr->output();
