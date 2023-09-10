<?php
App::import('Vendor','receipts/payment_plan');
$ppr= new PaymentPlanReceipt();
$ppr->agreement();
$ppr->output();
