<?php

namespace App\Services;

use App\Helpers\Agora\AgoraAccessToken;
use App\Helpers\Agora\AgoraServiceChat;
use App\Helpers\Agora\AgoraServiceEducation;
use App\Helpers\Agora\AgoraServiceFpa;
use App\Helpers\Agora\AgoraServiceRtc;
use App\Helpers\Agora\AgoraServiceRtm;
use Illuminate\Support\Facades\Config;

class AgoraTokenService
{
    /**
     * Build the RTM token.
     *
     * @param string $userId
     * @param int $expire
     * @return string
     */
    public static function buildRtmToken(string $userId, int $expire): string
    {
        $appId = Config::get('agora.app_id');
        $appCertificate = Config::get('agora.app_certificate');

        $accessToken = new AgoraAccessToken($appId, $appCertificate, $expire);
        $serviceRtm = new AgoraServiceRtm($userId);
        $serviceRtm->addPrivilege(AgoraServiceRtm::PRIVILEGE_LOGIN, $expire);
        $accessToken->addService($serviceRtm);

        return $accessToken->build();
    }

    /**
     * Build the RTC token.
     *
     * @param string $channelName
     * @param string $uid
     * @param int $expire
     * @return string
     */
    public static function buildRtcToken(string $channelName, string $uid, int $expire): string
    {
        $appId = Config::get('agora.app_id');
        $appCertificate = Config::get('agora.app_certificate');

        $accessToken = new AgoraAccessToken($appId, $appCertificate, $expire);
        $serviceRtc = new AgoraServiceRtc($channelName, $uid);

        $serviceRtc->addPrivilege(AgoraServiceRtc::PRIVILEGE_JOIN_CHANNEL, $expire);
        $serviceRtc->addPrivilege(AgoraServiceRtc::PRIVILEGE_PUBLISH_AUDIO_STREAM, $expire);
        $serviceRtc->addPrivilege(AgoraServiceRtc::PRIVILEGE_PUBLISH_VIDEO_STREAM, $expire);
        $serviceRtc->addPrivilege(AgoraServiceRtc::PRIVILEGE_PUBLISH_DATA_STREAM, $expire);

        $accessToken->addService($serviceRtc);

        return $accessToken->build();
    }

    /**
     * Build the FPA token.
     *
     * @param int $expire
     * @return string
     */
    public static function buildFpaToken(int $expire): string
    {
        $appId = Config::get('agora.app_id');
        $appCertificate = Config::get('agora.app_certificate');

        $accessToken = new AgoraAccessToken($appId, $appCertificate, $expire);
        $serviceFpa = new AgoraServiceFpa();

        $serviceFpa->addPrivilege($serviceFpa::PRIVILEGE_LOGIN, $expire);
        $accessToken->addService($serviceFpa);

        return $accessToken->build();
    }


    /**
     * Build the Chat token.
     *
     * @param string $userId
     * @param int $expire
     * @return string
     */
    public static function buildChatToken(string $userId, int $expire): string
    {
        $appId = Config::get('agora.app_id');
        $appCertificate = Config::get('agora.app_certificate');

        $accessToken = new AgoraAccessToken($appId, $appCertificate, $expire);
        $serviceChat = new AgoraServiceChat($userId);

        $serviceChat->addPrivilege($serviceChat::PRIVILEGE_USER, $expire);
        $accessToken->addService($serviceChat);

        return $accessToken->build();
    }


    /**
     * Build the Education token.
     *
     * @param string $roomUuid
     * @param string $userUuid
     * @param int $role
     * @param int $expire
     * @return string
     */
    public static function buildEducationToken(string $roomUuid, string $userUuid, int $role, int $expire): string
    {
        $appId = Config::get('agora.app_id');
        $appCertificate = Config::get('agora.app_certificate');

        $accessToken = new AgoraAccessToken($appId, $appCertificate, $expire);
        $serviceEducation = new AgoraServiceEducation($roomUuid, $userUuid, $role);

        $accessToken->addService($serviceEducation);

        return $accessToken->build();
    }
}
