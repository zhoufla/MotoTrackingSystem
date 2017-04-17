<?php
/**
* This file is part of workerman.
*
* Licensed under The MIT License
* For full copyright and license information, please see the MIT-LICENSE.txt
* Redistributions of files must retain the above copyright notice.
*
* @author walkor<walkor@workerman.net>
* @copyright walkor<walkor@workerman.net>
* @link http://www.workerman.net/
* @license http://www.opensource.org/licenses/mit-license.php MIT License
*/

/**
* ���ڼ��ҵ�������ѭ�����߳�ʱ������������
* �������ҵ���������Խ�����declare�򿪣�ȥ��//ע�ͣ�����ִ��php start.php reload
* Ȼ��۲�һ��ʱ��workerman.log���Ƿ���process_timeout�쳣
*/
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use \Workerman\Worker;
use \Workerman\Lib\Timer;
use \Workerman\Protocols\Websocket;

/**
* ���߼�
* ��Ҫ�Ǵ��� onConnect onMessage onClose ��������
* onConnect �� onClose �������Ҫ���Բ���ʵ�ֲ�ɾ��
*/
class Events
{

/**
 * [onWorkerStart description]
 * @param  [type] $worker [description]
 * @return [type]         [description]
 */

public static $webid = array();

public static function onWorkerStart($worker){
    //��ʼ��һ����ʱ��
  Timer::add(10,function(){
  });

}

  /**
   * ���ͻ�������ʱ����
   * ���ҵ����˻ص�����ɾ��onConnect
   * 
   * @param int $client_id ����id
   */
  public static function onConnect($client_id)
  {

   echo $_SERVER['GATEWAY_PORT'];
   echo "\r\n";
 //  echo $client_id;

  //�жϵ�ǰ�ͻ����Ƿ�������WebSocket�˿�
  //�����ǰ�˶˿��򽫸�clientid����WEBID��
   if ( $_SERVER['GATEWAY_PORT']=="8099") {
  //  echo $client_id;
    Events::$webid[]=$client_id;
  }


      // ��ǰclient_id�������� 
 //   Gateway::sendToClient($client_id, "Hello $client_id\r\n");
      // �������˷���
  //  Gateway::sendToAll("$client_id login\r\n");

}

 /**
  * ���ͻ��˷�����Ϣʱ����
  * ���յ�GPS��������Ϣ�󣬽�����Ϣ
  * @param int $client_id ����id
  * @param mixed $message ������Ϣ
  */
 public static function onMessage($client_id, $message)
 {

    //�����ǰ�����Ķ˿�Ϊ8282����ʾ����Ϣ��GPS�ն˷���
   if ( $_SERVER['GATEWAY_PORT']=="8282") {

  //����$message
  //function doSomething();

  //��������Ϣ����ǰ�˽���
    foreach (Events::$webid as $key => $value) {
    //echo "ֵ'{$value}'�ļ���$key<br>";
      Gateway::sendToClient($value,$message);
    }

  }else{
    //����ǰ�˷���������
    //
    //  Gateway::sendToClient($client_id,$message);
  }

      // �������˷��� 
      // Gateway::sendToAll("�յ��� $client_id ��������Ϣ $message\r\n");

  if ($message=="q") {
    Gateway::closeClient($client_id);
  }
  echo $message;
  echo "\r\n";

     // $ws::onConnect($connection,$message);
}

 /**
  * ���û��Ͽ�����ʱ����
  * @param int $client_id ����id
  */
 public static function onClose($client_id)
 {
     // �������˷��� 
  // GateWay::sendToAll("$client_id logout\r\n");
 }

}
