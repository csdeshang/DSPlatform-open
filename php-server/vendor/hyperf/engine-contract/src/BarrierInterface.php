<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Hyperf\Engine\Contract;

interface BarrierInterface
{
    /**
     * Wait for the barrier to be released.
     */
    public static function wait(object &$barrier, int $timeout = -1): void;

    /**
     * Create a new barrier instance.
     *
     * @return BarrierInterface
     */
    public static function create(): object;
}
