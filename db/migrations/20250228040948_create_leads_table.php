<?php

declare(strict_types=1);

use Phinx\Migration\AbstractMigration;

final class CreateLeadsTable extends AbstractMigration
{
    public function change(): void
    {
        $table = $this->table('leads');
        $table->addColumn('name', 'string', ['limit' => 50])
              ->addColumn('email', 'string', ['limit' => 100])
              ->addColumn('phone', 'string', ['limit' => 20, 'null' => true])
              ->addColumn('source', 'enum', ['values' => ['facebook', 'google', 'linkedin', 'manual']])
              ->addColumn('created_at', 'timestamp', ['default' => 'CURRENT_TIMESTAMP'])
              ->addIndex(['email'], ['unique' => true])
              ->create();
    }
}
