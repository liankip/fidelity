<div>
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="pull-left">
                </div>
            </div>
        </div>
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <p>{{ $message }}</p>
            </div>
        @endif
        <div class="card">
            <div class="card-header">
                <h2>{{ config('app.company', 'SNE') }} - ERP | Purhase Report</h2>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Item</th>
                        <th>Project</th>
                        <th>QTY</th>
                        <th>Harga</th>
                        <th>Date</th>
                        <th>Tax</th>
                    </tr>

                </table>


            </div>

            {{-- <div class="card-footer"></div> --}}
        </div>
    </div>
</div>
