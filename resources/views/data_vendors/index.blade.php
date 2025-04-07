@extends('layouts.app')

@section('content')
     <div>
          <div class="container mt-2">
               <div class="row">
                    <div class="col-lg-12 margin-tb">
                         <h4>
                              Data Vendor
                         </h4>
                         <hr>

                         <x-common.notification-alert />

                         <div class="card mt-3">
                              <div class="card-body">
                                   @hasanyrole('it|top-manager|manager')
                                        <a class="btn btn-info rounded mb-3" href="{{ route('vendors.newVendor') }}">Add Vendor</a>
                                   @endhasanyrole
                                   <table class="table" id="table">
                                        <thead class="thead-light">
                                             <tr>
                                                  <th class="align-middle" style="width: 5%">#</th>
                                                  <th class="align-middle">Company</th>
                                                  <th class="align-middle">Email</th>
                                                  <th class="align-middle">Address</th>
                                                  <th class="align-middle">Phone</th>
                                                  <th class="align-middle">Date</th>
                                             </tr>
                                        </thead>
                                        <tbody>
                                             @foreach ($vendors as $vendor)
                                                  <tr>
                                                       <td>
                                                            {{ $loop->iteration }}
                                                       </td>
                                                       <td>
                                                            <a href="{{ route('vendors.show', $vendor->id) }}"
                                                                 target="_blank">
                                                                 {{ $vendor->name }}
                                                            </a>
                                                       </td>
                                                       <td>
                                                            {{ $vendor->address }}
                                                       </td>
                                                       <td>{{ $vendor->email }}</td>
                                                       <td>{{ $vendor->telp }}</td>
                                                       <td>
                                                            {{ date('d F Y', strtotime($vendor->created_at)) }}
                                                       </td>
                                                  </tr>
                                             @endforeach
                                        </tbody>
                                   </table>
                              </div>
                         </div>
                    </div>
               </div>

               <script>
                    $(document).ready(function() {
                         const dTable = new DataTable('#table', {
                              ordering: false,
                         });
                    });
               </script>
          </div>
     </div>
@endsection
