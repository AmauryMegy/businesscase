<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220819152023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE category ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE payment_method ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE photo CHANGE product_id product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE status ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand DROP slug');
        $this->addSql('ALTER TABLE category DROP slug');
        $this->addSql('ALTER TABLE payment_method DROP slug');
        $this->addSql('ALTER TABLE photo CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE status DROP slug');
        $this->addSql('ALTER TABLE user DROP slug');
    }
}
