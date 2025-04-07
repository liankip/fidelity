            @extends('layouts.app')

            @section('content')
                <div class="container mt-2">
                    <div class="row">
                        <div class="col-lg-12 margin-tb">
                            <div class="pull-left">
                                <h2>{{ config('app.company', 'SNE') }} - ERP | Validasi Berkas</h2>
                            </div>
                            <div class="pull-right mb-2">
                                <a class="btn btn-success" href="{{ route('submitions.create') }}"> Upload Foto Barang</a>
                            </div>
                        </div>
                    </div>
                    @if ($message = Session::get('success'))
                        <div class="alert alert-success">
                            <p>{{ $message }}</p>
                        </div>
                    @endif
                    <div class="card">
                        <div class="card-header"></div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <tr>
                                    <th>No</th>
                                    <th>PO No</th>
                                    <th>Item Name</th>
                                    <th>Qty</th>
                                    <th>PIC Pengantar</th>
                                    <th>Penerima</th>
                                    <th>Photo Barang</th>


                                    {{-- <th width="280px">Action</th> --}}
                                </tr>
                                @foreach ($sh as $key => $val)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $val->purchaseorder->po_no }}</td>
                                        {{-- <td>{{ $val->prdetail->item_name }}</td> --}}
                                        <td>{{ $val->qty }}</td>
                                        <td>{{ $val->pic_pengantar }}</td>
                                        <td>{{ $val->penerima }}</td>

                                        <td>
                                            <a href="/{{ $val->foto_barang }}" target="_blank"> Click Here</a>

                                        </td>


                                    </tr>
                                @endforeach
                            </table>

                        </div>
                        <div class="card-footer"></div>
                    </div>

                    {{-- {!! $sh->links() !!} --}}
                @endsection
