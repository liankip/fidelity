@extends('layouts.guest')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class=" col-md-6">
                @foreach (['danger', 'warning', 'success', 'info'] as $key)
                    @if (Session::has($key))
                        <div class="alert alert-{{ $key }} alert-dismissible fade show mb-1 mt-1" role="alert">
                            {{ Session::get($key) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">
                            </button>
                        </div>
                    @endif
                @endforeach
                @if ($rfq->is_submitted)
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>
                                Anda telah mengirimkan penawaran harga.
                            </h5>
                            <p class="mt-3">
                                Terima kasih telah mengirimkan penawaran harga. silahkan menunggu informasi selanjutnya
                                dari
                                kami.
                            </p>
                        </div>
                    </div>
                @elseif($rfq->expired_at->isPast())
                    <div class="card">
                        <div class="card-body text-center">
                            <h5>
                                Maaf, Form Penawaran Harga telah ditutup.
                            </h5>
                            <p class="mt-3">
                                Terima kasih telah mengirimkan penawaran harga. silahkan menunggu informasi selanjutnya
                                dari
                                kami.
                            </p>
                        </div>
                    </div>
                @else
                    <div>
                        <div class="text-center fw-bold">
                            <h3>Form Penawaran Harga</h3>
                            <div id="" class="form-text mb-3">
                                Form ini akan ditutup pada <span class="text-danger">{{ $rfq->expired_at }}</span>
                            </div>
                        </div>
                        <div class="card mt-3">
                            <div class="card-body">
                                <form method="POST" action="{{ route('request-for-quotation.store', $rfq->id) }}"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @foreach ($rfq->itemDetail as $item)
                                        <div class="mt-4">
                                            <input type="hidden" name="item_id[]" value="{{ $item->item_id }}">
                                            <h6>{{ $item->item->name }} - {{ $item->unit }} </h6>
                                            <x-common.input label="Harga" name="price[]" required type="number"
                                                max="999999999" />
                                            <div id="" class="form-text mb-3">
                                                Harga untuk 1 {{ $item->unit }}
                                            </div>
                                        </div>
                                        <div class="mt-2">
                                            <span class="">Notes</span>
                                            <textarea class="form-control" name="notes[]" id="" cols="30" rows="3"></textarea>
                                        </div>
                                    @endforeach

                                    <div class="mt-3">
                                        <button type="submit" class="btn btn-primary">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
