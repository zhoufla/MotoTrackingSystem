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
use \Workerman\Worker;
use \Workerman\WebServer;
use \GatewayWorker\Gateway;
use \GatewayWorker\BusinessWorker;
use \Workerman\Autoloader;
use \Workerman\Protocols\Websocket;

// 自动加载类
require_once __DIR__ . '/../../vendor/autoload.php';

// 
$gateway = new Gateway("websocket://0.0.0.0:8099");
// gateway名称，status方便查看
$gateway->name = 'MyWebSocketGateway';
// gateway进程数
//$gateway->count = 4;
// 本机ip，分布式部署时使用内网ip
//$gateway->lanIp = '127.0.0.1';
// 内部通讯起始端口，假如$gateway->count=4，起始端口为4000
// 则一般会使用4000 4001 4002 4003 4个端口作为内部通讯端口 
//$gateway->startPort = 2900;
//
// 服务注册地址
$gateway->registerAddress = '127.0.0.1:1238';


// 心跳间隔
//$gateway->pingInterval = 10;
// 心跳数据
//$gateway->pingData = '{"type":"ping"}';


// 当客户端连接上来时，设置连接的onWebSocketConnect，即在websocket握手时的回调
$gateway->onConnect = function($connection)
{
	//$connection->WebsocketType=Websocket::BINARY_TYPE_ARRAYBUFFER;

    $connection->onWebSocketConnect = function($connection , $http_header)
    {
         
    };
}; 


// 如果不是在根目录启动，则运行runAll方法
if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

