<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusTransSeedTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $now = date("Y-m-d H:i:s");
        $data = [
            
            ['name' => 'Claim Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lead Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lead Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Lead Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'QC Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'QC Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'QC Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'New', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Header Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Header Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Header Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim Independent Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim Independent Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim Independent Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim GOP Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim GOP Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Claim GOP Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager GOP Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager GOP Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Manager GOP Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Supper Approved', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Supper Rejected', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now],
            ['name' => 'Supper Repealed', 'created_user' => 1, 'updated_user' => 1, 'created_at' => $now, 'updated_at' => $now]
        ];
        DB::table('role_change_status')->insert($data);
    }
}
