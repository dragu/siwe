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

    public static function fromArray(array $data): self
    {
        return new self(
            address: $data["address"],
            chainId: $data["chainId"],
            domain: $data["domain"],
            statement: $data["statement"] ?? null,
            expirationTime: $data["expirationTime"] ?? null,
            issuedAt: $data["issuedAt"] ?? null,
            notBefore: $data["notBefore"] ?? null,
            requestId: $data["requestId"] ?? null,
            scheme: $data["scheme"] ?? null,
            nonce: $data["nonce"] ?? null,
            uri: $data["uri"] ?? null,
            version: $data["version"] ?? null,
            resources: $data["resources"] ?? null
        );
    }

    private function validate(): void
    {
        SiweMessageFieldValidator::validateOrFail($this);
    }
}