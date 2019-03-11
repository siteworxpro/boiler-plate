<?php


use Phinx\Migration\AbstractMigration;

class QueueLogs extends AbstractMigration
{
    public function change(): void
    {
        $this->table('queue_logs')
            ->addColumn('job', 'string')
            ->addColumn('message_id', 'string', ['length' => 100])
            ->addColumn('message_content', 'text')
            ->addColumn('status', 'integer', ['length' => 3])
            ->addColumn('started_at', 'datetime', ['default' => null])
            ->addColumn('completed_at', 'datetime', ['default' => null])
            ->addTimestamps()
            ->addIndex('message_id')
            ->addIndex('started_at')
            ->addIndex('completed_at')
            ->create();

    }
}
