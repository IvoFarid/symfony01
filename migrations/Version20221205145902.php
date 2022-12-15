<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221205145902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA76AA074ED');
        $this->addSql('ALTER TABLE followers DROP FOREIGN KEY FK_8408FDA79D86650F');
        $this->addSql('DROP TABLE followers');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649290B2F37 ON user (nick)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE followers (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, follow_id_id INT NOT NULL, INDEX IDX_8408FDA79D86650F (user_id_id), INDEX IDX_8408FDA76AA074ED (follow_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA76AA074ED FOREIGN KEY (follow_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE followers ADD CONSTRAINT FK_8408FDA79D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP INDEX UNIQ_8D93D649290B2F37 ON `user`');
    }
}
