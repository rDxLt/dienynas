<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMissingFieldsInTables extends Migration
{
    public function up()
    {
        $fields = [
            'date' => [
                'type' => 'TEXT',
                'constraint' => '10',
            ],
        ];

        $this->forge->addColumn('grades', $fields);
        $this->forge->addColumn('notices', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('grades', 'date');
        $this->forge->dropColumn('notices', 'date');
    }
}
