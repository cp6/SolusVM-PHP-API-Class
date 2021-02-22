<?php
require_once('class.php');

$call = new solusClientApi();

echo json_encode($call->allInfo());