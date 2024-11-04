<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

class NonceManager
{
    /**
     * @return string A randomly generated EIP-4361 nonce
     */
    public static function generate(): string
    {
        return bin2hex(random_bytes(16));
    }
}