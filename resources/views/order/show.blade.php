<div class="modal-body">
    <div class="row justify-content-between">
        <div class="col">
            <h4>Order No</h4>
            <span>#{{ $settings->invoice($data->id) }}</span>
        </div>
        <div class="col">
            <h4>Order Date</h4>
            <span>{{ \Carbon\Carbon::parse($data->order_date)->format($settings->date_format) }}</span>
        </div>
        <div class="col">
            <h4>Order Type</h4>
            <span>{{ $data->type }}</span>
        </div>
        <div class="col">
            <h4>Status</h4>
            <span>{{ $data->status }}</span>
        </div>
    </div>
    {{-- <hr> --}}
    <div class="row mt-4">
        <div class="col">
            <h5>Customer</h5>
            @if ($data->customer_id == 0)
                <p>Walking Customer</p>
            @else
                <p>{{ $data->customer->name }},</p>
                <p>{{ $data->customer->contact }},</p>
                <p>{{ $data->customer->email }},</p>
                <p>{{ $data->customer->address }}.</p>
            @endif
        </div>
        @if ($data->room_id != 0)
            <div class="col">
                <h5>Room</h5>
                <p>{{ $data->room->name }}</p>
            </div>
        @endif
        @if ($data->table_id != 0)
            <div class="col">
                <h5>Table</h5>
                <p>{{ $data->table->name }}</p>
            </div>
        @endif
    </div>
    <hr>
    <div class="row">
        <h5>Items</h5>
        <div class="col-12">
            <table class="table table-hover align-middle">
                <thead>
                    <th>#</th>
                    <th>Name</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                </thead>
                <tbody>
                    @foreach ($data->items as $key => $item)
                        <tr>
                            <td>{{ $key + 1 }}</td>
                            <td>{{ $item->meal->name }}</td>
                            <td>{{ $settings->currency }} {{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $settings->currency }} {{ number_format($item->total, 2) }}</td>
                        </tr>
                        @if (!empty($item->modifiers))
                            @foreach ($item->modifiers as $item2)
                                <tr>
                                    <td>

                                    </td>
                                    <td>{{ $item2->modifier->name }} <br> <span
                                            class="small text-muted">Modifier</span></td>
                                    <td>{{ $settings->currency }} {{ number_format($item2->price, 2) }}</td>
                                    <td>{{ $item2->quantity }}</td>
                                    <td>{{ $settings->currency }} {{ number_format($item2->total, 2) }}</td>
                                </tr>
                            @endforeach
                        @endif
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            Sub Total
                        </td>
                        <td>
                            {{ $settings->currency }}
                            {{ number_format($data->payment ? $data->payment->sub_total : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            Discount
                        </td>
                        <td>
                            {{ $settings->currency }}
                            {{ number_format($data->payment ? $data->payment->discount : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            VAT
                        </td>
                        <td>
                            {{ $settings->currency }}
                            {{ number_format($data->payment ? $data->payment->vat : 0, 2) }}
                        </td>
                    </tr>
                    <tr>
                        <td colspan="3"></td>
                        <td>
                            <h3>Total</h3>
                        </td>
                        <td>
                            <h4>{{ $settings->currency }}
                                {{ number_format($data->payment ? $data->payment->total : 0, 2) }}</h4>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>
