<?php

namespace App\Http\Livewire;

use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Attendance extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $data_attendances = [];
    public $search, $selectedDate;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
    }

    public function render()
    {
        $date = Carbon::create($this->selectedDate)->format('d');
        $month = Carbon::create($this->selectedDate)->format('m');
        $year = Carbon::create($this->selectedDate)->format('Y');

        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                CURLOPT_URL => 'https://telegram-bot.satrianusa.group/api/telegram/update-attendance/' . $date . '/' . $month . '/' . $year,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
                CURLOPT_HTTPHEADER => array(
                    'Accept: 7qcX3AuK5mm23KW5TiKCGkDMKkj1354027i4y38HTxUCyCqpBz4c27kAhjBS74837O7QesJEW41uVBFqL00s4O66z1F9KzCL7M7kDduayQ7q83OSZm758EKE'
                ),
            )
        );

        $curl_attendance = json_decode(curl_exec($curl), true);
        curl_close($curl);
        $this->data_attendances = isset($curl_attendance['data']) ? $curl_attendance['data'] : [];


        $collection = collect($this->data_attendances);
        $search_results = $collection->filter(function ($item) {
            return strpos(strtolower($item['employee']['name']), strtolower($this->search)) !== false ||
                strpos(strtolower($item['status']), strtolower($this->search)) !== false;
        })->all();


        $locations = [];

        foreach ($this->data_attendances as $item) {
            $latitude = $item['latitude'];
            $longitude = $item['longitude'];

            $locations[] = ['lat' => (float) $latitude, 'lng' => (float) $longitude];
        }

        return view('livewire.attendance', [
            'attendances' => $search_results,
            'locations' => $locations,
        ]);
    }
}
