<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

class Version20260527000000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove Gedmo nested tree columns from categories';
    }

    public function up(Schema $schema): void
    {
        $categories = $schema->getTable('categories');
        $categories->dropColumn('lft');
        $categories->dropColumn('lvl');
        $categories->dropColumn('rgt');
        $categories->dropColumn('root');
    }

    public function down(Schema $schema): void
    {
        $categories = $schema->getTable('categories');
        $categories->addColumn('lft', 'integer', ['notnull' => true]);
        $categories->addColumn('lvl', 'integer', ['notnull' => true]);
        $categories->addColumn('rgt', 'integer', ['notnull' => true]);
        $categories->addColumn('root', 'integer', ['notnull' => false]);
    }
}
