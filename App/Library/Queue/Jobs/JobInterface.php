<?php declare(strict_types = 1);

namespace App\Library\Queue\Jobs;


use App\Library\Queue\NotAllowedException;

/**
 * Interface JobInterface
 *
 * @package App\Library\Queue\Jobs
 */
interface JobInterface
{

    /**
     * @param array $params
     */
    public function runJob(array $params = []): void;

    /**
     * Fires on success
     */
    public function onSuccess(): void;

    /**
     * Fires on fail
     */
    public function onFail(): void;

    /**
     * @throws NotAllowedException
     * @return bool
     */
    public function assertAllowed(): bool;
}
