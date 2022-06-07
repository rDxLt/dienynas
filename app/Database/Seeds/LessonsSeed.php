<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class LessonsSeed extends Seeder
{
    public function run()
    {
        $this->db->table('lessons')->truncate();

        $data = [
            [
                'title' => 'lietuviu kalba',
            ],
            [
                'title' => 'anglu kalba',
            ],
            [
                'title' => 'geografija',
            ],
            [
                'title' => 'matematika',
            ],
            [
                'title' => 'informatika',
            ],
        ];

        $this->db->table('lessons')->insertBatch($data);
    }
}
