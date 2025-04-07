<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CriticalTaskExport implements FromCollection, WithHeadings, WithStyles, ShouldAutoSize, WithStrictNullComparison
{
    protected $projectId;
    protected $totalBobot = 0;
    protected $totalBobotCost = 0;

    public function __construct($paramId)
    {
        $this->projectId = $paramId;
    }

    public function collection()
    {
        $taskData = Task::where('project_id', $this->projectId)
            ->orderBy('section', 'asc')
            ->orderBy('slack', 'asc')
            ->get();

        $filteredTaskData = $taskData->filter(function ($task) use ($taskData) {
            $parentType = $taskData->where('id', $task->section)->first()?->type;
            return (is_numeric($task->section) || $task->section === '' || $task->section === null) && $parentType !== 'task';
        });

        $iteration = 0;
        $previousSection = null;

        $groupedData = $filteredTaskData->groupBy('section')->sortKeys();

        $data = collect();

        foreach ($groupedData as $section => $tasks) {
            if ($section == '') {
                $tasks = $tasks->sortByDesc(function ($task) {
                    return $task->task == 'Consumables';
                })->sortBy('id');
            }

            $sectionName = is_numeric($section)
                ? $taskData->where('id', $section)->first()?->task
                : $section;

            foreach ($tasks as $task) {
                $iteration++;
                $this->totalBobot += $task->bobot;
                $this->totalBobotCost += $task->bobot_cost;

                $data->push([
                    'No' => $iteration,
                    'Section' => $previousSection !== $sectionName ? $sectionName : '',
                    'Task' => $task->task,
                    'Bobot' => $task->bobot,
                    'Bobot Cost' => $task->bobot_cost,
                    'Duration' => $task->duration,
                    'Start Date' => $task->start_date,
                    'Earliest Start' => $task->earliest_start,
                    'Earliest Finish' => $task->earliest_finish,
                    'Latest Start' => $task->latest_start,
                    'Latest Finish' => $task->latest_finish,
                    'Finish Date' => $task->finish_date,
                    'Slack' => $task->slack,
                ]);

                $previousSection = $sectionName;
            }
        }

        // Add total row at the end
        $data->push([
            'No' => '',
            'Section' => '',
            'Task' => 'Total',
            'Bobot' => $this->totalBobot,
            'Bobot Cost' => $this->totalBobotCost,
            'Duration' => '',
            'Start Date' => '',
            'Earliest Start' => '',
            'Earliest Finish' => '',
            'Latest Start' => '',
            'Latest Finish' => '',
            'Finish Date' => '',
            'Slack' => '',
        ]);

        return $data;
    }



    public function headings(): array
    {
        return [
            'No',
            'Section',
            'Task',
            'Bobot',
            'Bobot Cost',
            'Duration',
            'Start Date',
            'Earliest Start',
            'Earliest Finish',
            'Latest Start',
            'Latest Finish',
            'Finish Date',
            'Slack',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $lastRow = count($sheet->toArray());

        $sheet->mergeCells("A{$lastRow}:C{$lastRow}");

        return [
            1 => ['font' => ['bold' => true]],
            $lastRow => ['font' => ['bold' => true]],
            'A:L' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
