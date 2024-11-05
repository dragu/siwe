<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

use DateTime;

class SiweMessage
{
    /**
     * @param SiweMessageParams $params
     * @return string
     */
    public static function create(SiweMessageParams $params): string
    {
        $domain = $params->scheme ? "$params->scheme://$params->domain" : $params->domain;

        $message = "$domain wants you to sign in with your Ethereum account:\n$params->address\n\n";

        if ($params->statement) {
            $message .= "$params->statement\n";
        }

        $message .= "\nURI: " . $params->uri . "\n";
        $message .= "Version: " . $params->version . "\n";
        $message .= "Chain ID: " . $params->chainId . "\n";
        $message .= "Nonce: " . $params->nonce . "\n";
        $message .= "Issued At: " . self::formatTimestampToISO($params->issuedAt);

        if ($params->expirationTime) {
            $message .= "\nExpiration Time: " . self::formatTimestampToISO($params->expirationTime);
        }

        if ($params->notBefore) {
            $message .= "\nNot Before: " . self::formatTimestampToISO($params->notBefore);
        }

        if ($params->requestId) {
            $message .= "\nRequest ID: " . $params->requestId;
        }

        if ($params->resources) {
            $message .= "\nResources:";
            foreach ($params->resources as $resource) {
                $message .= "\n- $resource";
            }
        }

        return $message;
    }

    public static function parse(string $message): SiweMessageParams
    {
        // regex from https://github.com/wevm/viem/blob/main/src/utils/siwe/parseSiweMessage.ts
        $re = "/^(?:(?P<scheme>[a-zA-Z][a-zA-Z0-9+-.]*):\/\/)?(?P<domain>[a-zA-Z0-9+-.]*(?::[0-9]{1,5})?) (?:wants you to sign in with your Ethereum account:\n)(?P<address>0x[a-fA-F0-9]{40})\n\n(?:(?P<statement>.*)\n\n)?(?:URI: (?P<uri>.+))\n(?:Version: (?P<version>.+))\n(?:Chain ID: (?P<chainId>\d+))\n(?:Nonce: (?P<nonce>[a-zA-Z0-9]+))\n(?:Issued At: (?P<issuedAt>.+))(?:\nExpiration Time: (?P<expirationTime>.+))?(?:\nNot Before: (?P<notBefore>.+))?(?:\nRequest ID: (?P<requestId>.+))?/m";

        preg_match($re, $message, $params);
        $params["chainId"] = (int)$params["chainId"];
        $params = array_filter($params);

        if (array_key_exists("expirationTime", $params)) {
            $params["expirationTime"] = self::formatISOToTimestamp($params["expirationTime"]);
        }

        if (array_key_exists("issuedAt", $params)) {
            $params["issuedAt"] = self::formatISOToTimestamp($params["issuedAt"]);
        }

        if (array_key_exists("notBefore", $params)) {
            $params["notBefore"] = self::formatISOToTimestamp($params["notBefore"]);
        }

        $resources = explode("Resources:\n", $message);

        if (isset($resources[1])) {
            $params["resources"] = array_map(function ($r) {
                return substr($r, 2);
            }, explode("\n", $resources[1]));
        }

        return SiweMessageParams::fromArray($params);
    }

    private static function formatTimestampToISO(int $timestamp): string
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d\TH:i:s.v\Z');
    }

    private static function formatISOToTimestamp(string $iso): int
    {
        return strtotime($iso);
    }

}