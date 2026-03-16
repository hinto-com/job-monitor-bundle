<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\Controller\Web;

use Hinto\Bundle\JobMonitorBundle\Service\JobMonitorService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Process\Process;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin/jobs', name: 'hinto_job-monitoring_dashboard_')]
class JobDashboardController extends AbstractController
{
    public function __construct(private readonly JobMonitorService $monitor) {}

    #[Route('', name: 'index')]
    public function index(): Response
    {
        return $this->render('@HintoJobMonitor/index.html.twig', [
            'queue_stats' => $this->monitor->getQueueStats(),
            'failed_jobs' => $this->monitor->getFailedJobs(),
            'total_failed' => $this->monitor->getTotalFailed(),
        ]);
    }

    #[Route('/retry/{id}', name: 'retry', methods: ['POST'])]
    public function retry(int $id): JsonResponse
    {
        $process = new Process([
            'php',
            $this->getParameter('kernel.project_dir') . '/bin/console',
            'messenger:failed:retry',
            (string) $id,
            '--force',
        ]);
        $process->run();
        if ($process->isSuccessful()) {
            return new JsonResponse(['status' => 'retried']);
        }

        return new JsonResponse(['status' => 'error', 'message' => $process->getErrorOutput()], 500);
    }
}
