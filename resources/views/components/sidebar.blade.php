<div id="sidebar" class="active">
    <div class="sidebar-wrapper active">
        <div class="position-relative">
            <div class="d-flex align-items-center">
                <div style="margin-left: 2rem; margin-top: 2rem;">
                    <a href="{{ url('/') }}">
                        @if (env('COMPANY') == 'SNE')
                            <img height="40px" src="{{ asset('logo/sne.png') }}">
                        @elseif(env('COMPANY') == 'NADIC')
                            <img height="40px" src="{{ asset('logo/nadic.png') }}">
                        @elseif(env('COMPANY') == 'SGE')
                            <img height="40px" src="{{ asset('logo/sge.png') }}">
                        @elseif(env('COMPANY') == 'SMI')
                            <img height="40px" src="{{ asset('logo/smi.png') }}">
                        @else
                            <img height="40px" src="{{ asset('logo/dev.png') }}">
                        @endif
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">

                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="display: none">
                        <label class="form-check-label"></label>
                    </div>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="fas fa-x  fs-3"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu pb-5">
            <ul class="menu">
                <x-navlink.navlink-basic name="Dashboard" :href="url('/')">
                    <x-slot:icon>
                        <i class="fas fa-home"></i>
                    </x-slot:icon>
                </x-navlink.navlink-basic>
                @if (auth()->user()->hasRole(\App\Roles\Role::ADMIN_LAPANGAN) ||
                        auth()->user()->hasRole(\App\Roles\Role::K3) ||
                        auth()->user()->hasRole(\App\Roles\Role::ADMIN_GUDANG))
                    @if (Auth::user()->can('approve-pr'))
                        <x-navlink.navlink-dropdown name="Approval">
                            <x-slot:icon>
                                <i class="fas fa-check-to-slot"></i>
                            </x-slot:icon>
                            <x-navlink.navlink-dropdown-item :to="url('pr-waiting-list')">
                                {{ __('PR Waiting List') }}
                            </x-navlink.navlink-dropdown-item>
                        </x-navlink.navlink-dropdown>
                    @endif
                    <li class="sidebar-item has-sub">
                        <a href="#" class='sidebar-link'>
                            <div style="width: 25px;">
                                <i class="fas fa-database"></i>
                            </div>
                            <span>Master Data</span>
                        </a>
                        <ul class="submenu">
                            <li class="submenu-item">
                                <a href
                                   ="{{ url('projects') }}">
                                    {{ __('Project') }}
                                </a>
                                @if (auth()->user()->hasRole(\App\Roles\Role::ADMIN_LAPANGAN))
                                    <x-navlink.navlink-dropdown-item :to="url('items')">
                                        {{ __('Item') }}
                                    </x-navlink.navlink-dropdown-item>
                                @endif
                                <x-navlink.navlink-dropdown-item :to="url('prices')">
                                    {{ __('Price') }}
                                </x-navlink.navlink-dropdown-item>
                                <x-navlink.navlink-dropdown-item :to="url('suppliers')">
                                    {{ __('Supplier') }}
                                </x-navlink.navlink-dropdown-item>
                                <x-navlink.navlink-dropdown-item :to="url('inventory')">
                                    {{ __('Inventory') }}
                                </x-navlink.navlink-dropdown-item>
                            </li>
                        </ul>
                    </li>
                    @if (auth()->user()->hasRole(\App\Roles\Role::ADMIN_LAPANGAN) || auth()->user()->hasRole(\App\Roles\Role::PAYABLE))
                        <x-navlink.navlink-dropdown name="Purchases">
                            <x-slot:icon>
                                <i class="fas fa-cart-shopping"></i>
                            </x-slot:icon>
                            @hasanyrole('it|top-manager|purchasing|adminlapangan|manager|lapangan|admin_2')
                                <x-navlink.navlink-dropdown-item :to="url('purchase-requests')">
                                    {{ __('Purchase Request') }}
                                </x-navlink.navlink-dropdown-item>
                            @endhasanyrole
                            @if (auth()->user()->hasRole(\App\Roles\Role::PURCHASING) ||
                                    auth()->user()->hasPermissionTo('create-po') ||
                                    auth()->user()->hasRole(\App\Roles\Role::PAYABLE))
                                <x-navlink.navlink-dropdown-item :to="url('purchase-orders')">
                                    {{ __('Purchase Order') }}
                                </x-navlink.navlink-dropdown-item>
                                <x-navlink.navlink-dropdown-item :to="url('office-expense')">
                                    {{ __('Office Expense') }}
                                </x-navlink.navlink-dropdown-item>

                                <x-navlink.navlink-dropdown-item :to="url('capex-expense')">
                                    {{ __('Capex Expense') }}
                                </x-navlink.navlink-dropdown-item>
                            @endif
                        </x-navlink.navlink-dropdown>
                        <x-navlink.navlink-dropdown name="Manufacture">
                            <x-slot:icon>
                                <i class="fas fa-store"></i>
                            </x-slot:icon>

                            <x-navlink.navlink-dropdown-item :to="url('product/customer')">
                                {{ __('Customer') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/sku')">
                                {{ __('SKU') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/sales')">
                                {{ __('Sales') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/work-order')">
                                {{ __('Work Order') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/raw-material')">
                                {{ __('Raw Material') }}
                            </x-navlink.navlink-dropdown-item>

                        </x-navlink.navlink-dropdown>
                    @endif
                    {{--                    <x-navlink.navlink-dropdown name="Overtime"> --}}
                    {{--                        <x-slot:icon> --}}
                    {{--                            <i class="fas fa-clock"></i> --}}
                    {{--                        </x-slot:icon> --}}
                    {{--                        <x-navlink.navlink-dropdown-item :to="route('overtime-request.index')"> --}}
                    {{--                            {{ __('Overtime Request') }} --}}
                    {{--                        </x-navlink.navlink-dropdown-item> --}}
                    {{--                        <x-navlink.navlink-dropdown-item :to="route('overtime.index')"> --}}
                    {{--                            {{ __('Overtime Form') }} --}}
                    {{--                        </x-navlink.navlink-dropdown-item> --}}
                    {{--                    </x-navlink.navlink-dropdown> --}}
                    <x-navlink.navlink-dropdown name="Forms">
                        <x-slot:icon>
                            <i class="fa-solid fa-table-list"></i>
                        </x-slot:icon>
                        <x-navlink.navlink-dropdown-item :to="route('hrd.leaverequest')">
                            {{ __('Leave Form') }}
                        </x-navlink.navlink-dropdown-item>
                    </x-navlink.navlink-dropdown>

                    @hasanyrole('k3')
                        <x-navlink.navlink-basic name="Database Employee" :href="route('hrd.alluser')">
                            <x-slot:icon>
                                <i class="fa-solid fa-server"></i>
                            </x-slot:icon>
                        </x-navlink.navlink-basic>
                        {{--                        <x-navlink.navlink-basic name="Attendance" :href="route('hrd.attendance')"> --}}
                        {{--                            <x-slot:icon> --}}
                        {{--                                <i class="fa-solid fa-clipboard-user"></i> --}}
                        {{--                            </x-slot:icon> --}}
                        {{--                        </x-navlink.navlink-basic> --}}
                    @endhasanyrole
                    @hasanyrole('k3')
                        {{--                        <x-navlink.navlink-dropdown style="background: red" name="HSE Compliance"> --}}
                        {{--                            <x-slot:icon> --}}
                        {{--                                <i class="fa-solid fa-file-shield"></i> --}}
                        {{--                            </x-slot:icon> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('jsa-view.index')"> --}}
                        {{--                                {{ __('JSA') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.hiradc')"> --}}
                        {{--                                {{ __('HIRADC') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.ibpr')"> --}}
                        {{--                                {{ __('IBPR') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('internal.index')"> --}}
                        {{--                                {{ __('Internal Training') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('service.index')"> --}}
                        {{--                                {{ __('Checklist Service') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.workInstruction')"> --}}
                        {{--                                {{ __('Work Instruction') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('meeting.index')"> --}}
                        {{--                                {{ __('Meeting Form') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('msds.index')"> --}}
                        {{--                                {{ __('MSDS') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('permit.index')"> --}}
                        {{--                                {{ __('Work Permit') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.sop')"> --}}
                        {{--                                {{ __('SOP Documents') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.workInduction')"> --}}
                        {{--                                {{ __('Safety Inductions') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.hsePolicy')"> --}}
                        {{--                                {{ __('HSE Policy') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.otp')"> --}}
                        {{--                                {{ __('OTP') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.mcu')"> --}}
                        {{--                                {{ __('Medical Check Up') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.apd')"> --}}
                        {{--                                {{ __('APD') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.apd-inspection')"> --}}
                        {{--                                {{ __('APD Inspection') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.equipment-inspection')"> --}}
                        {{--                                {{ __('Equip Inspection') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('csms.index')"> --}}
                        {{--                                {{ __('CSMS') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('safety-talk.index')"> --}}
                        {{--                                {{ __('Safety Talk') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                        </x-navlink.navlink-dropdown> --}}
                    @endhasanyrole
                @else
                    @hasanyrole('it|top-manager|manager|purchasing|finance|admin_2')
                        <x-navlink.navlink-dropdown name="Approval">
                            <x-slot:icon>
                                <i class="fas fa-check-to-slot"></i>
                            </x-slot:icon>
                            <x-navlink.navlink-dropdown-item :to="url('aprv_waitinglists')">
                                {{ __('PO Waiting List') }}
                            </x-navlink.navlink-dropdown-item>
                            @if (Auth::user()->can('approve-pr'))
                                <x-navlink.navlink-dropdown-item :to="url('pr-waiting-list')">
                                    {{ __('PR Waiting List') }}
                                </x-navlink.navlink-dropdown-item>
                            @endif
                            <x-navlink.navlink-dropdown-item :to="url('payment-submission-approval')">
                                {{ __('Payment Submission Waiting List') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('approval-histories')">
                                {{ __('History') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('task-approval.index')">
                                {{ __('WBS Submission') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('task-revision-approval.index')">
                                {{ __('WBS Revision') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('minutes-of-meeting-approval.index')">
                                {{ __('MoM') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('office-expense-approval.index')">
                                {{ __('Office Expense') }}
                            </x-navlink.navlink-dropdown-item>
                        </x-navlink.navlink-dropdown>
                    @endhasanyrole
                    <x-navlink.navlink-dropdown name="Log History">
                        <x-slot:icon>
                            <i class="fas fa-history"></i>
                        </x-slot:icon>
                        <x-navlink.navlink-dropdown-item :to="route('log-purchase')">
                            {{ __('History Purchase') }}
                        </x-navlink.navlink-dropdown-item>
                        <x-navlink.navlink-dropdown-item :to="url('log.payment')">
                            {{ __('History Payment') }}
                        </x-navlink.navlink-dropdown-item>
                    </x-navlink.navlink-dropdown>
                    <x-navlink.navlink-dropdown name="Purchases">
                        <x-slot:icon>
                            <i class="fas fa-cart-shopping"></i>
                        </x-slot:icon>
                        @hasanyrole('it|top-manager|purchasing|adminlapangan|manager|lapangan|admin_2')
                            <x-navlink.navlink-dropdown-item :to="url('purchase-requests')">
                                {{ __('Purchase Request') }}
                            </x-navlink.navlink-dropdown-item>
                        @endhasanyrole

                        @hasanyrole('it|top-manager|purchasing|adminlapangan|admin-gudang|manager|lapangan|admin_2|finance|payable')
                            <x-navlink.navlink-dropdown-item :to="url('purchase-orders')">
                                {{ __('Purchase Order') }}
                            </x-navlink.navlink-dropdown-item>
                        @endhasanyrole

                        @hasanyrole('it|top-manager|purchasing|adminlapangan|admin-gudang|manager|lapangan|admin_2|finance|payable')
                            <x-navlink.navlink-dropdown-item :to="url('general-purchase')">
                                {{ __('General Purchase') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('office-expense')">
                                {{ __('Office Expense') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('capex-expense')">
                                {{ __('Capex Expense') }}
                            </x-navlink.navlink-dropdown-item>
                        @endhasanyrole
                    </x-navlink.navlink-dropdown>
                    @hasanyrole('it|top-manager|manager|purchasing|adminlapangan|finance|admin_2|admin-gudang')
                        <x-navlink.navlink-dropdown name="Master Data">
                            <x-slot:icon>
                                <i class="fas fa-database"></i>
                            </x-slot:icon>
                            <x-navlink.navlink-dropdown-item :to="url('items')">
                                {{ __('Item') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="url('delivery_services')">
                                {{ __('Jasa Pengiriman') }}
                            </x-navlink.navlink-dropdown-item>
                            {{--                            <x-navlink.navlink-dropdown-item :to="url('event_types')"> --}}
                            {{--                                {{ __('Jenis Notifikasi') }} --}}
                            {{--                            </x-navlink.navlink-dropdown-item> --}}
                            <x-navlink.navlink-dropdown-item :to="url('projects')">
                                {{ __('Project') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="url('prices')">
                                {{ __('Price') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="url('suppliers')">
                                {{ __('Supplier') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="url('inventory')">
                                {{ __('Inventory') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="url('warehouses')">
                                {{ __('Warehouse') }}
                            </x-navlink.navlink-dropdown-item>
                        </x-navlink.navlink-dropdown>
                        <x-navlink.navlink-dropdown name="Manufacture">
                            <x-slot:icon>
                                <i class="fas fa-store"></i>
                            </x-slot:icon>

                            <x-navlink.navlink-dropdown-item :to="url('product/customer')">
                                {{ __('Customer') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/sku')">
                                {{ __('SKU') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/sales')">
                                {{ __('Sales') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/work-order')">
                                {{ __('Work Order') }}
                            </x-navlink.navlink-dropdown-item>

                            <x-navlink.navlink-dropdown-item :to="url('product/raw-material')">
                                {{ __('Raw Material') }}
                            </x-navlink.navlink-dropdown-item>

                        </x-navlink.navlink-dropdown>
                    @endhasanyrole
                    @hasanyrole('it|top-manager|manager|finance|adminlapangan|purchasing|payable')
                        <x-navlink.navlink-dropdown name="Payments">
                            <x-slot:icon>
                                <i class="fas fa-money-bill"></i>
                            </x-slot:icon>
                            @hasanyrole('it|top-manager|manager|finance|payable')
                                {{-- <x-navlink.navlink-dropdown-item :to="url('payment-waiting-lists')">
                                    {{ __('Payable') }}
                                </x-navlink.navlink-dropdown-item>
                                <x-navlink.navlink-dropdown-item :to="url('payment-list')">
                                    {{ __('Payment List') }}
                                </x-navlink.navlink-dropdown-item>
                                <x-navlink.navlink-dropdown-item :to="route('payment.history')">
                                    {{ __('Payment History') }}
                                </x-navlink.navlink-dropdown-item> --}}
                                <x-navlink.navlink-dropdown-item :to="url('payment-submission')">
                                    {{ __('Payment Submission') }}
                                </x-navlink.navlink-dropdown-item>
                            @endhasanyrole
                            @hasanyrole('it|top-manager|manager|finance|adminlapangan')
                                <x-navlink.navlink-dropdown-item :to="url('invoices_index')">
                                    {{ __('Invoice List') }}
                                </x-navlink.navlink-dropdown-item>
                            @endhasanyrole
                        </x-navlink.navlink-dropdown>
                    @endhasanyrole
                    {{-- @hasanyrole('it|top-manager|manager|purchasing')
                        <x-navlink.navlink-dropdown name="Vendors">
                            <x-slot:icon>
                                <i class="fas fa-trailer"></i>
                            </x-slot:icon>
                            <x-navlink.navlink-dropdown-item :to="route('vendors.index')">
                                {{ __('Vendors') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('vendors.need-approval')">
                                {{ __('Pending Vendors') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('vendors.item-list')">
                                {{ __('Items') }}
                            </x-navlink.navlink-dropdown-item>
                            <x-navlink.navlink-dropdown-item :to="route('vendors.price-quotation')">
                                {{ __('Quotation') }}
                            </x-navlink.navlink-dropdown-item>
                        </x-navlink.navlink-dropdown>
                    @endhasanyrole --}}

                    {{--                    <x-navlink.navlink-dropdown name="Overtime"> --}}
                    {{--                        <x-slot:icon> --}}
                    {{--                            <i class="fas fa-clock"></i> --}}
                    {{--                        </x-slot:icon> --}}
                    {{--                        <x-navlink.navlink-dropdown-item :to="route('overtime-request.index')"> --}}
                    {{--                            {{ __('Overtime Request') }} --}}
                    {{--                        </x-navlink.navlink-dropdown-item> --}}
                    {{--                        <x-navlink.navlink-dropdown-item :to="route('overtime.index')"> --}}
                    {{--                            {{ __('Overtime Form') }} --}}
                    {{--                        </x-navlink.navlink-dropdown-item> --}}
                    {{--                    </x-navlink.navlink-dropdown> --}}
                    @hasanyrole('manager|it|top-manager')
                        <x-navlink.navlink-dropdown name="Forms">
                            <x-slot:icon>
                                <i class="fa-solid fa-table-list"></i>
                            </x-slot:icon>
                            {{-- <x-navlink.navlink-dropdown-item :to="route('hrd.leaverequest')">
                                {{ __('Leave Form') }}
                            </x-navlink.navlink-dropdown-item> --}}
                            <x-navlink.navlink-dropdown-item :to="route('minute-of-meeting.index')">
                                {{ __('MoM Form') }}
                            </x-navlink.navlink-dropdown-item>
                        </x-navlink.navlink-dropdown>
                    @endhasanyrole

                    @hasanyrole('manager|it|top-manager|k3')
                        <x-navlink.navlink-basic name="Database Employee" :href="route('hrd.alluser')">
                            <x-slot:icon>
                                <i class="fa-solid fa-server"></i>
                            </x-slot:icon>
                        </x-navlink.navlink-basic>
                        {{--                        <x-navlink.navlink-basic name="Attendance" :href="route('hrd.attendance')"> --}}
                        {{--                            <x-slot:icon> --}}
                        {{--                                <i class="fa-solid fa-clipboard-user"></i> --}}
                        {{--                            </x-slot:icon> --}}
                        {{--                        </x-navlink.navlink-basic> --}}
                    @endhasanyrole
                    @hasanyrole('manager|it|top-manager|k3')
                        {{--                        <x-navlink.navlink-dropdown style="background: red" name="HSE Compliance"> --}}
                        {{--                            <x-slot:icon> --}}
                        {{--                                <i class="fa-solid fa-file-shield"></i> --}}
                        {{--                            </x-slot:icon> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('jsa-view.index')"> --}}
                        {{--                                {{ __('JSA') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.hiradc')"> --}}
                        {{--                                {{ __('HIRADC') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.ibpr')"> --}}
                        {{--                                {{ __('IBPR') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('internal.index')"> --}}
                        {{--                                {{ __('Internal Training') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('service.index')"> --}}
                        {{--                                {{ __('Checklist Service') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.workInstruction')"> --}}
                        {{--                                {{ __('Work Instruction') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('meeting.index')"> --}}
                        {{--                                {{ __('Meeting Form') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('msds.index')"> --}}
                        {{--                                {{ __('MSDS') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('permit.index')"> --}}
                        {{--                                {{ __('Work Permit') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.sop')"> --}}
                        {{--                                {{ __('SOP Documents') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.workInduction')"> --}}
                        {{--                                {{ __('Safety Inductions') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.hsePolicy')"> --}}
                        {{--                                {{ __('HSE Policy') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.otp')"> --}}
                        {{--                                {{ __('OTP') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.mcu')"> --}}
                        {{--                                {{ __('Medical Check Up') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.apd')"> --}}
                        {{--                                {{ __('APD') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.apd-inspection')"> --}}
                        {{--                                {{ __('APD Inspection') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('k3.equipment-inspection')"> --}}
                        {{--                                {{ __('Equip Inspection') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('csms.index')"> --}}
                        {{--                                {{ __('CSMS') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('safety-talk.index')"> --}}
                        {{--                                {{ __('Safety Talk') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                            <x-navlink.navlink-dropdown-item :to="route('legal-document-management.index')"> --}}
                        {{--                                {{ __('Legal Document Management') }} --}}
                        {{--                            </x-navlink.navlink-dropdown-item> --}}
                        {{--                        </x-navlink.navlink-dropdown> --}}
                    @endhasanyrole
                @endif
            </ul>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            // get li have class submenu-item that has active
            var active = $('.submenu-item.active')
            active.parent().addClass('active');
            active.parent().parent().addClass('active');
        });
    </script>

</div>
