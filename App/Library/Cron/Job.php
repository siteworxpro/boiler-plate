<?php

declare(strict_types=1);

namespace App\Library\Cron;

use App\Library\App;
use Carbon\Carbon;
use Cron\CronExpression;

/**
 * Class Job
 *
 * @package App\Library\Cron
 */
abstract class Job implements JobInterface
{

    /**
     * @var string
     */
    protected $cronExpression = '* * * * *';

    private $cron;

    public function __construct()
    {
        $this->cron = CronExpression::factory($this->cronExpression);
    }

    final public function checkAndRun(): void
    {
        if ($this->cron->isDue()) {
            App::di()->log->info('[' . static::class . ']' . ' Running');

            try {
                $this->run();
                App::di()
                    ->log
                    ->info('[' . static::class . ']' . ' Completed. Next run: ' . $this->nextRun()->toDateTimeString());
            } catch (CronException $e) {
                App::di()->log->error('[' . static::class . ']' . ' Failed! ' . $e->getMessage());
            } catch (\Exception $e) {
                App::di()->log->error('[' . static::class . ']' . ' Failed! ' . $e->getMessage());
            }
        }
    }

    /**
     * @return Carbon
     */
    final public function lastRun(): Carbon
    {
        return Carbon::createFromTimestamp($this->cron->getPreviousRunDate()->getTimestamp());
    }

    /**
     * @return Carbon
     */
    final public function nextRun(): Carbon
    {
        return Carbon::createFromTimestamp($this->cron->getNextRunDate()->getTimestamp());
    }
}
