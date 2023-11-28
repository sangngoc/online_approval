<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->seedUnit();
        $this->seedDep();
        $this->seedSec();
        $this->seedUser();

        $this->seedSys();
        $this->seedType();
        $this->seedMaster();
        $this->seedRoute();
        $this->seedEmpRoute();
    }

    public function seedUnit(){
        DB::insert('insert into units (unit_id, unit_name)
        values (?,?)',
        ['u0001','Office'],
        );

        DB::insert('insert into units (unit_id, unit_name)
        values (?,?)',
        ['u0002','Production'],
        );
    }
    public function seedDep(){
        DB::insert('insert into departments (dep_id, dep_name, unit_id)
        values (?,?,?)',
        ['d0001','IT','u0001'],
        );

        DB::insert('insert into departments (dep_id, dep_name, unit_id)
        values (?,?,?)',
        ['d0002','QA','u0001'],
        );

        DB::insert('insert into departments (dep_id, dep_name, unit_id)
        values (?,?,?)',
        ['d0003','Make product','u0002'],
        );

        DB::insert('insert into departments (dep_id, dep_name, unit_id)
        values (?,?,?)',
        ['d0004','Inspection','u0002'],
        );
    }
    public function seedSec(){
        DB::insert('insert into sections (sec_id, sec_name, dep_id)
        values (?,?,?)',
        ['s0001','IT','d0001'],
        );

        DB::insert('insert into sections (sec_id, sec_name, dep_id)
        values (?,?,?)',
        ['s0002','QA','d0002'],
        );

        DB::insert('insert into sections (sec_id, sec_name, dep_id)
        values (?,?,?)',
        ['s0003','Make product','d0003'],
        );

        DB::insert('insert into sections (sec_id, sec_name, dep_id)
        values (?,?,?)',
        ['s0004','Inspection product','d0004'],
        );
    }
    public function seedUser(){
        DB::insert('insert into users (id, name, sec_id, position, email)
        values (?,?,?,?,?)',
        ['00001','Ng Văn A1','s0001','staff','ngva1@gmail.com'],
        );
        DB::insert('insert into users (id, name, sec_id, position, email, u_right)
        values (?,?,?,?,?,?)',
        ['00002','Ng Văn A2','s0001','staff','ngva2@gmail.com','01'],
        );
        DB::insert('insert into users (id, name, sec_id, position, email, u_right)
        values (?,?,?,?,?,?)',
        ['00003','Ng Văn A3','s0003','staff','ngva3@gmail.com','02'],
        );
        DB::insert('insert into users (id, name, sec_id, position, email, u_right)
        values (?,?,?,?,?,?)',
        ['00004','Ng Văn A4','s0004','staff','ngva4@gmail.com','03'],
        );
        DB::insert('insert into users (id, name, sec_id, position, email)
        values (?,?,?,?,?)',
        ['00005','Ng Văn A5','s0002','staff','ngva5@gmail.com'],
        );
        DB::insert('insert into users (id, name, position, email)
        values (?,?,?,?)',
        ['00006','Chủ tịch','chairman','ngva6@gmail.com'],
        );
        DB::insert('insert into users (id, name, position, email)
        values (?,?,?,?)',
        ['00007','Giám đốc SX','director','ngva7@gmail.com'],
        );
    }

    public function seedSys(){
        DB::insert('insert into system__owners (sys_id, sys_name)
        values (?,?)',
        ['sys0001','IT'],
        );

        DB::insert('insert into system__owners (sys_id, sys_name)
        values (?,?)',
        ['sys0002','QA'],
        );
    }
    public function seedType(){
        DB::insert('insert into request__types (type_id, type_name, sys_id)
        values (?,?,?)',
        ['t0001','IT_Approve_New_request','sys0001'],
        );
        DB::insert('insert into request__types (type_id, type_name, sys_id)
        values (?,?,?)',
        ['t0002','QA_Approve_Test_Product','sys0002'],
        );
        DB::insert('insert into request__types (type_id, type_name, sys_id)
        values (?,?,?)',
        ['t0003','QA_Approve_NC Report','sys0002'],
        );
    }
    public function seedMaster(){
        DB::insert('insert into master (sys_id, emp_id)
        values (?,?)',
        ['sys0001','00003'],
        );
        
        DB::insert('insert into master (sys_id, emp_id)
        values (?,?)',
        ['sys0002','00002'],
        );
        DB::insert('insert into master (sys_id, emp_id)
        values (?,?)',
        ['sys0002','00003'],
        );
    }
    public function seedRoute(){
        DB::insert('insert into request__routes (type_id, emp_id, LV1, LV2)
        values (?,?,?,?)',
        ['t0001','00003','00005','00004'],
        );
        DB::insert('insert into request__routes (type_id, emp_id, LV1, LV2)
        values (?,?,?,?)',
        ['t0001','00003','00006','00004'],
        );
        DB::insert('insert into request__routes (type_id, emp_id, LV1)
        values (?,?,?)',
        ['t0002','00003','00005'],
        );
        DB::insert('insert into request__routes (type_id, emp_id, LV1, LV2)
        values (?,?,?,?)',
        ['t0003','00003','00005','00004'],
        );
        
    }

    public function seedEmpRoute(){
        DB::insert('insert into emp__routes (route_id, emp_id)
        values (?,?)',
        ['1','00001'],
        );
        DB::insert('insert into emp__routes (route_id, emp_id)
        values (?,?)',
        ['3','00001'],
        );
        DB::insert('insert into emp__routes (route_id, emp_id)
        values (?,?)',
        ['4','00001'],
        );

    }
    
}
