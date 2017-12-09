<?php


use Phinx\Migration\AbstractMigration;

class AddRecipeLensFk extends AbstractMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $lens = $this->table("lens");
        if(!$lens->hasColumn('recipe_id')){
            $lens->addColumn('recipe_id', 'integer');
            $lens->update();
            $lens->addForeignKey('recipe_id', 'recipe', 'id', ['delete' => 'cascade']);
            $lens->update();
        }

        if(!$lens->hasColumn('order_id')){
            $lens->addColumn('code_order', 'integer');
            $lens->update();
        }
    }
}
