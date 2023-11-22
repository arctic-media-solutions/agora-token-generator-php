<?php

namespace App\Helpers\Agora;

class AgoraServiceFpa extends AgoraService
{
    const SERVICE_TYPE = 4;
    const PRIVILEGE_LOGIN = 1;

    public function __construct()
    {
        parent::__construct(self::SERVICE_TYPE);
    }

    public function pack()
    {
        return AgoraUtil::packUint16($this->type) . AgoraUtil::packMapUint32($this->privileges);
    }

    public function unpack(&$data)
    {
        $this->privileges = AgoraUtil::unpackMapUint32($data);
    }
}
