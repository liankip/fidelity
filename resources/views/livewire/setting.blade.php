<div class="container mt-2">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2>Settings</h2>
                <hr>
            </div>
        </div>
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

    @livewire('setting.notification-emails')

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>BOQ Implementation</h3>
            </div>

            <div class="px-5">
                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Global</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengintegrasikan BOQ dengan halaman PR
                                            secara
                                            global, atau matikan untuk memilih secara manual.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="global_boq" wire:click="global_boq">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Multiple Approval</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval pada
                                            halaman
                                            BOQ.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="multiple_approval" wire:click="multiple_approval">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Multiple Approval Barang K3</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval barang
                                            oleh K3 pada halaman
                                            pengajuan BOQ.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="multiple_k3_approval" wire:click="multiple_k3_approval">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if (!$global_boq)
                    <div class="row mt-3">
                        <table>
                            <tbody>
                                <tr>
                                    <td style="width: 90%">
                                        <strong>Individual</strong>
                                        <div class="row">
                                            <div class="col-sm-8 text-secondary">
                                                Aktifkan project mana saja yang ingin anda implementasikan dengan BOQ.
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                @endif

                @if (!$global_boq)
                    <div class="row mt-3">
                        <table class="table table-borderless table-striped">
                            <thead>
                                <tr class="table-primary">
                                    <th style="width: 30%">Project Name</th>
                                    <th style="width: 30%">Company Name</th>
                                    <th style="width: 30%">PIC</th>
                                    <th class="text-center" style="width: 10%">BOQ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($projects as $data_boq)
                                    <tr>
                                        <td>{{ $data_boq->name }}</td>
                                        <td>{{ $data_boq->company_name }}</td>
                                        <td>{{ $data_boq->pic }}</td>
                                        <td class="text-center">
                                            <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input text-secondary" type="checkbox"
                                                    role="switch" wire:model="individual_boq.{{ $data_boq->id }}"
                                                    wire:click="individual_boq({{ $data_boq->id }})"
                                                    @if ($global_boq) disabled @endif>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>MOM Implementation</h3>
            </div>

            <div class="px-5">
                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Multiple Approval MOM</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval MOM pada
                                            halaman
                                            pengajuan MOM.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="multiple_mom_approval" wire:click="multiple_mom_approval">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>Item Implementation</h3>
            </div>

            <div class="px-5">
                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Multiple Approval Item</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval Item pada
                                            halaman
                                            pengajuan Item.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="multiple_item_approval" wire:click="multiple_item_approval">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>PR Implementation</h3>
            </div>

            <div class="px-5">
                <div class="row mt-3">
                    <table>
                        <tbody>
                            <tr>
                                <td style="width: 90%">
                                    <strong>Multiple Approval PR</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval PR pada
                                            halaman
                                            pengajuan PR.
                                        </div>
                                    </div>
                                </td>
                                <td style="width: 10%" class="text-center">
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                            wire:model="multiple_pr_approval" wire:click="multiple_pr_approval">
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>Pengajuan Cuti</h3>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 90%">
                                <strong>Pengajuan cuti H-7</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Aktifkan fitur ini untuk mengatur waktu pengajuan cuti minimal H-7
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%" class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                        wire:model="leave_request_limit" wire:click="leave_request_limit_switch">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>PO Implementation</h3>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 90%">
                                <strong>Multiple Approval PO</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval PO pada
                                        halaman
                                        pengajuan PO.
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%" class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                        wire:model="multiple_po_approval" wire:click="multiple_po_approval">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 90%">
                                <strong>Limit PO Creation</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Berapa jumlah PO yang dapat dibuat oleh user dalam 1 hari.
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%" class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                        wire:model="po_limit_switch" wire:click="po_limit_switch">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 80%"></td>
                            <td style="width: 20%">
                                <div class="d-flex justify-content-center">
                                    @if (!$po_limit_switch)
                                        <input class="form-control" type="text" value="Unlimited" disabled
                                            readonly>
                                    @else
                                        <div class="text-left">
                                            <input
                                                class="form-control @error('po_limit') is-invalid text-danger @enderror"
                                                type="number" wire:model="po_limit"
                                                wire:change.defer="update_po_limit">
                                            @error('po_limit')
                                                <div class="text-danger text-left">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>WBS Implementation</h3>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 90%">
                                <strong>Multiple Approval WBS Revision</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval WBS Revision
                                        pada halaman
                                        pengajuan WBS Revision.
                            </td>
                            <td style="width: 10%" class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                        wire:model="multiple_wbs_revision_approval"
                                        wire:click="multiple_wbs_revision_approval">
                                </div>

                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 90%">
                                <strong>Multiple Approval WBS</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Aktifkan fitur ini jika anda ingin mengaktifkan multiple approval WBS pada
                                        halaman
                                        pengajuan WBS.
                                    </div>
                                </div>
                            </td>
                            <td style="width: 10%" class="text-center">
                                <div class="form-check form-switch d-flex justify-content-center">
                                    <input class="form-check-input text-secondary" type="checkbox" role="switch"
                                        wire:model="multiple_wbs_approval" wire:click="multiple_wbs_approval">
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card mt-5">
        <div class="card-body p-5">

            <div class="row">
                <h3>Latest Code Number</h3>
            </div>

            <div class="row mt-3 px-5">
                <table>
                    <tbody>
                        <tr>
                            <td style="width: 80%">
                                <strong>Purchase Request Number</strong>
                                <div class="row">
                                    <div class="col-sm-8 text-secondary">
                                        Nomor PR Terakhir (Nomor selanjunya adalah Nomor sekarang ditambah 1)
                                    </div>
                                </div>
                            </td>
                            <td style="width: 20%">
                                <div class="d-flex justify-content-center">
                                    <div class="text-left">
                                        <input
                                            class="form-control @error('pr_number') is-invalid text-danger @enderror"
                                            type="number" wire:model="pr_number"
                                            wire:change.defer="update_pr_number">
                                        @error('pr_number')
                                            <div class="text-danger text-left">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 80%">
                                <div class="mt-3">
                                    <strong>Purchase Order Number</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Nomor PO Terakhir (Nomor selanjunya adalah Nomor sekarang ditambah 1)
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 20%">
                                <div class="d-flex justify-content-center mt-3">
                                    <div class="text-left">
                                        <input
                                            class="form-control @error('po_number') is-invalid text-danger @enderror"
                                            type="number" wire:model="po_number"
                                            wire:change.defer="update_po_number">
                                        @error('po_number')
                                            <div class="text-danger text-left">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 80%">
                                <div class="mt-3">
                                    <strong>Voucher Number</strong>
                                    <div class="row">
                                        <div class="col-sm-8 text-secondary">
                                            Nomor Voucher Terakhir (Nomor selanjunya adalah Nomor sekarang ditambah 1)
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td style="width: 20%">
                                <div class="d-flex justify-content-center mt-3">
                                    <div class="text-left">
                                        <input
                                            class="form-control @error('voucher_number') is-invalid text-danger @enderror"
                                            type="number" name="voucher_number" wire:model="voucher_number">
                                        @error('voucher_number')
                                            <div class="text-danger text-left">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('input[name="voucher_number"]').on('blur', () => {
                // call update voucher number function
                @this.
                call('update_voucher_number')
            })
        })
    </script>
    {{-- @livewire("settings.limit.po.setting") --}}
    {{-- <livewire:settings.limit-po-setting> --}}
</div>
