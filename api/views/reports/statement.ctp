<?php
App::import('Vendor','statement/account');

$AS= new AccountStatement($statement);
$AS->headerInfo();
//$AS->paysched('current');
$AS->paysched('old');
$AS->ledger();
$AS->output();