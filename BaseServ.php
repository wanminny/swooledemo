<?php

/**
 *  手动投标 server
 *
*/

namespace Deamon;

//use Deamon\BasePacket;
include_once("BasePacket.php");

class BaseServ
{
    protected $_serv = null;

    public function __construct($host = '127.0.0.1',$port = 8000)
    {
        $this->_serv = new \swoole_server($host, $port,SWOOLE_BASE,SWOOLE_SOCK_TCP);

        $this->_serv->set(array(
            'worker_num'       => 2,
            'task_worker_num'  => 4,
            'task_max_request' => 50000,
            'max_request'      => 50000,
            'log_file'         => '/tmp/swoole_wsd.log',
            'dispatch_mode'    => 2
        ));

        $this->_serv->on('Start', array($this, 'onStart'));
        $this->_serv->on('ManagerStart', array($this, 'onManagerStart'));
        $this->_serv->on('ManagerStop', array($this, 'onManagerStop'));

        $this->_serv->on('WorkerStart', array($this, 'onWorkerStart'));
        $this->_serv->on('Receive', array($this, 'onReceive'));
        // Task 回调的2个必须函数
        $this->_serv->on('Task', array($this, 'onTask'));
        $this->_serv->on('Finish', array($this, 'onFinish'));


        $this->_serv->on('close', array($this, 'onClose'));

        $this->_serv->start();
    }

    public function onStart($serv)
    {
//        swoole_set_process_name("wsd_master");
        echo "server: onStart \n";
    }


    public function onManagerStart($serv)
    {
        echo "server: onManagerStart \n";
//        \swoole_set_process_name("wsd-manager");
    }


    public function onManagerStop($serv)
    {
        echo "server: managerStop \n";
    }

    public function onWorkerStart($serv,$worker_id)
    {
        echo "sever :onWorkerStart  \n";
//        var_dump($serv);
        $istask = $serv->taskworker;
        if (!$istask) {
//            swoole_set_process_name("dora: worker {$worker_id}");
        } else {
//            swoole_set_process_name("dora: task {$worker_id}");
        }
    }

    public function onReceive(\swoole_server $serv, $fd, $from_id, $data)
    {
        echo "sever :onReceive \n";
//        var_dump($data);
        $data_tmp = [];
        $data_tmp['data'] = ($data);
//        $data_tmp['data'] = \Deamon\BasePacket::packEncode($data);
        $data_tmp['fd'] = $fd;
        $task_id = $serv->task($data_tmp);
        echo "Dispath AsyncTask: id=$task_id\n";
//        var_dump($serv, $fd, $from_id, $data);
//        $serv->send($fd,$data);

    }


    public function onTask($serv, $task_id, $from_id, $data)
    {
        echo "sever :onTask. \n";
        var_dump($data);
        echo "New AsyncTask[id=$task_id]".PHP_EOL;
        $serv->send($data['fd'],$data['data']);
        //返回任务执行的结果
        $serv->finish("data -> OK");
//        var_dump($serv, $task_id, $from_id, $data);
//        $serv->send();
    }

    public function onFinish($serv,$task_id, $data)
    {
        echo "sever :onFinish entry! ";
        echo "AsyncTask[$task_id] Finish: $data".PHP_EOL;
    }

    public function onClose($serv, $fd)
    {
        echo "client {$fd} closed\n";
    }


}

$serv = new BaseServ();

