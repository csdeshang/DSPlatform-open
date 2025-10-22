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

namespace Hyperf\Engine;

use Hyperf\Engine\Contract\BarrierInterface;
use Swoole\Coroutine\Barrier as SwooleBarrier;

class Barrier implements BarrierInterface
{
    public static function wait(object &$barrier, int $timeout = -1): void
    {
        SwooleBarrier::wait($barrier, $timeout);
    }

    public static function create(): object
    {
        return SwooleBarrier::make();
    }
}
