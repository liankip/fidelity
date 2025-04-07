<div>
    <div class="card primary-box-shadow">
        <div class="card-body">
            <table class="table primary-box-shadow">
                <tr class="thead-light">
                    <th class="border-top-left">Action</th>
                    <th>Supplier Name</th>
                    <th>PIC</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Address</th>
                    <th>City</th>
                    <th>Province</th>
                    <th>Post Code</th>
                    <th class="border-top-right" width="10%">
                        @if(auth()->user()->can(\App\Permissions\Permission::APPROVE_SUPPLIER))
                            Action
                        @else
                            Status
                        @endif
                    </th>
                </tr>
                @forelse ($suppliers as $key => $supplier)
                    <tr>
                        <td>
                            <a href="{{ route('suppliers.edit', $supplier->id) }}"
                               class="btn btn-primary btn-sm">Edit</a>
                        </td>
                        <td>
                            <a href="{{ route('suppliers.show', [$supplier->id, 'need-approval' => true]) }}">{{ $supplier->name }}</a>
                        </td>
                        <td>{{ $supplier->pic }}</td>
                        <td>{{ $supplier->email }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->address }}</td>
                        <td>{{ $supplier->city }}</td>
                        <td>{{ $supplier->province }}</td>
                        <td>{{ $supplier->post_code }}</td>
                        <td class="d-flex gap-2">
                            @if(auth()->user()->can(\App\Permissions\Permission::APPROVE_SUPPLIER))
                                <button wire:click="approve({{ $supplier->id }})" class="btn btn-success btn-sm">Approve
                                </button>
                                <button wire:click="reject({{ $supplier->id }})" class="btn btn-danger btn-sm">Reject
                                </button>
                            @else
                                <p>
                                    Waiting for approval
                                </p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">
                            No Data to approve
                        </td>
                    </tr>
                @endforelse
            </table>

        </div>
    </div>
    <script>
        const url = new URL(window.location.href);
        const params = new URLSearchParams(url.search);
        const activeTab = params.get('tab');

        if (activeTab === 'need-approval') {
            $('#need-approval-tab').addClass('active');
            $('#all-suppliers-tab').removeClass('active');
            $('#need-approval').addClass('show active');
            $('#all-suppliers').removeClass('show active');

        }

    </script>
</div>
