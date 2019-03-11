<?php


use Phinx\Migration\AbstractMigration;

class OauthTables extends AbstractMigration
{
    public function change(): void
    {

        $this->table('clients')
            ->addColumn('client_id', 'string', ['length' => 32])
            ->addColumn('client_secret', 'string', ['length' => 64])
            ->addColumn('client_name', 'string', ['length' => 24])
            ->addColumn('grant_type', 'string', ['length' => 32])
            ->addTimestamps()
            ->addIndex('client_id', ['unique' => true])
            ->addIndex('client_secret', ['unique' => true])
            ->save();

        $this->table('scopes')
            ->addColumn('scope_name', 'string', ['length' => 32])
            ->addColumn('scope_description', 'text')
            ->addTimestamps()
            ->save();

        $this->table('access_tokens')
            ->addColumn('client_id', 'integer')
            ->addColumn('token', 'string')
            ->addColumn('is_revoked', 'boolean', ['default' => false])
            ->addColumn('expires', 'datetime')
            ->addTimestamps()
            ->addIndex('token', ['unique' => true])
            ->addIndex('is_revoked')
            ->addIndex('client_id')
            ->addForeignKey('client_id', 'clients', 'id', ['delete' => 'CASCADE'])
            ->create();

        $this->table('token_scopes')
            ->addColumn('token_id', 'integer')
            ->addColumn('scope_id', 'integer')
            ->addTimestamps()
            ->addIndex([ 'token_id', 'scope_id'], ['unique' => true ])
            ->addIndex('token_id')
            ->addForeignKey('token_id', 'access_tokens', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('scope_id', 'scopes', 'id', ['delete' => 'CASCADE'])
            ->create();

        $this->table('client_scopes')
            ->addColumn('client_id', 'integer')
            ->addColumn('scope_id', 'integer')
            ->addTimestamps()
            ->addIndex([ 'client_id', 'scope_id'], ['unique' => true ])
            ->addIndex('client_id')
            ->addForeignKey('client_id', 'clients', 'id', ['delete' => 'CASCADE'])
            ->addForeignKey('scope_id', 'scopes', 'id', ['delete' => 'CASCADE'])
            ->create();
    }
}
