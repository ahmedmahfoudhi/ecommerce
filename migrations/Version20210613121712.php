<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210613121712 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY cart_item_ibfk_1');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY cart_item_ibfk_2');
        $this->addSql('ALTER TABLE cart_item CHANGE cart_item_id cart_item_id INT AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE shopping_cart_id shopping_cart_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE25274584665A FOREIGN KEY (product_id) REFERENCES product (product_id)');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT FK_F0FE252745F80CD FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (shopping_cart_id)');
        $this->addSql('ALTER TABLE category CHANGE deleted_at deleted_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY command_ibfk_1');
        $this->addSql('ALTER TABLE command CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT FK_8ECAEAD4A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE line DROP FOREIGN KEY line_ibfk_1');
        $this->addSql('ALTER TABLE line DROP FOREIGN KEY line_ibfk_2');
        $this->addSql('ALTER TABLE line CHANGE line_id line_id INT AUTO_INCREMENT NOT NULL, CHANGE product_id product_id INT DEFAULT NULL, CHANGE command_id command_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE line ADD CONSTRAINT FK_D114B4F633E1689A FOREIGN KEY (command_id) REFERENCES command (commandid)');
        $this->addSql('ALTER TABLE line ADD CONSTRAINT FK_D114B4F64584665A FOREIGN KEY (product_id) REFERENCES product (product_id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY product_ibfk_1');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT DEFAULT NULL, CHANGE deleted_at deleted_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (category_id)');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY shopping_cart_ibfk_1');
        $this->addSql('ALTER TABLE shopping_cart CHANGE user_id user_id INT DEFAULT NULL, CHANGE deleted_at deleted_at DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6A76ED395 FOREIGN KEY (user_id) REFERENCES user (user_id)');
        $this->addSql('ALTER TABLE user ADD roles JSON NOT NULL, DROP role, DROP created_at, DROP deleted_at, CHANGE firstname firstname VARCHAR(255) NOT NULL, CHANGE lastname lastname VARCHAR(255) NOT NULL, CHANGE state state VARCHAR(255) NOT NULL, CHANGE street street VARCHAR(255) DEFAULT NULL, CHANGE postal_code postal_code INT DEFAULT NULL, CHANGE phone_number phone_number INT NOT NULL, CHANGE email email VARCHAR(180) NOT NULL, CHANGE password password VARCHAR(255) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE25274584665A');
        $this->addSql('ALTER TABLE cart_item DROP FOREIGN KEY FK_F0FE252745F80CD');
        $this->addSql('ALTER TABLE cart_item CHANGE cart_item_id cart_item_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL, CHANGE shopping_cart_id shopping_cart_id INT NOT NULL');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT cart_item_ibfk_1 FOREIGN KEY (product_id) REFERENCES product (product_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cart_item ADD CONSTRAINT cart_item_ibfk_2 FOREIGN KEY (shopping_cart_id) REFERENCES shopping_cart (shopping_cart_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category CHANGE deleted_at deleted_at DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE command DROP FOREIGN KEY FK_8ECAEAD4A76ED395');
        $this->addSql('ALTER TABLE command CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE command ADD CONSTRAINT command_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (user_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE line DROP FOREIGN KEY FK_D114B4F633E1689A');
        $this->addSql('ALTER TABLE line DROP FOREIGN KEY FK_D114B4F64584665A');
        $this->addSql('ALTER TABLE line CHANGE line_id line_id INT NOT NULL, CHANGE command_id command_id INT NOT NULL, CHANGE product_id product_id INT NOT NULL');
        $this->addSql('ALTER TABLE line ADD CONSTRAINT line_ibfk_1 FOREIGN KEY (command_id) REFERENCES command (commandid) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE line ADD CONSTRAINT line_ibfk_2 FOREIGN KEY (product_id) REFERENCES product (product_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD12469DE2');
        $this->addSql('ALTER TABLE product CHANGE category_id category_id INT NOT NULL, CHANGE deleted_at deleted_at DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT product_ibfk_1 FOREIGN KEY (category_id) REFERENCES category (category_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6A76ED395');
        $this->addSql('ALTER TABLE shopping_cart CHANGE user_id user_id INT NOT NULL, CHANGE deleted_at deleted_at DATE DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT shopping_cart_ibfk_1 FOREIGN KEY (user_id) REFERENCES user (user_id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('DROP INDEX UNIQ_8D93D649E7927C74 ON user');
        $this->addSql('ALTER TABLE user ADD role INT NOT NULL, ADD created_at DATE NOT NULL, ADD deleted_at DATE DEFAULT \'NULL\', DROP roles, CHANGE email email TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE password password TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE firstname firstname VARCHAR(70) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE lastname lastname VARCHAR(70) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE state state TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE street street TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, CHANGE postal_code postal_code INT NOT NULL, CHANGE phone_number phone_number TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`');
    }
}
