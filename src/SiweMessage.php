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
    static public function create(SiweMessageParams $params): string
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

    static private function formatTimestampToISO(int $timestamp): string
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d\TH:i:s.v\Z');
    }

}