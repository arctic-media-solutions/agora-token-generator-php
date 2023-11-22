<?php

namespace App\Helpers\Agora;

class AgoraServiceEducation extends AgoraService
{
    const SERVICE_TYPE = 7;
    const PRIVILEGE_ROOM_USER = 1;
    const PRIVILEGE_USER = 2;
    const PRIVILEGE_APP = 3;

    public $roomUuid;
    public $userUuid;
    public $role;

    public function __construct($roomUuid = "", $userUuid = "", $role = -1)
    {
        parent::__construct(self::SERVICE_TYPE);
        $this->roomUuid = $roomUuid;
        $this->userUuid = $userUuid;
        $this->role = $role;
    }

    public function pack()
    {
        return AgoraUtil::packUint16($this->type) . AgoraUtil::packMapUint32($this->privileges)
            . AgoraUtil::packString($this->roomUuid) . AgoraUtil::packString($this->userUuid) . AgoraUtil::packInt16($this->role);
    }

    public function unpack(&$data)
    {
        $this->privileges = AgoraUtil::unpackMapUint32($data);
        $this->roomUuid = AgoraUtil::unpackString($data);
        $this->userUuid = AgoraUtil::unpackString($data);
        $this->role = AgoraUtil::unpackInt16($data);
    }
}
