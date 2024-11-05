<?php
declare(strict_types=1);

namespace Zbkm\Siwe\Utils;

use DateTime;

class TimeFormatter
{
    public static function timestampToISO(int $timestamp): string
    {
        $date = new DateTime();
        $date->setTimestamp($timestamp);
        return $date->format('Y-m-d\TH:i:s.v\Z');
    }

    public static function ISOToTimestamp(string $iso): int
    {
        return strtotime($iso);
    }
}