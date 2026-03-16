<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle;

use Hinto\Bundle\JobMonitorBundle\DependencyInjection\HintoJobMonitorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class HintoJobMonitorBundle extends Bundle
{
    #[\Override]
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    #[\Override]
    public function getContainerExtension(): HintoJobMonitorExtension
    {
        return new HintoJobMonitorExtension();
    }
}
