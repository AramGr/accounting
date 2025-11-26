<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20251126101419 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create balance_history table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE balance_history (
            id INT AUTO_INCREMENT NOT NULL,
            account_id INT NOT NULL,
            change_amount DECIMAL(15, 2) NOT NULL,
            balance_before DECIMAL(15, 2) NOT NULL,
            balance_after DECIMAL(15, 2) NOT NULL,
            created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            INDEX IDX_balance_history_created_at (created_at),
            PRIMARY KEY(id),
            CONSTRAINT FK_balance_history_account FOREIGN KEY (account_id) REFERENCES accounts (id) ON DELETE CASCADE
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE balance_history');
    }
}
