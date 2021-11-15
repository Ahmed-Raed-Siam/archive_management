<?php

namespace Database\Seeders;

use App\Models\files as Modelfiles;
use Illuminate\Database\Seeder;

class files extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Modelfiles::create([
            'name' => 'name',
            'parent_id' => 1,
            'user_id' => 1,
            'type' => 'folder',
            'file_path' => 'folders',
            'file_size' => '',
            'file_type' => '',
        ]);
    }
}
