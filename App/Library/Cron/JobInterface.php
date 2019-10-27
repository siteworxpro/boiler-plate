<?php

declare(strict_types=1);

namespace App\Library\Cron;

use Carbon\Carbon;

/**
 * Interface JobInterface
 *
 * @package App\Library\Cron
 */
interface JobInterface
{

    /**
     * check and run the job if needed
     */
    public function checkAndRun(): void;

    /**
     * run the job
     * @throws CronException
     */
    public function run(): void;

    /**
     * Next Run Date Time
     *
     * @return Carbon
     */
    public function nextRun(): Carbon;

    /**
     * Last Run Date Time
     *
     * @return Carbon
     */
    public function lastRun(): Carbon;
}
