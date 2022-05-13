<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Admin;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $adminRecords = [
            ['id'=> 1, 'name'=> 'Super Admin', 'type'=> 'superadmin', 'vendor_id'=> 0, 'mobile'=> '01762711599', 'email'=> 'shahidullah.piyaro@gmail.com', 'password'=> '$2y$10$Sfgx8CTfZTYL6vj6yPc2SOcUiO6tOWFsnvdYZLPmzcyu6JrZg4Dea', 'image'=> '', 'status'=> 1],
        ];
        Admin::insert($adminRecords);
    }
}
