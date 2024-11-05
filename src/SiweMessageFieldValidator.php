<?php
declare(strict_types=1);

namespace Zbkm\Siwe;

use Zbkm\Siwe\Ethereum\Address;
use Zbkm\Siwe\Exception\SiweInvalidMessageFieldException;

class SiweMessageFieldValidator
{
    const MIN_NONCE_LENGTH = 8;

    public static function validateOrFail(SiweMessageParams $params): bool
    {
        if (!self::addressValidate($params->address)) {
            throw new SiweInvalidMessageFieldException("address", $params->address, [
                "Address must be a hex value of 20 bytes (40 hex characters).",
                "Address must match its checksum counterpart."
            ]);
        }

        if (!self::chainIdValidate($params->chainId)) {
            throw new SiweInvalidMessageFieldException("chainId", $params->chainId, [
                "Chain ID must be a EIP-155 chain ID.",
                "See https://eips.ethereum.org/EIPS/eip-155"
            ]);
        }


        if (!self::domainValidate($params->domain)) {
            throw new SiweInvalidMessageFieldException("domain", $params->domain, [
                "Domain must be an RFC 3986 authority.",
                "See https://www.rfc-editor.org/rfc/rfc3986"
            ]);
        }

        if ($params->nonce && !self::nonceValidate($params->nonce)) {
            throw new SiweInvalidMessageFieldException("nonce", $params->nonce, [
                "Nonce must be at least 8 characters.",
                "Nonce must be alphanumeric."
            ]);
        }

        if ($params->uri && !self::uriValidate($params->uri)) {
            throw new SiweInvalidMessageFieldException("uri", $params->uri, [
                "URI must be a RFC 3986 URI referring to the resource that is the subject of the signing.",
                "See https://www.rfc-editor.org/rfc/rfc3986"
            ]);
        }

        if ($params->version && !self::versionValidate($params->version)) {
            throw new SiweInvalidMessageFieldException("version", $params->version, [
                "Version must be '1'."
            ]);
        }

        if ($params->scheme && !self::schemeValidate($params->scheme)) {
            throw new SiweInvalidMessageFieldException("scheme", $params->scheme, [
                "Scheme must be an RFC 3986 URI scheme.",
                "See https://www.rfc-editor.org/rfc/rfc3986#section-3.1"
            ]);
        }

        if ($params->statement && !self::statementValidate($params->statement)) {
            throw new SiweInvalidMessageFieldException("statement", $params->statement, [
                "Statement must not include '\\n'.",
            ]);
        }

        if ($params->resources) {
            foreach ($params->resources as $resource) {
                if (!self::resourceValidate($resource)) {
                    throw new SiweInvalidMessageFieldException("resources", $resource, [
                        "Every resource must be a RFC 3986 URI.",
                        "See https://www.rfc-editor.org/rfc/rfc3986"
                    ]);
                }
            }
        }

        return true;
    }

    public static function addressValidate(string $address): bool
    {
        return Address::isAddress($address);
    }

    public static function chainIdValidate(int $chainId): bool
    {
        return $chainId > 0 and gettype($chainId) === "integer";
    }

    public static function domainValidate(string $domain): bool
    {
        // parse url to handle port
        $domain = parse_url("http://$domain", PHP_URL_HOST);
        return (bool)filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME);
    }

    public static function nonceValidate(string $field): bool
    {
        return strlen($field) > self::MIN_NONCE_LENGTH and ctype_alnum($field);
    }

    public static function uriValidate(string $uri): bool
    {
        return (bool)filter_var($uri, FILTER_VALIDATE_URL);
    }

    public static function versionValidate(string $version): bool
    {
        return $version == "1";
    }

    public static function schemeValidate(string $scheme): bool
    {
        return preg_match('/^[a-z][a-z0-9+.-]*$/', strtolower($scheme)) === 1;
    }

    public static function statementValidate(string $statement): bool
    {
        return !str_contains($statement, "\n");
    }

    public static function resourceValidate(string $resource): bool
    {
        return self::uriValidate($resource);
    }
}