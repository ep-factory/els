<?php
/**
 * This class has been auto-generated by the Doctrine ORM Framework
 */
class Version3 extends Doctrine_Migration_Base
{
    public function up()
    {
        $this->removeColumn('fiche', 'parent_id');
        $this->removeColumn('fiche_log', 'parent_id');
    }

    public function down()
    {
        $this->addColumn('fiche', 'parent_id', 'integer', '8', array());
        $this->addColumn('fiche_log', 'parent_id', 'integer', '8', array());
    }
}