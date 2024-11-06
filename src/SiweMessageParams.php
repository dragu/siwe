<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

use Zbkm\Siwe\Validators\SiweMessageFieldValidator;

class SiweMessageParams
{
    /**
     * @param string        $address        The Ethereum address performing the signing
     * @param int           $chainId        Chain ID (1 for Ethereum)
     * @param string        $domain         The domain that is requesting the signing
     * @param string        $uri            An RFC 3986 URI referring to the resource that is the subject of the signing (as in the subject of a claim)
     * @param int|null      $issuedAt       The time when the message was generated, typically the current time. Default: now
     * @param string|null   $nonce          A random string typically chosen by the relying party and used to prevent replay attacks, at least 8 alphanumeric characters. Default: random
     * @param string|null   $statement      A human-readable ASCII assertion that the user will sign which MUST NOT include '\n'
     * @param string|null   $version        The current version of the SIWE Message, which MUST be 1 for this specification
     * @param string|null   $scheme         The URI scheme of the origin of the request
     * @param int|null      $expirationTime The time when the signed authentication message is no longer valid
     * @param int|null      $notBefore      The time when the signed authentication message will become valid
     * @param string|null   $requestId      A system-specific identifier that MAY be used to uniquely refer to the sign-in request
     * @param string[]|null $resources      A list of information or references to information the user wishes to have resolved as part of authentication by the relying party
     * @throws \Random\RandomException
     */
    public function __construct(
        public string  $address,
        public int     $chainId,
        public string  $domain,
        public string  $uri,
        public ?int    $issuedAt = null,
        public ?string $nonce = null,
        public ?string $statement = null,
        public ?string $version = null,
        public ?string $scheme = null,
        public ?int    $expirationTime = null,
        public ?int    $notBefore = null,
        public ?string $requestId = null,
        public ?array  $resources = null,
    )
    {
        if ($this->issuedAt === null) {
            $this->issuedAt = time();
        }

        if ($this->nonce === null) {
            $this->nonce = NonceManager::generate();
        }

        $this->validate();
    }

    /**
     * Create Message Params from assoc array
     *
     * @param array{
     *      address: string,
     *      chainId: int,
     *      domain: string,
     *      uri: string,
     *      issuedAt?: int,
     *      nonce?: string,
     *      statement?: string,
     *      version?: string,
     *      scheme?: string,
     *      expirationTime?: int,
     *      notBefore?: int,
     *      requestId?: string,
     *      resources?: string[]
     *     } $data
     * @return SiweMessageParams
     */
    public static function fromArray(array $data): self
    {
        return new self(
            address: $data["address"],
            chainId: $data["chainId"],
            domain: $data["domain"],
            uri: $data["uri"],
            issuedAt: $data["issuedAt"] ?? null,
            nonce: $data["nonce"] ?? null,
            statement: $data["statement"] ?? null,
            version: $data["version"] ?? null,
            scheme: $data["scheme"] ?? null,
            expirationTime: $data["expirationTime"] ?? null,
            notBefore: $data["notBefore"] ?? null,
            requestId: $data["requestId"] ?? null,
            resources: $data["resources"] ?? null
        );
    }

    /**
     * Validate params. Runs once when an object is created.
     *
     * @return void
     */
    protected function validate(): void
    {
        SiweMessageFieldValidator::validateOrFail($this);
    }
}