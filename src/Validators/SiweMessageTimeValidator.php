<?php
declare(strict_types=1);

namespace Zbkm\Siwe\Validators;

use Zbkm\Siwe\Exception\SiweTimeException;
use Zbkm\Siwe\SiweMessageParams;

class SiweMessageTimeValidator
{
    /**
     * Validate times field in SiweMessageParams or except
     *
     * @param SiweMessageParams $params
     * @return bool
     */
    public static function validateOrFail(SiweMessageParams $params): bool
    {
        if ($params->expirationTime && !self::expirationTimeValidate($params->expirationTime)) {
            throw new SiweTimeException("The message has expired (now > expirationTime).");
        }

        if ($params->notBefore && !self::notBeforeValidate($params->notBefore)) {
            throw new SiweTimeException("The message is not valid yet (notBefore > now).");
        }

        return true;
    }

    public static function notBeforeValidate(string $expirationTime): bool
    {
        return new \DateTime() > new \DateTime($expirationTime);
    }

    public static function expirationTimeValidate(string $expirationTime): bool
    {
        return new \DateTime($expirationTime) > new \DateTime();
    }
}