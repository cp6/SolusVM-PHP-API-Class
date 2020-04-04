<?php
require_once('class.php');

$call = new solusClientApi('https://hostsurl/api/client/command.php','API-KEY','API-HASH');

echo json_encode($call->allInfo());
