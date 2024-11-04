<?php
use PHPUnit\Framework\TestCase;
use Zbkm\Siwe\Siwe;

class NonceTest extends TestCase
{
    public function testGenerateNonce(): void
    {
        $nonce = Siwe::generateNonce();
        $this->assertSame(32, strlen($nonce));

        $nonce2 = Siwe::generateNonce();
        $this->assertNotSame($nonce, $nonce2);
    }
}
