<?php

declare(strict_types=1);

namespace App\Library\Queue;

use Aws\Sqs\SqsClient;

/**
 * Class Messenger
 *
 * @package App\Library
 */
class Messenger
{

    /**
     * @var SqsClient
     */
    private $sqsClient;

    /**
     * @var string
     */
    private $queueUrl;

    /**
     * Messenger constructor.
     *
     * @throws \Exception
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (!isset($config['queue'])) {
            throw new \InvalidArgumentException('Queue URL not set');
        }

        $this->queueUrl = $config['queue'];

        $config = [
            'credentials' => $config['credentials'],
            'version' => 'latest',
            'region' => 'us-east-1'
        ];

        $this->sqsClient = new SqsClient($config);
    }

    /**
     * @return SqsClient
     */
    public function getClient(): SqsClient
    {
        return $this->sqsClient;
    }

    /**
     * @param string $message
     * @param int $delay
     *
     * @return string
     */
    public function sendMessage(string $message, int $delay = 0): string
    {
        $params = [
            'DelaySeconds' => $delay,
            'MessageBody' => $message,
            'QueueUrl' => $this->queueUrl
        ];
        $result = $this->sqsClient->sendMessage($params);

        return $result->get('MessageId');
    }

    /**
     * @return Message
     */
    public function getMessage(): ?Message
    {
        $params = [
            'MaxNumberOfMessages' => 1,
            'QueueUrl' => $this->queueUrl
        ];

        $result = $this->sqsClient->receiveMessage($params);

        $messages = $result->get('Messages');

        if (empty($messages)) {
            return null;
        }

        $this->sqsClient->deleteMessage([
            'QueueUrl' => $this->queueUrl,
            'ReceiptHandle' => $messages[0]['ReceiptHandle']
        ]);

        return new Message($messages[0]);
    }
}
