<?php

namespace App\Helpers\Agora;

class AgoraAccessToken
{
    const VERSION = "007";
    const VERSION_LENGTH = 3;
    public $appCert;
    public $appId;
    public $expire;
    public $issueTs;
    public $salt;
    public $services = [];

    public function __construct($appId = "", $appCert = "", $expire = 900)
    {
        $this->appId = $appId;
        $this->appCert = $appCert;
        $this->expire = $expire;
        $this->issueTs = time();
        $this->salt = rand(1, 99999999);
    }

    public function addService($service)
    {
        $this->services[$service->getServiceType()] = $service;
    }

    public function build()
    {
        if (!self::isUUid($this->appId) || !self::isUUid($this->appCert)) {
            return "";
        }

        $signing = $this->getSign();
        $data = AgoraUtil::packString($this->appId) . AgoraUtil::packUint32($this->issueTs) . AgoraUtil::packUint32($this->expire)
            . AgoraUtil::packUint32($this->salt) . AgoraUtil::packUint16(count($this->services));

        ksort($this->services);
        foreach ($this->services as $key => $service) {
            $data .= $service->pack();
        }

        $signature = hash_hmac("sha256", $data, $signing, true);

        return self::getVersion() . base64_encode(zlib_encode(AgoraUtil::packString($signature) . $data, ZLIB_ENCODING_DEFLATE));
    }

    public function getSign()
    {
        $hh = hash_hmac("sha256", $this->appCert, AgoraUtil::packUint32($this->issueTs), true);
        return hash_hmac("sha256", $hh, AgoraUtil::packUint32($this->salt), true);
    }

    public static function getVersion()
    {
        return self::VERSION;
    }

    public static function isUUid($str)
    {
        if (strlen($str) != 32) {
            return false;
        }
        return ctype_xdigit($str);
    }

    public function parse($token)
    {
        if (substr($token, 0, self::VERSION_LENGTH) != self::getVersion()) {
            return false;
        }

        $data = zlib_decode(base64_decode(substr($token, self::VERSION_LENGTH)));
        $signature = AgoraUtil::unpackString($data);
        $this->appId = AgoraUtil::unpackString($data);
        $this->issueTs = AgoraUtil::unpackUint32($data);
        $this->expire = AgoraUtil::unpackUint32($data);
        $this->salt = AgoraUtil::unpackUint32($data);
        $serviceNum = AgoraUtil::unpackUint16($data);

        $servicesObj = [
            AgoraServiceRtc::SERVICE_TYPE => new AgoraServiceRtc(),
            AgoraServiceRtm::SERVICE_TYPE => new AgoraServiceRtm(),
            AgoraServiceFpa::SERVICE_TYPE => new AgoraServiceFpa(),
            AgoraServiceChat::SERVICE_TYPE => new AgoraServiceChat(),
            AgoraServiceEducation::SERVICE_TYPE => new AgoraServiceEducation(),
        ];
        for ($i = 0; $i < $serviceNum; $i++) {
            $serviceType = AgoraUtil::unpackUint16($data);
            $service = $servicesObj[$serviceType];
            if ($service == null) {
                return false;
            }
            $service->unpack($data);
            $this->services[$serviceType] = $service;
        }
        return true;
    }
}
