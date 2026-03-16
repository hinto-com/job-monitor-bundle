<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\Controller\Api;

use Hinto\Bundle\JobMonitorBundle\Service\JobMonitorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api/admin/jobs', name: 'hinto_job-monitoring_dashboard_api_')]
class JobDashboardController extends AbstractController
{
    public function __construct(private readonly JobMonitorService $monitor) {}

    #[Route('/stats', name: 'stats', methods: ['GET'])]
    public function stats(): JsonResponse
    {
        return new JsonResponse([
            'queue_stats' => $this->monitor->getQueueStats(),
            'total_failed' => $this->monitor->getTotalFailed(),
            'workers' => $this->getWorkerStatus(),
        ]);
    }

    /**
     * @return array<array{
     *     pid: string,
     *     command: string
     * }>
     */
    private function getWorkerStatus(): array
    {
        $process = new Process(['pgrep', '-fa', 'messenger:consume']);
        $process->run();
        $workers = [];
        $lines = \array_filter(\explode("\n", mb_trim($process->getOutput())));

        foreach ($lines as $line) {
            $parts = \explode(' ', $line, 2);
            $workers[] = [
                'pid' => $parts[0] ?? '',
                'command' => $parts[1] ?? '',
            ];
        }

        return $workers;
    }
}
