<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

class SiweMessageParams
{
    public function __construct(
        public string  $address,
        public int     $chainId,
        public string  $domain,
        public ?string $statement = null,
        public ?int    $expirationTime = null,
        public ?int    $issuedAt = null,
        public ?int    $notBefore = null,
        public ?string $requestId = null,
        public ?string $scheme = null,
        public ?string $nonce = null,
        public ?string $uri = null,
        public ?string $version = null,
        public ?array  $resources = null,
    )
    {
        if ($this->issuedAt === null) {
            $this->issuedAt = time();
        }

        $this->validate();
    }

    private function validate(): void
    {
        SiweMessageFieldValidator::validateOrFail($this);
    }
}