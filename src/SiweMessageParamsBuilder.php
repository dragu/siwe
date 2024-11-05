<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

use Zbkm\Siwe\Exception\SiweInvalidMessageFieldException;

class SiweMessageParamsBuilder
{
    private string $address;
    private int $chainId;
    private string $domain;
    private ?string $statement = null;
    private ?int $expirationTime = null;
    private ?int $issuedAt = null;
    private ?int $notBefore = null;
    private ?string $requestId = null;
    private ?string $scheme = null;
    private ?string $nonce = null;
    private ?string $uri = null;
    private ?string $version = null;
    private ?array $resources = null;

    private function __construct()
    {
    }

    public static function create(): self
    {
        return new self();
    }

    public function withAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    public function withChainId(int $chainId): self
    {
        $this->chainId = $chainId;
        return $this;
    }

    public function withDomain(string $domain): self
    {
        $this->domain = $domain;
        return $this;
    }

    public function withStatement(string $statement): self
    {
        $this->statement = $statement;
        return $this;
    }

    public function withExpirationTime(int $expirationTime): self
    {
        $this->expirationTime = $expirationTime;
        return $this;
    }

    public function withIssuedAt(int $issuedAt): self
    {
        $this->issuedAt = $issuedAt;
        return $this;
    }

    public function withNotBefore(int $notBefore): self
    {
        $this->notBefore = $notBefore;
        return $this;
    }

    public function withRequestId(string $requestId): self
    {
        $this->requestId = $requestId;
        return $this;
    }

    public function withScheme(string $scheme): self
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function withNonce(string $nonce): self
    {
        $this->nonce = $nonce;
        return $this;
    }

    public function withUri(string $uri): self
    {
        $this->uri = $uri;
        return $this;
    }

    public function withVersion(string $version): self
    {
        $this->version = $version;
        return $this;
    }

    public function withResources(array $resources): self
    {
        $this->resources = $resources;
        return $this;
    }

    public function build(): SiweMessageParams
    {
        if (!isset($this->address, $this->chainId, $this->domain)) {
            throw new SiweInvalidMessageFieldException("", "", ["Required fields are not set"]);
        }

        return new SiweMessageParams(
            address: $this->address,
            chainId: $this->chainId,
            domain: $this->domain,
            statement: $this->statement,
            expirationTime: $this->expirationTime,
            issuedAt: $this->issuedAt,
            notBefore: $this->notBefore,
            requestId: $this->requestId,
            scheme: $this->scheme,
            nonce: $this->nonce,
            uri: $this->uri,
            version: $this->version,
            resources: $this->resources
        );
    }
}