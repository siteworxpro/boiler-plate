<?php declare(strict_types = 1);

namespace App\Library\Queue\Jobs;

use App\Library\App;
use App\Library\Queue\NotAllowedException;

/**
 * Class Job
 */
abstract class Job implements JobInterface
{
    /**
     * @return bool
     * @throws NotAllowedException
     */
    public function assertAllowed(): bool
    {
        /** Prod jobs are always allowed */
        if (App::di()->config->get('dev_mode', false) === false) {
            return true;
        }

        $class = static::class;

        if (!\in_array($class, App::di()->config->get('whitelisted_jobs', []), false)) {
            throw new NotAllowedException('Job now whitelisted');
        }

        return true;
    }
}
