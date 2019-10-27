<?php

declare(strict_types=1);

namespace App\Library\Queue;

use App\Library\App;
use App\Library\Container;
use App\Library\Queue\Jobs\JobInterface;
use App\Models\QueueLogs;
use Carbon\Carbon;

/**
 * Class Consumer
 *
 * @package App\Library\Queue
 */
class Consumer
{

    /**
     * @var bool
     */
    private static $shutDown = false;

    /**
     * @var Messenger
     */
    private $messenger;

    /**
     * Consumer constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->messenger = new Messenger(App::di()->config->get('aws.sqs'));
        $this->registerSignalHandlers();
    }

    /**
     * register handlers
     */
    private function registerSignalHandlers(): void
    {
        App::di()->log->debug('Registering Shutdown Functions: ' . self::class);
        \pcntl_signal(SIGINT, [self::class, 'handleSignal']); // Interrupted (Ctrl-C is pressed)
        \pcntl_signal(SIGTERM, [self::class, 'handleSignal']);
        \pcntl_signal(SIGHUP, [self::class, 'handleSignal']);
    }

    /**
     * @param $signal
     */
    public static function handleSignal($signal): void
    {
        switch ($signal) {
            // Graceful
            case SIGINT:
            case SIGTERM:
            case SIGHUP:
                /** @var Container $container */
                $container = App::getApp()->getContainer();
                $container->log->info('Received stop signal... Letting all work complete before stopping');
                self::$shutDown = true;

                break;

            // Not Graceful
            case SIGKILL:
                exit(9);

                break;
        }
    }

    /**
     * @return int
     */
    public function startConsumer(): int
    {

        $this->registerSignalHandlers();

        $first_run = true;

        while (true) {
            $class = null;
            $queue = null;
            $message = null;

            if (self::$shutDown) {
                return 0;
            }

            pcntl_signal_dispatch();

            if (!$first_run) {
                sleep(15);
            }

            $message = $this->messenger->getMessage();

            if ($message instanceof Message) {
                App::di()->log->info('Received Job:' . $message->getId());
                App::di()->log->debug('Received Payload:' . $message->toJson());

                /** @var QueueLogs $queue */
                $queue = QueueLogs::where('message_id', $message->getId())->get()->first();

                /** @var JobInterface $class */
                $class = new $queue->job();

                $this->performTask($class, $queue, $message->toArray());

                App::di()->log->info('Completed Job:' . $message->getId());
            }

            $first_run = false;
        }

        return 0;
    }

    /**
     * @param JobInterface $class
     * @param array $params
     * @param QueueLogs $queue
     */
    private function performTask(JobInterface $class, QueueLogs $queue, array $params = []): void
    {

        App::di()->log->info('Starting Job ' . class_basename($class));

        $queue->status = QueueLogs::STATUS_RUNNING;
        $queue->started_at = Carbon::now()->toDateTimeString();
        $queue->save();

        try {
            $class->assertAllowed();
            $class->runJob($params);
            $queue->status = QueueLogs::STATUS_COMPLETE;
            $queue->completed_at = Carbon::now()->toDateTimeString();
            $queue->save();
            $class->onSuccess();
            App::di()->log->info('Completed Job ' . class_basename($class));
        } catch (NotAllowedException $exception) {
            App::di()->log->warning($exception->getMessage());
            $queue->status = QueueLogs::STATUS_NOT_ALLOWED;
            $queue->save();
            App::di()->log->warning('Job Failed: ' . class_basename($class) . ' Reason: Not Allowed');
        } catch (\Throwable $exception) {
            App::di()->log->warning($exception->getMessage());
            $queue->status = QueueLogs::STATUS_FAILED;
            $queue->save();
            $class->onFail();
            App::di()->log->error('Job Failed: ' . class_basename($class) . ' Reason: ' . $exception->getMessage());
        }
    }
}
