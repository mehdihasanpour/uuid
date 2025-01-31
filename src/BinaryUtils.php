<?php

/**
 * This file is part of the ramsey/uuid library
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @copyright Copyright (c) Ben Ramsey <ben@benramsey.com>
 * @license http://opensource.org/licenses/MIT MIT
 */

declare(strict_types=1);

namespace Ramsey\Uuid;

/**
 * Provides binary math utilities
 */
class BinaryUtils
{
    /**
     * Applies the RFC 4122 variant field to the 16-bit clock sequence
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.1 RFC 4122, § 4.1.1: Variant
     *
     * @param int $clockSeq The 16-bit clock sequence value before the RFC 4122
     *     variant is applied
     *
     * @return int The 16-bit clock sequence multiplexed with the UUID variant
     *
     * @psalm-pure
     */
    public static function applyVariant(int $clockSeq, Variant $variant = Variant::Rfc4122): int
    {
        return match ($variant) {
            Variant::ReservedNcs => $clockSeq & 0x7fff,
            Variant::Rfc4122 => $clockSeq & 0x3fff | 0x8000,
            Variant::ReservedMicrosoft => $clockSeq & 0x1fff | 0xc000,
            Variant::ReservedFuture => $clockSeq & 0x1fff | 0xe000,
        };
    }

    /**
     * Applies the RFC 4122 version number to the 16-bit `time_hi_and_version` field
     *
     * @link http://tools.ietf.org/html/rfc4122#section-4.1.3 RFC 4122, § 4.1.3: Version
     *
     * @param int $timeHi The value of the 16-bit `time_hi_and_version` field
     *     before the RFC 4122 version is applied
     * @param int $version The RFC 4122 version to apply to the `time_hi` field
     *
     * @return int The 16-bit time_hi field of the timestamp multiplexed with
     *     the UUID version number
     *
     * @psalm-pure
     */
    public static function applyVersion(int $timeHi, int $version): int
    {
        $timeHi = $timeHi & 0x0fff;
        $timeHi |= $version << 12;

        return $timeHi;
    }
}
