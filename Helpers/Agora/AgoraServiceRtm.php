<?php

namespace App\Helpers\Agora;

class AgoraServiceRtm extends AgoraService
{
    const SERVICE_TYPE = 2;
    const PRIVILEGE_LOGIN = 1;
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
