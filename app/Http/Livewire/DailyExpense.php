<?php

namespace App\Http\Livewire;

use App\Models\DailyExpensesModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class DailyExpense extends Component
{
    use WithFileUploads, WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $expenseName, $expenseAmount, $expenseDescription, $expenseDocuments = [], $existingDocuments, $search;

    protected $rules = [
        'expenseName' => 'required',
        'expenseAmount' => 'required|numeric',
        'expenseDescription' => 'required',
        'expenseDocuments' => 'nullable|array',
        'expenseDocuments.*' => 'max:2000',
    ];

    public function render()
    {
        $expenseData = $this->getExpenseData();
        return view('livewire.daily-expense', ['expenseData' => $expenseData]);
    }

    public function getExpenseData()
    {
        if ($this->search) {
            return DailyExpensesModel::where('name', 'like', '%' . $this->search . '%')->paginate(10);
        }
        return DailyExpensesModel::paginate(10);
    }


    public function submitFunction()
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $expense = [
                'name' => $this->expenseName,
                'amount' => $this->expenseAmount,
                'description' => $this->expenseDescription,
            ];


            if ($this->expenseDocuments) {
                foreach ($this->expenseDocuments as $key => $file) {
                    $path = $file->store('expenses', 'public');

                    $expense['documents'][] = [
                        'path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ];
                }
                $expense['documents'] = json_encode($expense['documents']);
            }

            DailyExpensesModel::create($expense);
            DB::commit();
            $this->reset();

            session()->flash('success', 'Daily expense has been created successfully.');
            $this->dispatchBrowserEvent('closeModal');
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e);
            session()->flash('fail', 'Error creating daily expense.');
        }
    }

    public function update($id)
    {
        $this->validate();

        DB::beginTransaction();
        try {
            $expense = DailyExpensesModel::findOrFail($id);

            $expense->name = $this->expenseName;
            $expense->amount = $this->expenseAmount;
            $expense->description = $this->expenseDescription;

            if ($this->expenseDocuments) {
                $documents = [];
                foreach ($this->expenseDocuments as $file) {
                    $path = $file->store('expenses', 'public');
                    $documents[] = [
                        'path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                    ];
                }
                $expense->documents = json_encode($documents);
            }

            $expense->save();
            DB::commit();
            $this->reset();

            session()->flash('success', 'Daily expense has been updated successfully.');
            $this->dispatchBrowserEvent('closeUpdateModal', ['id' => $id]);
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('fail', 'Error updating daily expense.');
        }
    }

    public function delete($id)
    {
        $expense = DailyExpensesModel::findOrFail($id);
        $expense->delete();

        return redirect()->route('daily-expense')->with('success', 'Daily expense has been deleted successfully.');
    }

    public function loadExpenseData($expenseId)
    {
        $expense = DailyExpensesModel::find($expenseId);

        $this->expenseName = $expense->name;
        $this->expenseAmount = $expense->amount;
        $this->expenseDescription = $expense->description;
        $this->existingDocuments = $expense->documents;
    }

    public function unloadExpenseData()
    {
        $this->reset();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }
}
