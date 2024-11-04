<div class="modal-body">
    <div class="table-responsive">
        <table class="table align-middle" id="">
            <thead class="table-light">
                <th>#</th>
                <th>Date</th>
                <th>Amount</th>
                <th>Reference</th>
                <th>Description</th>
                <th>Receipt</th>
            </thead>
            <tbody>
                @foreach ($data as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td> {{ date_format(new DateTime('@' . strtotime($item->date)), $settings->date_format) }}
                        </td>
                        <td>
                            LKR {{ number_format($item->amount, 2) }}
                        </td>
                        <td>{{ $item->reference ?? '-' }}</td>
                        <td>{{ $item->description ?? '-' }}</td>
                        <td>
                            @if ($item->receipt)
                                <a href="{{ asset('storage/' . $item->receipt) }}"
                                    class="btn btn-light bg-transparent border" target="__blank">View
                                    Receipt</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
