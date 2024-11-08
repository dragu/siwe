<?php
declare(strict_types=1);

namespace Zbkm\Siwe\Validators;

use Zbkm\Siwe\Exception\SiweInvalidMessageFieldException;
use Zbkm\Siwe\SiweMessageParams;

class SiweMessageTimeValidator
{
    public static function validate(SiweMessageParams $params): bool
    {
        try {
            self::validateOrFail($params);
        } catch (\Exception) {
            return false;
        }

        return true;
    }

    public static function validateOrFail(SiweMessageParams $params): bool
    {
        if ($params->expirationTime && !self::expirationTimeValidate($params->expirationTime)) {
            throw new SiweInvalidMessageFieldException("expirationTime", $params->expirationTime, [
                "The message has expired.",
            ]);
        }

        if ($params->notBefore && !self::notBeforeValidate($params->notBefore)) {
            throw new SiweInvalidMessageFieldException("notBefore", $params->notBefore, [
                "The message is not valid yet.",
            ]);
        }

        return true;
    }

    /**
     * Validate notBefore time
     *
     * @param int $notBefore
     * @return bool
     */
    public static function notBeforeValidate(int $notBefore): bool
    {
        return time() > $notBefore;
    }

    /**
     * Validate expiration time
     *
     * @param int $expirationTime
     * @return bool
     */
    public static function expirationTimeValidate(int $expirationTime): bool
    {
        return $expirationTime > time();
    }
}