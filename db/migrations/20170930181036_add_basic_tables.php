<?php


use Phinx\Migration\AbstractMigration;

class AddBasicTables extends AbstractMigration
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
    public function up()
    {
        $clients = $this->table("client");
        if (!$clients->exists()) {
            $clients->addColumn('ci', 'string', ['limit' => 9, 'null' => false]);
            $clients->addColumn('name', 'string', ['null' => false]);
            $clients->addColumn('lastname', 'string', ['null' => false]);
            $clients->addColumn('telephone', 'string', ['null' => true]);
            $clients->addColumn('cellphone', 'string', ['null' => true]);
            $clients->addColumn('birthdate', 'datetime', ['null' => true]);
            $clients->addColumn('enable', 'integer', ['null' => false, 'limit' => 1, 'default' => 1]);
            $clients->addIndex(['ci'], ['unique' => true, 'name' => 'unique_ci']);
            $clients->create();
        }

        $oculist = $this->table("oculist");
        if (!$oculist->exists()) {
            $oculist->addColumn('name', 'string', ['null' => false]);
            $oculist->addColumn('lastname', 'string', ['null' => false]);
            $oculist->addColumn('professional_code', 'string', ['null' => false]);
            $oculist->create();
        }


        $recipies = $this->table("recipe");
        if (!$recipies->exists()) {
            $recipies->addColumn('date', 'datetime');
            $recipies->addColumn('bps', 'integer', ['limit' => 1]);
            $recipies->addColumn('oculist_id', 'integer');
            $recipies->addColumn('client_id', 'integer');
            $recipies->addColumn('observations', 'string');
            $recipies->addForeignKey('oculist_id', 'oculist', 'id', ['delete' => 'cascade']);
            $recipies->addForeignKey('client_id', 'client', 'id', ['delete' => 'cascade']);
            $recipies->create();
        }

        $recipies_data = $this->table('recipe_data');
        if (!$recipies_data->exists()) {
            $recipies_data->addColumn("recipe_id", 'integer');
            $recipies_data->addColumn("close", 'integer', ['limit' => 1]);
            $recipies_data->addColumn("distance", 'integer', ['limit' => 1]);
            $recipies_data->addColumn("eye", 'string');
            $recipies_data->addColumn("esf", 'string');
            $recipies_data->addColumn("cil", 'string');
            $recipies_data->addColumn("eje", 'integer');
            $recipies_data->addColumn("prisma", 'string');
            $recipies_data->addColumn("disInt", 'integer');
            $recipies_data->addForeignKey('recipe_id', 'recipe', 'id', ['delete' => 'cascade']);
            $recipies_data->create();
        }

        $type_crystal = $this->table("type_crystal");
        if (!$type_crystal->exists()) {
            $type_crystal->addColumn('name', 'string');
            $type_crystal->addIndex(['name'], ['unique' => true, 'name' => 'unique_name']);
            $type_crystal->create();
        }


        $brand = $this->table("brand");
        if (!$brand->exists()) {
            $brand->addColumn('name', 'string');
            $brand->addIndex(['name'], ['unique' => true, 'name' => 'unique_name']);
            $brand->create();
        }

        $lens = $this->table("lens");
        if (!$lens->exists()) {
            $lens->addColumn('date', 'timestamp', ['default' => 'CURRENT_TIMESTAMP']);
            $lens->addColumn('brand_id', 'integer');
            $lens->addColumn('model', 'string');
            $lens->addColumn('price', 'integer');
            $lens->addColumn('type_crystal_id', 'integer');
            $lens->addColumn('price_crystal', 'integer');
            $lens->addColumn('discount', 'integer');
            $lens->addColumn('observations', 'string');
            $lens->addForeignKey('brand_id', 'brand', 'id');
            $lens->addForeignKey('type_crystal_id', 'type_crystal', 'id');
            $lens->create();
        }

        $user = $this->table('user');
        if (!$user->exists()) {
            $user->addColumn('name', 'string');
            $user->addColumn('lastname', 'string');
            $user->addColumn('username', 'string');
            $user->addColumn('password', 'string');
            $user->addColumn('token', 'string');
            $user->create();
        }
    }

    public function down()
    {
        $clients = $this->table("client");
        if ($clients->exists()) {
            $clients->drop();
        }

        $oculist = $this->table("oculist");
        if ($oculist->exists()) {
            $oculist->drop();
        }


        $recipies = $this->table("recipe");
        if ($recipies->exists()) {
            $recipies->drop();
        }

        $recipies_data = $this->table('recipe_data');
        if ($recipies_data->exists()) {
            $recipies_data->drop();
        }

        $type_crystal = $this->table("type_crystal");
        if ($type_crystal->exists()) {
            $type_crystal->drop();
        }


        $brand = $this->table("brand");
        if ($brand->exists()) {
            $brand->drop();
        }

        $lens = $this->table("lens");
        if ($lens->exists()) {
            $lens->drop();
        }

        $user = $this->table('user');
        if ($user->exists()) {
            $user->drop();
        }
    }
}
