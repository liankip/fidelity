<div>
    <h2 class="primary-color-sne">Daily Expenses</h2>


    <div class="mt-2">
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('fail'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('fail') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    <div class="card mt-5 primary-box-shadow">
        <div class="card-body">
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#exampleModal"
                wire:click="unloadExpenseData"><i class="fa-solid fa-plus"></i> Create Expenses</button>
            <input type="text" wire:model="search" class="form-control mt-2" placeholder="Search Expenses">
            <table class="table primary-box-shadow mt-2">
                <thead class="thead-light">
                    <th class="text-center border-top-left">No</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Amount</th>
                    <th class="text-center border-top-right">Action</th>
                </thead>
                <tbody>
                    @forelse ($expenseData as $expense)
                        <tr>
                            <td class="text-center">{{ $loop->iteration }}</td>
                            <td class="text-center">{{ $expense->name }}</td>
                            <td class="text-center">Rp. {{ number_format($expense->amount) }}</td>
                            <td class="text-center">
                                <button class="btn btn-warning" data-bs-toggle="modal"
                                    data-bs-target="#exampleModal-{{ $expense->id }}"
                                    wire:click="loadExpenseData({{ $expense->id }})" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="btn btn-danger" title="Delete" data-bs-toggle="modal"
                                    data-bs-target="#deleteModal-{{ $expense->id }}">
                                    <i class="fas fa-trash"></i>
                                </button>
                                <button class="btn btn-info" title="Detail" data-bs-toggle="modal"
                                    data-bs-target="#detailModal-{{ $expense->id }}"><i
                                        class="fas fa-info"></i></button>
                            </td>
                        </tr>

                        {{-- Update Modal --}}
                        <div class="modal fade" id="exampleModal-{{ $expense->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel-{{ $expense->id }}" aria-hidden="true"
                            wire:ignore.self>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Update Daily Expense
                                            {{ $expense->name }}
                                        </h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="expenseName">Name</label>
                                            <input type="text" class="form-control" wire:model="expenseName"
                                                placeholder="Enter expense name">
                                            @error('expenseName')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="expenseAmount">Amount</label>
                                            <input type="number" id="expenseAmount" class="form-control"
                                                wire:model="expenseAmount" placeholder="Enter amount">
                                            @error('expenseAmount')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="expenseDescription">Description</label>
                                            <textarea id="expenseDescription" class="form-control" wire:model="expenseDescription" placeholder="Enter description"></textarea>
                                            @error('expenseDescription')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>
                                        <div class="form-group">
                                            <label for="expenseFiles">Documents</label>
                                            <input type="file" id="expenseFiles" class="form-control"
                                                wire:model="expenseDocuments" multiple>

                                            @if (json_decode($existingDocuments) != null)
                                                @foreach (json_decode($existingDocuments) as $document)
                                                    <li>
                                                        <small>
                                                            <a href="{{ asset('storage/' . $document->path) }}"
                                                                target="_blank">
                                                                {{ $document->file_name }}</a>
                                                        </small>
                                                    </li>
                                                @endforeach
                                            @endif

                                            @error('expenseDocuments')
                                                <span class="text-danger">{{ $message }}</span>
                                            @enderror
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary" wire:loading.attr="disabled"
                                            wire:click="update({{ $expense->id }})">Save
                                            changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Modal -->
                        <div class="modal fade" id="detailModal-{{ $expense->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Detail
                                            {{ $expense->name }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <label for="detailName">Name</label>
                                            <p id="detailName" class="form-control">{{ $expense->name }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="detailAmount">Amount</label>
                                            <p id="detailAmount" class="form-control">Rp.
                                                {{ number_format($expense->amount) }}
                                            </p>
                                        </div>
                                        <div class="form-group">
                                            <label for="detailDescription">Description</label>
                                            <p id="detailDescription" class="form-control">
                                                {{ $expense->description }}</p>
                                        </div>
                                        <div class="form-group">
                                            <label for="detailDocuments">Documents</label>
                                            @if (json_decode($expense->documents) != null)
                                                <ul id="detailDocuments" class="list-unstyled">
                                                    @foreach (json_decode($expense->documents) as $document)
                                                        <li>
                                                            <a href="{{ asset('storage/' . $document->path) }}"
                                                                target="_blank">
                                                                {{ $document->file_name }}
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p id="detailDocuments" class="form-control">No documents
                                                    available</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Delete Modal --}}
                        <div class="modal fade" id="deleteModal-{{ $expense->id }}" tabindex="-1"
                            aria-labelledby="exampleModalLabel" aria-hidden="true" wire:ignore.self>
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="exampleModalLabel">Delete
                                            {{ $expense->name }}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <p>Are you sure you want to delete <strong>{{ $expense->name }}</strong>?
                                            </p>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-danger"
                                            wire:click="delete({{ $expense->id }})">Delete</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @empty
                        <tr>
                            <td colspan="4" class="text-center">No Data</td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>

        <div class="mt-4 d-flex justify-content-end">
            {{ $expenseData->links() }}
        </div>
    </div>

    <!-- Create Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"
        wire:ignore.self>
        <form class="modal-dialog" wire:submit.prevent="submitFunction" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Daily Expense</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="expenseName">Name</label>
                        <input type="text" class="form-control" wire:model="expenseName"
                            placeholder="Enter expense name">
                        @error('expenseName')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="expenseAmount">Amount</label>
                        <input type="number" id="expenseAmount" class="form-control" wire:model="expenseAmount"
                            placeholder="Enter amount">
                        @error('expenseAmount')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="expenseDescription">Description</label>
                        <textarea id="expenseDescription" class="form-control" wire:model="expenseDescription"
                            placeholder="Enter description"></textarea>
                        @error('expenseDescription')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="expenseFiles">Documents</label>
                        <input type="file" id="expenseFiles" class="form-control" wire:model="expenseDocuments"
                            multiple>
                        @error('expenseDocuments')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">Save
                        changes</button>
                </div>
            </div>
        </form>
    </div>

    <script>
        window.addEventListener('closeModal', event => {
            $("#exampleModal").modal('hide');
        })

        window.addEventListener('closeUpdateModal', (event) => {
            const id = event.detail.id;
            $("#exampleModal-" + id).modal('hide');
        });
    </script>
</div>
