<?php
/**
 * Created by PhpStorm.
 * User: wanmin
 * Date: 16/12/23
 * Time: ����11:09
 *  ���ñ��� ѡ�
 */
namespace Deamon;

class PacketConfig
{
    //a flag to sure check the crc32
    //�Ƿ�������ǩ��������˿ͻ��˶���Ҫ�򿪣��򿪺����ǿ����ȫ�����ή��һ������
    const SW_DATASIGEN_FLAG = false;

    //a flag to decide if compress the packet
    //�Ƿ������ѹ����Ŀǰ�����õ�����ѹ����zlib��gzencode��ѹ������4
    const SW_DATACOMPRESS_FLAG = false;

    //salt to mixed the crc result
    //���濪�ؿ��������ڼ��ܴ�����������뱣�ֿͻ��˺ͷ����һ��
    const SW_DATASIGEN_SALT = "wsdxy@@com=dsw";

}