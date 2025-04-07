@props(['errors'])
<div class="mt-2 alert-container">
    <span class="alert-icon">
        <i class="fas fa-circle-exclamation text-danger"></i>
    </span>
    <div class="alert alert-warning alert-message" role="alert">
        <h6>
            There are some errors in your file, here are the details:
        </h6>
        <ul>
            @foreach($errors as $error)
                @if(count($error['list']) > 0)
                    <li class="fw-bold">
                        <span >Item no {{ $error['no'] }}</span>
                        <ul>
                            @foreach($error['list'] as $key => $value)
                                <li>
                                    <span>
                                        @if($key === 'item_id')
                                            Item ID
                                        @elseif($key === 'unit')
                                            Unit
                                        @elseif($key === 'qty')
                                            Quantity
                                        @elseif($key === 'price_estimation')
                                            Price Estimation
                                        @elseif($key === 'shipping_cost')
                                            Shipping Cost
                                        @endif
                                    :
                                    </span>
                                    @foreach($value as $val)
                                       <span class="text-danger"> {{ $val }}</span>
                                    @endforeach
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endforeach
        </ul>
        <h6>You can still submit the file, but the items with errors will not be saved.</h6>
    </div>
</div>
