<div class="card mt-5">
    <div class="card-body p-5">

        <div class="row">
            <h3>Limit creating purchase orders setting</h3>
        </div>


        <hr>

        <div class="row">
            <div class="col-sm-8 text-secondary">
                Setting ini untuk mengatur limit pembuatan purchase order dalam sehari. Hanay bisa di ubah oleh User
                manager, hubungi managemnt untuk permintaan penambahan limit PO
            </div>
        </div>

        <div class="row mt-3">
            <div>
                <button class="btn btn-success mb-1">Add/Edit rule</button>
            </div>
            <div>
                <table class="table table-bordered">
                    <thead>
                        <tr class="table-secondary">
                            <th class="text-center" style="width: 10%">No</th>
                            <th class="text-center" style="width: 30%">Limit</th>
                            <th class="text-center" style="width: 30%">date</th>
                            <th class="text-center" style="width: 30%">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center">{{ 1 }}</td>
                            <td>{{ $active->limit }}</td>
                            <td>
                                @if ($active->id == 1)
                                    {{ date('d/m/Y') }}
                                @else
                                    {{ $active->date }}
                                @endif

                            </td>
                            <td class="text-center">
                                @if ($active->id == 1)
                                    <span class="bg-success rounded p-1 text-white">Default</span>
                                @else
                                    <span class="bg-success rounded p-1 text-white">Activate</span>
                                @endif
                            </td>
                        </tr>
                        @foreach ($rules as $key => $rule)
                            <tr>
                                <td class="text-center">{{ $key + 2 }}</td>
                                <td>{{ $rule->limit }}</td>
                                <td>{{ $rule->date }}</td>
                                <td class="text-center">
                                    <span class="bg-warning rounded p-1 text-white">Not activate</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
