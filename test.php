<?php

require_once 'Gateway.php';

/**
 *====��������Ǳ����====
 *������дRegister�����ip��ͨ��������GatewayWorker�ķ�����ip���Ͷ˿�
 *ע��Register����˿���start_register.php�п����ҵ���chatĬ����1236��
 *�������GatewayClient��Register������һ̨�������ϣ�ip��д127.0.0.1
 **/
Gateway::$registerAddress = '127.0.0.1:1006';


// �����ǵ���ʾ��ӿ���GatewayWorker�����Ľӿ�һ��
// ע����˲�֧��sendToCurrentClient��closeCurrentClient����
// �����֧��
/**
Gateway::sendToAll('{"type":"broadcast","content":"hello all"}');

Gateway::sendToClient($client_id,'{"type":"say","content":"hello"}');

Gateway::isOnline($client_id);

Gateway::bindUid($client_id, $uid);

Gateway::sendToUid($client_id, $uid);

	Gateway::getSession($client_id);
*/

echo "online:" . Gateway::getAllClientCount();
