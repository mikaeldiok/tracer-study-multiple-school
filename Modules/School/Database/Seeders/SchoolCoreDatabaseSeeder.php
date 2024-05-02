<?php

namespace Modules\School\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Entities\Unit;
use Modules\School\Entities\Core;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon as Carbon;

class SchoolCoreDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Schema::disableForeignKeyConstraints();

        $faker = \Faker\Factory::create();

        // Add the master administrator, user id of 1df
        $units = [
            [
                'name'              => "KB/TK",
                'level'              => "1",
            ],
            [
                'name'              => "SD",
                'level'              => "2",
            ],
            [
                'name'              => "SMP",
                'level'              => "3",
            ],
            [
                'name'              => "SMA",
                'level'              => "4",
            ],
            [
                'name'              => "SMK",
                'level'              => "5",
            ],
            [
                'name'              => "Kuliah",
                'level'              => "98",
            ],
            [
                'name'              => "Bekerja",
                'level'              => "99",
            ],
        ];

        foreach ($units as $unit) {
            $unitCreate = Unit::firstOrCreate($unit);
        }

        Schema::enableForeignKeyConstraints();
    }
}
