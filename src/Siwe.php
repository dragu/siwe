<?php

namespace Zbkm\Siwe;

use Random\RandomException;

class Siwe
{
    /**
     * @return string A randomly generated EIP-4361 nonce
     */
    static function generateNonce(): string
    {
        return bin2hex(random_bytes(16));
    }
}