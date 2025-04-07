<?php

namespace Database\Seeders;

use App\Helpers\NumberToAAConverter;
use App\Models\Project;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //for update all project_code with code
        $project = Project::all();
        foreach ($project as $key => $value) {
            Project::where("id",$value->id)->update([
                "project_code" => NumberToAAConverter::format($value->id)
            ]);
        }

    }
}
