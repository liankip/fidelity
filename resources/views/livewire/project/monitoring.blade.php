<div class="mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2 class="text-black">Monitoring project</h2>
                <h4 class="text-secondary"><strong>{{ $project->name }}</strong></h4>
            </div>
            <hr>

            <div class="d-flex justify-content-between mb-2">
                <button class="btn btn-success" wire:click='downloadexcel'>Export to excel</button>
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

            <div>
                <table class="table table-sm table-bordered">
                    <tr>
                        <th class="text-center">No.</th>
                        <th class="text-center">nama</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Qty BOQ</th>
                        <th class="text-center">Qty PR</th>
                        <th class="text-center">Qty PO</th>
                        <th class="text-center">Total</th>
                    </tr>
                    @php
                        $total = 0;
                    @endphp
                    @foreach ($items as $key => $value)
                        <tr>
                            <td class="text-center fw-bold">{{ $key + 1 }}</td>
                            <td class="px-2">{{ $value->name }}</td>
                            <td class="px-2">{{ $value->unit_name }}</td>
                            <td class="text-end px-2">{{ str_replace(',00', '', number_format($value->qty, 2,',', '.')) }}</td>
                            <td class="text-end px-2">{{ str_replace(',00', '', number_format($value->qty_pr, 2,',', '.')) }}</td>
                            <td class="text-end px-2">{{ str_replace(',00', '', number_format($value->qty_po, 2, ',', '.')) }}</td>
                            <td class="px-2">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        Rp.
                                    </div>
                                    <div>
                                        {{ str_replace(',00', '', number_format($value->amount_po, 2, ',', '.')) }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @php
                            $total += $value->amount_po;
                        @endphp
                    @endforeach
                    <tr>
                        <td colspan="6">

                        </td>
                        <td class="fw-bold px-2">
                            <div class="d-flex justify-content-between">
                                <div>
                                    Rp.
                                </div>
                                <div>
                                    {{ str_replace(',00', '', number_format($total, 2, ',', '.')) }}
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>


    {{-- <div>
        <strong>Note:</strong>
        <div> <strong>*</strong> <em>Harga estimasi didapat dari harga terendah dari supplier yang terdaftar di sistem.</em></div>
        <div> <strong>**</strong> <em>Harga total estimasi didapat dari harga terendah dari supplier yang terdaftar di sistem dikali quantity.</em></div>
    </div> --}}
</div>



