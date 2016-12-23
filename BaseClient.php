<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/12/23
 * Time: 上午10:24
 *  手动投标；
 */
namespace Deamon;
use Deamon\BasePacket;
use DoraRPC\Packet;
include_once("BasePacket.php");
class BaseClient
{

    private $client ;

    const PORT = 8000;
    const HOST = '127.0.0.1';


    public function __construct()
    {
        $this->client = new \swoole_client(SWOOLE_SOCK_TCP);
        $this->client->set(array(
            'open_length_check' => 1,
            'package_length_type' => 'N',
            'package_length_offset' => 0,
            'package_body_offset' => 4,
//            'package_max_length' => 1024 * 1024 * 2,
            'open_tcp_nodelay' => 1,
//            'socket_buffer_size' => 1024 * 1024 * 4,
        ));
        return $this->client;
    }

    public function connect($host = '127.0.0.1',$port = 8000)
    {
       return $this->client->connect($host, $port, 5);
    }

    public function send($msg)
    {
        $msg = BasePacket::packEncode($msg);
        $this->client->send($msg);
    }

    public function getData()
    {
        $data =  $this->client->recv();
        $data = BasePacket::packDecode($data);
        return $data;
    }

    public function close()
    {
        $this->client->close();
    }

}

$cli = new BaseClient();
$status = $cli->connect();
//var_dump($status);
if($status)
{
    $i = 0;
    while($i < 1){
        $cli->send("a");
        $data = $cli->getData();
        var_dump($data);
        $i++;
    }
}
$cli->close();

