@extends('layouts.email')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12" style="font-size: 16px">
                <p>
                    New BOQ has been approved by {{ $data['approver'] }} for project {{ $data['project_name'] }}. <br>
                    Please check the details in the system.
                </p>
                <p>
                    You can also download the BOQ file attached in this email.
                </p>
                <p>
                    Thank you.
                </p>
            </div>
        </div>
    </div>
@endsection
