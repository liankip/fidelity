<?php

namespace App\Imports;

use App\Models\Project;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Maatwebsite\Excel\Concerns\Importable;

class TaskImport implements ToCollection, WithCalculatedFormulas
{
    use Importable;

    protected $projectId;

    public function __construct($projectId)
    {
        $this->projectId = $projectId;
    }

    protected function getProjectCode($projectId)
    {
        $project = Project::find($projectId);

        return $project->project_code;
    }

    public function collection(Collection $collection)
    {
        try {
            $projectCode = $this->getProjectCode($this->projectId);

            $defaultTaskNumber = "{$projectCode}/00/00";
            if (
                !Task::where('project_id', $this->projectId)
                    ->where('task_number', $defaultTaskNumber)
                    ->exists()
            ) {
                Task::create([
                    'project_id' => $this->projectId,
                    'task_number' => "{$projectCode}/00/00",
                    'section' => 'Consumables',
                    'number' => '0',
                    'task' => 'Task 00',
                    'bobot' => '0',
                    'earliest_start' => '0',
                    'start_date' => Carbon::now()->format('Y-m-d'),
                    'duration' => '0',
                    'earliest_finish' => '0',
                    'finish_date' => Carbon::now()->format('Y-m-d'),
                ]);
            }

            foreach ($collection as $index => $row) {
                if ($row->filter()->isNotEmpty()) {
                    if ($index == 0) {
                        continue;
                    }

                    $section = $row[0] ?? null;

                    if (empty($section)) {
                        $section = $this->lastSection;
                    } else {
                        $this->lastSection = $section;
                    }

                    $formattedSection = str_pad($section, 2, '0', STR_PAD_LEFT);

                    $number = str_pad($index, 2, '0', STR_PAD_LEFT);
                    $taskNumber = "{$projectCode}/{$formattedSection}/{$number}";

                    $startDate = !empty($row[6]) ? Date::excelToDateTimeObject(Date::stringToExcel($row[6]))->format('Y-m-d') : null;
                    $endDate = !empty($row[9]) ? Date::excelToDateTimeObject(Date::stringToExcel($row[9]))->format('Y-m-d') : null;

                    Task::create([
                        'project_id' => $this->projectId,
                        'task_number' => $taskNumber,
                        'section' => $row[1] ?? '',
                        'number' => $row[2],
                        'task' => $row[3],
                        'bobot' => $row[4],
                        'earliest_start' => $row[5],
                        'start_date' => $startDate,
                        'duration' => $row[7],
                        'earliest_finish' => $row[8],
                        'finish_date' => $endDate,
                    ]);
                }
            }
            return redirect()->route('project.task', $this->projectId, ['success' => 'Data berhasil diimport.']);
        } catch (\Exception $e) {
            //    dd($e->getMessage());
        }
    }
}
