<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelRoleStatus extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            ["name" => "Level 1 (QC approved) - M", "min_amount" => 0 , "max_amount" =>10000000 , 'begin_status' => 13, 'end_status' => 10, 'signature_accepted_by' => 10, 'created_user' => 1 , 'updated_user' => 1],
            ["name" => "Level 2 (Manager approved) - M", "min_amount" => 10000000 , "max_amount" =>100000000 , 'begin_status' => 13,'end_status' => 7,'signature_accepted_by' => 7,'created_user' => 1 , 'updated_user' => 1],
            ["name" => "Level 3 (Header approved) - M", "min_amount" => 100000000 , "max_amount" =>99999999999 , 'begin_status' => 13,'end_status' => 14,'signature_accepted_by' => 14,'created_user' => 1 , 'updated_user' => 1],
            ["name" => "Level 1 (None) - (GOP)", "min_amount" => 0 , "max_amount" =>10000000 , 'begin_status' => 13, 'end_status' => 20, 'signature_accepted_by' => 20, 'created_user' => 1 , 'updated_user' => 1],
            ["name" => "Level 2 (Manager approved) - (GOP)", "min_amount" => 10000000 , "max_amount" =>100000000 , 'begin_status' => 13,'end_status' => 23,'signature_accepted_by' => 23,'created_user' => 1 , 'updated_user' => 1],
            ["name" => "Level 3 (Header approved) - (GOP)", "min_amount" => 100000000 , "max_amount" =>99999999999 , 'begin_status' => 13,'end_status' => 14,'signature_accepted_by' => 14,'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('level_role_status')->insert($data);

        $data_lv1_m =[
            ['level_role_status_id'=> 1 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 17 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 18 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 1 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 10 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 11 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 1 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv1_m);

        $data_lv2_m =[
            ['level_role_status_id'=> 2 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 17 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 18 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 2 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 10 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 11 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 2 , 'current_status' => 10 , 'role' => 4 , 'to_status' =>7 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 10 , 'role' => 4 , 'to_status' =>8 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 7 , 'role' => 4 , 'to_status' =>9 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 8 , 'role' => 4 , 'to_status' =>9 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 9 , 'role' => 4 , 'to_status' =>7 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 2 , 'current_status' => 9 , 'role' => 4 , 'to_status' =>8 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv2_m);

        $data_lv3_m =[
            ['level_role_status_id'=> 3 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 13 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 17 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 18 , 'role' => 8 , 'to_status' =>19 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>17 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 19 , 'role' => 8 , 'to_status' =>18 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 3 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 17 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 10 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 11 , 'role' => 7 , 'to_status' =>12 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>10 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 12 , 'role' => 7 , 'to_status' =>11 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 3 , 'current_status' => 10 , 'role' => 4 , 'to_status' =>7 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 10 , 'role' => 4 , 'to_status' =>8 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 7 , 'role' => 4 , 'to_status' =>9 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 8 , 'role' => 4 , 'to_status' =>9 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 9 , 'role' => 4 , 'to_status' =>7 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 9 , 'role' => 4 , 'to_status' =>8 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 3 , 'current_status' => 7 , 'role' => 5 , 'to_status' =>14 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 7 , 'role' => 5 , 'to_status' =>15 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 14 , 'role' => 5 , 'to_status' =>16 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 15 , 'role' => 5 , 'to_status' =>16 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 16 , 'role' => 5 , 'to_status' =>14 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 3 , 'current_status' => 16 , 'role' => 5 , 'to_status' =>15 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv3_m);

        $data_lv1_g =[
            ['level_role_status_id'=> 4 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 4 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 4 , 'current_status' => 20 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 4 , 'current_status' => 22 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 4 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 4 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv1_g);

        $data_lv2_g =[
            ['level_role_status_id'=> 5 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 20 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 22 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 5 , 'current_status' => 20 , 'role' => 10 , 'to_status' =>23 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 20 , 'role' => 10 , 'to_status' =>24 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 23 , 'role' => 10 , 'to_status' =>25 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 24 , 'role' => 10 , 'to_status' =>25 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 25 , 'role' => 10 , 'to_status' =>23 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 5 , 'current_status' => 25 , 'role' => 10 , 'to_status' =>24 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv2_g);

        $data_lv3_g =[
            ['level_role_status_id'=> 6 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 13 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 20 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 22 , 'role' => 9 , 'to_status' =>21 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>20 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 21 , 'role' => 9 , 'to_status' =>22 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 6 , 'current_status' => 20 , 'role' => 10 , 'to_status' =>23 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 20 , 'role' => 10 , 'to_status' =>24 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 23 , 'role' => 10 , 'to_status' =>25 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 24 , 'role' => 10 , 'to_status' =>25 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 25 , 'role' => 10 , 'to_status' =>23 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 25 , 'role' => 10 , 'to_status' =>24 , 'created_user' => 1 , 'updated_user' => 1],

            ['level_role_status_id'=> 6 , 'current_status' => 23 , 'role' => 5 , 'to_status' =>14 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 23 , 'role' => 5 , 'to_status' =>15 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 14 , 'role' => 5 , 'to_status' =>16 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 15 , 'role' => 5 , 'to_status' =>16 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 16 , 'role' => 5 , 'to_status' =>14 , 'created_user' => 1 , 'updated_user' => 1],
            ['level_role_status_id'=> 6 , 'current_status' => 16 , 'role' => 5 , 'to_status' =>15 , 'created_user' => 1 , 'updated_user' => 1],
        ];
        DB::table('transaction_role_status')->insert($data_lv3_g);
    }
}
