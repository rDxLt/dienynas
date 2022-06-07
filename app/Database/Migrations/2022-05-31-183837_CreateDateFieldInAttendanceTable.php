<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDateFieldInAttendanceTable extends Migration
{
    public function up()
    {
        $fields = [
            'date' => [
                'type' => 'TEXT',
                'constraint' => '10',
            ],
        ];

        $this->forge->addColumn('attendance', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('attendance', 'date');
    }
}
