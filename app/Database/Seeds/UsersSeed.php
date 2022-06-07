<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UsersSeed extends Seeder
{
    public function run()
    {
        $this->db->table('users')->truncate();
        $this->db->table('students')->truncate();
        $this->db->table('teachers')->truncate();

        $users = [
            [
                'email' => 'direktorius@mokykla.lt',
                'password' => md5('direktorius'),
                'firstname' => 'Petras',
                'lastname' => 'Petraitis',
                'type' => 'director',
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
