<?php
App::import('Vendor','receipts/adjust_receipt');

$pr= new AdjustmentReceipt();
$pr->receipt(0);
$pr->data($data,0);
$pr->data($data,3.5);
$pr->output();