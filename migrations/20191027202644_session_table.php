<?php

use Phinx\Migration\AbstractMigration;

class SessionTable extends AbstractMigration
{
    public function change(): void
    {
        $this->table('sessions', ['id' => false, 'primary_key' => 'key'])
            ->addColumn('key', 'string', ['null' => false, 'length' => 36])
            ->addColumn('ip', 'string',  ['null' => false, 'length' => 15])
            ->addColumn('user_agent', 'text', ['null' => false])
            ->addColumn('remember', 'boolean', ['default' => false, 'null' => false])
            ->addColumn('session', 'text')
            ->addTimestamps()
            ->save();
    }
}
