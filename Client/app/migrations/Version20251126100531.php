<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251126100531 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create request_logs table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE request_logs (
            id INT AUTO_INCREMENT NOT NULL,
            action ENUM('add', 'remove') NOT NULL,
            amount DECIMAL(15, 2) NOT NULL,
            success TINYINT(1) NOT NULL DEFAULT 0,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX IDX_request_logs_created_at (created_at),
            INDEX IDX_request_logs_action (action),
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE request_logs');
    }
}
