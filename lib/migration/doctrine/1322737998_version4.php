<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version4 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->changeColumn('fiche', 'number', 'string', '13', array('notnull' => '1'));
    }

    public function down()
    {
    }
}