@extends('layouts.app')

@section('content')
    <div class="container mt-2">
        <div class="row">
            <div class="col-lg-12 margin-tb">
                <div class="d-flex justify-content-between">
                    <h2>Notifications</h2>
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

                <hr>
            </div>
        </div>

        <div class="d-flex justify-content-end">
            <a href="{{ route('notifications.delete-all') }}" class="btn btn-danger">Delete All</a>
        </div>

        <div class="mt-5">
            @foreach ($notifications as $notification)
                @php
                    $data = json_decode($notification->data);
                @endphp

                @if ($data)
                    <div class="border ps-3 pe-3 pt-2 pb-2 rounded mt-2 bg-white">
                        <div class="d-flex justify-content-between">
                            <div>
                                <div class="fs-5 text-capitalize" style="font-weight: bold">{!! $data->name ? $data->name : null !!}</div>
                                <div style="font-size: 10px;">{{ $notification->created_at }}</div>
                            </div>
                            <form action="/notifications/{{ $notification->id }}" method="post">

                                @csrf
                                @method('DELETE')
                                <button href="" class="btn text-danger">Delete</button>

                            </form>
                        </div>

                        <hr>

                        <div class="mt-3">
                            <p>{!! $data->body !!}</p>
                        </div>
                        <a href="{{ $data->url }}" target="_blank"
                            class="text-decoration-none btn btn-sm btn-primary">More <svg xmlns="http://www.w3.org/2000/svg"
                                width="10" height="10" fill="currentColor" class="bi bi-box-arrow-up-right"
                                viewBox="0 0 16 16">
                                <path fill-rule="evenodd"
                                    d="M8.636 3.5a.5.5 0 0 0-.5-.5H1.5A1.5 1.5 0 0 0 0 4.5v10A1.5 1.5 0 0 0 1.5 16h10a1.5 1.5 0 0 0 1.5-1.5V7.864a.5.5 0 0 0-1 0V14.5a.5.5 0 0 1-.5.5h-10a.5.5 0 0 1-.5-.5v-10a.5.5 0 0 1 .5-.5h6.636a.5.5 0 0 0 .5-.5z" />
                                <path fill-rule="evenodd"
                                    d="M16 .5a.5.5 0 0 0-.5-.5h-5a.5.5 0 0 0 0 1h3.793L6.146 9.146a.5.5 0 1 0 .708.708L15 1.707V5.5a.5.5 0 0 0 1 0v-5z" />
                            </svg></a>

                    </div>
                @endif
            @endforeach
        </div>
        </table>
        <div class="mt-4">
            {{ $notifications->links() }}
        </div>
    @endsection
