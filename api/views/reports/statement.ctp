<?php
App::import('Vendor','statement/account');

$AS= new AccountStatement($statement);
$AS->headerInfo();
$AS->paysched('current');
$AS->ledger('current');
$AS->payment_ins();
$AS->output();