@extends('layouts.guest')

<!-- Content -->
@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-md-6">

                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="">
                                <br><br>
                                <h2 style="color:#0fad00">
                                    Registration Success
                                </h2>
                                <h3 class="mt-4">Dear, {{ Session::get('vendor-name')  }}</h3>
                                <p style="font-size:20px;color:#5C5C5C;">
                                    Your registration has been successfully submitted. Thank you for your interest in
                                    becoming a supplier for us. We will review your application and get back to you as
                                    soon as possible.
                                    <br><br>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
