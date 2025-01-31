<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// database migration to create the config table, this will comprise of:
/*
id - int 11 autointeger
config_item varchar 255 unique
config_value varchar 255
*/

class Migration_Create_config_table extends CI_Migration {

    public function up()
    {
        $this->dbforge->add_field(array(
            'id' => array(
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'config_item' => array(
                'type' => 'VARCHAR',
                'constraint' => '255',
                'unique' => TRUE
            ),
            'config_value' => array(
                'type' => 'VARCHAR',
                'constraint' => '255'
            )
        ));
        $this->dbforge->add_key('id', TRUE);
        $this->dbforge->create_table('config');
    }

    public function down()
    {
        $this->dbforge->drop_table('config');
    }
}
