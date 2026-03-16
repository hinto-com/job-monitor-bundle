<?php

declare(strict_types=1);

namespace Hinto\Bundle\JobMonitorBundle\Service;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;
use Doctrine\DBAL\ParameterType;

readonly class JobMonitorService
{
    public function __construct(private Connection $connection) {}

    /**
     * @throws Exception
     *
     * @return array<string, array{
     *     total: int,
     *     processing: int,
     *     delayed: int,
     *     pending: int
     * }>
     */
    public function getQueueStats(): array
    {
        $sql = '
            SELECT
                queue_name,
                COUNT(*) as total,
                SUM(CASE WHEN delivered_at IS NOT NULL THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN (available_at > NOW() OR (delivered_at IS NOT NULL AND available_at > delivered_at)) THEN 1 ELSE 0 END) as delayed,
                SUM(CASE WHEN delivered_at IS NULL AND available_at <= NOW() THEN 1 ELSE 0 END) as pending
            FROM messenger_messages
            GROUP BY queue_name
        ';

        $rows = $this->connection->fetchAllAssociative($sql);
        $stats = [];
        foreach ($rows as $row) {
            $stats[$row['queue_name']] = [
                'total' => (int) $row['total'],
                'processing' => (int) $row['processing'],
                'delayed' => (int) $row['delayed'],
                'pending' => (int) $row['pending'],
            ];
        }

        return $stats;
    }

    /**
     * @throws Exception
     *
     * @return array<array{
     *     id: int,
     *     class: string,
     *     created_at: string,
     *     error: string
     * }>
     */
    public function getFailedJobs(int $limit = 50): array
    {
        $sql = '
            SELECT id, body, headers, queue_name, created_at, available_at
            FROM messenger_messages
            WHERE queue_name = :queue
            ORDER BY created_at DESC
            LIMIT :limit
        ';

        $rows = $this->connection->fetchAllAssociative($sql, [
            'queue' => 'failed',
            'limit' => $limit,
        ], [
            'limit' => ParameterType::INTEGER,
        ]);

        $jobs = [];

        foreach ($rows as $row) {
            $headers = \json_decode((string) $row['headers'], true);
            $body = \json_decode((string) $row['body'], true);
            $jobs[] = [
                'id' => $row['id'],
                'class' => $headers['type'] ?? 'Unknown',
                'created_at' => $row['created_at'],
                'error' => $body['lastErrorMessage'] ?? 'No error message',
            ];
        }

        return $jobs;
    }

    public function getTotalFailed(): int
    {
        $sql = 'SELECT COUNT(*) FROM messenger_messages WHERE queue_name = :queue';

        return (int) $this->connection->fetchOne($sql, ['queue' => 'failed']);
    }
}
