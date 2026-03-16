<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\Tests;

use Doctrine\DBAL\Connection;
use Hinto\Bundle\JobMonitorBundle\Service\JobMonitorService;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 */
#[CoversNothing]
class JobMonitorServiceTest extends TestCase
{
    public function testGetTotalFailed(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->method('fetchOne')
            ->willReturn('5');

        $service = new JobMonitorService($connection);
        $this->assertSame(5, $service->getTotalFailed());
    }

    public function testGetQueueStatsReturnsCorrectShape(): void
    {
        $connection = $this->createMock(Connection::class);
        $connection
            ->method('fetchAllAssociative')
            ->willReturn([
                [
                    'queue_name' => 'default',
                    'total' => '10',
                    'processing' => '2',
                    'delayed' => '1',
                    'pending' => '7',
                ],
            ]);

        $service = new JobMonitorService($connection);
        $stats = $service->getQueueStats();

        $this->assertArrayHasKey('default', $stats);
        $this->assertSame(10, $stats['default']['total']);
        $this->assertSame(7, $stats['default']['pending']);
    }
}
