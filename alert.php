<?php
require_once 'Gateway.php';

Gateway::$registerAddress = '127.0.0.1:2004';

if($_GET["ps"] == 'hntl'){
	Gateway::sendToAll('{"msg_type":"alert","msg_code":0,"msg":"'.$_GET["c"].'"}');
}

