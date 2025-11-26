<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251126132915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add version column to accounts table for optimistic locking';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accounts ADD version INT NOT NULL DEFAULT 1');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE accounts DROP version');
    }
}
