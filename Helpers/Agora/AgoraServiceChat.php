<?php

namespace App\Helpers\Agora;

class AgoraServiceChat extends AgoraService
{
    const SERVICE_TYPE = 5;
    const PRIVILEGE_USER = 1;
    const PRIVILEGE_APP = 2;
    public $userId;

    public function __construct($userId = "")
    {
        parent::__construct(self::SERVICE_TYPE);
        $this->userId = $userId;
    }

    public function pack()
    {
        return AgoraUtil::packUint16($this->type) . AgoraUtil::packMapUint32($this->privileges) . AgoraUtil::packString($this->userId);
    }

    public function unpack(&$data)
    {
        $this->privileges = AgoraUtil::unpackMapUint32($data);
        $this->userId = AgoraUtil::unpackString($data);
    }
}
