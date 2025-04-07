<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="primary-color-sne">General Purchase</h2>
            </div>
            @foreach (['danger', 'warning', 'success', 'info'] as $key)
                @if (Session::has($key))
                    <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                        {{ Session::get($key) }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                        </button>
                    </div>
                @endif
            @endforeach
        </div>
    </div>

    <div class="card primary-box-shadow p-4">
        <h5>General Purchase Options</h5>
        <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault"  wire:model="bulkOptions" value="project">
            <label class="form-check-label" for="flexRadioDefault1">
              Bulk Purchase
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="flexRadioDefault" wire:model="bulkOptions" value="without_project">
            <label class="form-check-label" for="flexRadioDefault2">
              Stok persediaan
            </label>
          </div>
    </div>

    
    <div class="card primary-box-shadow mt-3">
        <div class="card-body">
            @if($bulkOptions == 'without_project')
                <livewire:bulk-items />
            @else
            <table class="table primary-box-shadow">
                <thead class="thead-light">
                    <tr class="table-secondary">
                        <th class="text-center align-items-center border-top-left" width="5%">No</th>
                        <th class="text-center" width="15%">Project</th>
                        <th class="text-center" width="15%">Company Name</th>
                        <th class="text-center" width="10%">PIC</th>
                        <th class="text-center">Complete Address</th>
                        @if ($status === 'Draft')
                            <th class="text-center">Created By</th>
                        @endif
                        <th class="text-center not-export border-top-right" width="7%">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($projects as $key => $project)
                        <tr>
                            <td class="text-center">{{ $key + 1 }}</td>
                            <td>
                                <div>
                                    <strong>Name:</strong> {{ $project->name }}
                                    {{ $project->po_number ? '- PO ' . $project->po_number : '' }}
                                </div>
                                <div><strong>Code:</strong> {{ $project->project_code }}</div>
                            </td>
                            <td>{{ $project->company_name }}</td>
                            <td>
                                @php
                                    $pic = json_decode($project->pic);
                                @endphp

                                @if (json_last_error() === JSON_ERROR_NONE)
                                    <ul>
                                        @foreach ($pic as $person)
                                            <li>{{ $person->value }}</li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p>{{ $project->pic }}</p>
                                @endif
                            </td>
                            <td>
                                <div>
                                    <strong>Address:</strong>
                                    {{ $project->address }}
                                    {{ $project->city }}
                                    {{ $project->province }}
                                    {{ $project->post_code }}
                                </div>
                            </td>
                            @if ($status === 'Draft')
                                <td class="text-center">{{ $project->createdby->name }}</td>
                            @endif
                            <td class="text-left">
                                <div class="mt-1">
                                    <a class="w-100 btn btn-success btn-sm"
                                        href="{{ route('bulk-purchase.boq', $project->id) }}">BOQ</a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </div>
    
</div>
