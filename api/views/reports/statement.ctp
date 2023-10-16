<?php
App::import('Vendor','statement/account');

$AS= new AccountStatement($statement);
$AS->headerInfo();
$AS->paysched($type);
$AS->ledger($type);
$AS->payment_ins();
$AS->output();