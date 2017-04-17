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
* 用于检测业务代码死循环或者长时间阻塞等问题
* 如果发现业务卡死，可以将下面declare打开（去掉//注释），并执行php start.php reload
* 然后观察一段时间workerman.log看是否有process_timeout异常
*/
//declare(ticks=1);

use \GatewayWorker\Lib\Gateway;
use \Workerman\Worker;
use \Workerman\Lib\Timer;
use \Workerman\Protocols\Websocket;

/**
* 主逻辑
* 主要是处理 onConnect onMessage onClose 三个方法
* onConnect 和 onClose 如果不需要可以不用实现并删除
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
    //初始化一个定时器
  Timer::add(10,function(){
  });

}

  /**
   * 当客户端连接时触发
   * 如果业务不需此回调可以删除onConnect
   * 
   * @param int $client_id 连接id
   */
  public static function onConnect($client_id)
  {

   echo $_SERVER['GATEWAY_PORT'];
   echo "\r\n";
 //  echo $client_id;

  //判断当前客户端是否连的是WebSocket端口
  //如果是前端端口则将该clientid存入WEBID中
   if ( $_SERVER['GATEWAY_PORT']=="8099") {
  //  echo $client_id;
    Events::$webid[]=$client_id;
  }


      // 向当前client_id发送数据 
 //   Gateway::sendToClient($client_id, "Hello $client_id\r\n");
      // 向所有人发送
  //  Gateway::sendToAll("$client_id login\r\n");

}

 /**
  * 当客户端发来消息时触发
  * 接收到GPS发来的消息后，解析信息
  * @param int $client_id 连接id
  * @param mixed $message 具体消息
  */
 public static function onMessage($client_id, $message)
 {

    //如果当前监听的端口为8282，表示该信息由GPS终端发来
   if ( $_SERVER['GATEWAY_PORT']=="8282") {

  //解析$message
  //function doSomething();

  //将坐标信息发向前端界面
    foreach (Events::$webid as $key => $value) {
    //echo "值'{$value}'的键是$key<br>";
      Gateway::sendToClient($value,$message);
    }

  }else{
    //处理前端发来的命令
    //
    //  Gateway::sendToClient($client_id,$message);
  }

      // 向所有人发送 
      // Gateway::sendToAll("收到了 $client_id 发来的消息 $message\r\n");

  if ($message=="q") {
    Gateway::closeClient($client_id);
  }
  echo $message;
  echo "\r\n";

     // $ws::onConnect($connection,$message);
}

 /**
  * 当用户断开连接时触发
  * @param int $client_id 连接id
  */
 public static function onClose($client_id)
 {
     // 向所有人发送 
  // GateWay::sendToAll("$client_id logout\r\n");
 }

}
