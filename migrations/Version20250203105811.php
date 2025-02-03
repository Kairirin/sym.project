<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250203105811 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE juego (id INT AUTO_INCREMENT NOT NULL, plataforma_id INT NOT NULL, nombre VARCHAR(255) NOT NULL, imagen VARCHAR(255) NOT NULL, INDEX IDX_F0EC403DEB90E430 (plataforma_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plataforma (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, juego_id INT NOT NULL, autor_id INT NOT NULL, titulo VARCHAR(255) NOT NULL, comentario VARCHAR(255) NOT NULL, ruta_captura VARCHAR(255) NOT NULL, INDEX IDX_794381C613375255 (juego_id), INDEX IDX_794381C614D45BBE (autor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usuario (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(20) NOT NULL, password VARCHAR(255) NOT NULL, role VARCHAR(30) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE juego ADD CONSTRAINT FK_F0EC403DEB90E430 FOREIGN KEY (plataforma_id) REFERENCES plataforma (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C613375255 FOREIGN KEY (juego_id) REFERENCES juego (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C614D45BBE FOREIGN KEY (autor_id) REFERENCES usuario (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE juego DROP FOREIGN KEY FK_F0EC403DEB90E430');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C613375255');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C614D45BBE');
        $this->addSql('DROP TABLE juego');
        $this->addSql('DROP TABLE plataforma');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP TABLE usuario');
    }
}
