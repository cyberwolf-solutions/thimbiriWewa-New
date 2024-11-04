@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <div>
                    <h3 class="mb-sm-0">{{ $title }}</h3>

                    <ol class="breadcrumb m-0 mt-2">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>

                        @foreach ($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item {{ $breadcrumb['active'] ? 'active' : '' }}">
                                @if (!$breadcrumb['active'])
                                    <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['label'] }}</a>
                                @else
                                    {{ $breadcrumb['label'] }}
                                @endif
                            </li>
                        @endforeach
                    </ol>
                </div>

                <div class="page-title-right">
                    {{-- Add Buttons Here --}}
                    <form action="" method="get" id="form">
                        <div class="row">
                            <div class="col">
                                <select class="form-select" name="type" id="" onchange="$('#form').submit()">
                                    <option value="" {{ $type == '' ? 'selected' : 'selected' }}>All</option>
                                    <option value="Dining"{{ $type == 'Dining' ? 'selected' : '' }}>Dining</option>
                                    <option value="Takeway"{{ $type == 'Takeway' ? 'selected' : '' }}>Takeway</option>
                                    <option value="RoomDelivery"{{ $type == 'RoomDelivery' ? 'selected' : '' }}>Room
                                        Delivery</option>
                                </select>
                            </div>

                            <a href="{{ route('kitchen.index') }}" class="btn btn-primary btn-icon"
                                data-bs-toggle="tooltip" title="Refresh">
                                <i class="ri-restart-line"></i>
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    @if ($data->isEmpty())
                        <h1>Nothing here yet</h1>
                    @endif
                    @foreach ($data as $item)
                        <div class="col-md-4">
                            <div class="card border">
                                <div class="card-header">
                                    <div class="row align-items -center">
                                        <div class="col">
                                            <h5>#{{ $settings->invoice($item->id) }} <span class="fw-normal fs-6">|
                                                    {{ $item->table_id != 0 ? $item->table->name : 'No Table' }}</span>
                                            </h5>
                                            <span>{{ $item->customer_id != 0 ? $item->customer->name : 'Walking Customer' }}</span>
                                        </div>
                                        <div class="col text-end">
                                            <h5>
                                                <span class="badge bg-info-subtle">{{ $item->type }}</span>
                                                <a href="{{ route('kot.print', ['id' => $item->id]) }}" target="__blank">
                                                    <span class="badge bg-warning-subtle" data-bs-toggle="tooltip"
                                                        title="Print"><i class="bi bi-printer small"></i></span>
                                                </a>
                                            </h5>
                                            <span class="small">{{ $item->created_at }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-body">
                                    <div class="card bg-light overflow-hidden">
                                        <div class="card-body py-2">
                                            <div class="d-flex">
                                                <div class="flex-grow-1">
                                                    <p class="mb-0 small"><b
                                                            class="text-secondary">{{ $item->progress ?? 0 }}%</b>

                                                        @if ($item->progress == 0)
                                                            Update
                                                            in
                                                            progress...
                                                        @else
                                                            Completed
                                                        @endif
                                                    </p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <?php $itemsLeft = $item->items->where('status', '<>', 'Complete')->count(); ?>
                                                    <p class="mb-0 small">{{ $itemsLeft }} Item(s) Left</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="progress progress-sm bg-secondary-subtle rounded-0">
                                            <div class="progress-bar bg-secondary" role="progressbar"
                                                style="width: {{ $item->progress ?? 0 }}%"
                                                aria-valuenow="{{ $item->progress ?? 0 }}" aria-valuemin="0"
                                                aria-valuemax="100"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <ul class="list-group">
                                            @foreach ($item->items as $meal)
                                                <li class="list-group-item border-0">
                                                    <div class="form-check">
                                                        <input type="checkbox" id="meal_{{ $meal->id }}"
                                                            {{ $meal->status == 'Complete' ? 'checked disabled' : '' }}
                                                            class="form-check-input meal" value="{{ $meal->id }}">
                                                        <label for="meal_{{ $meal->id }}"
                                                            class="form-check-label {{ $meal->status == 'Complete' ? ' text-decoration-line-through' : '' }}">{{ $meal->meal->name }}
                                                            (x
                                                            {{ $meal->quantity }})
                                                        </label>
                                                    </div>
                                                    @if (!$meal->modifiers->isEmpty())
                                                        <div class="ps-4">
                                                            <small class="blockquote-footer">Modifiers</small>
                                                            <ul>
                                                                @foreach ($meal->modifiers as $modifier)
                                                                    <li>{{ $modifier->modifier->name }}</li>
                                                                @endforeach
                                                            </ul>
                                                        </div>
                                                    @endif
                                                </li>
                                            @endforeach

                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <!-- Repeat similar structure for other items -->
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script>
        $(document).ready(function() {
            $('.meal').change(function() {
                $('#loader').removeClass('d-none');

                var id = $(this).val();
                $.ajax({
                    url: '{{ route('complete-meal') }}',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        "id": id
                    },
                    success: function(response) {
                        $('#loader').addClass('d-none');
                        if (response.success) {
                            display_success(response
                                .message); // replace with your success message
                        } else {
                            display_error(response.message); // replace with your error message
                        }
                        setTimeout(function() {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        $('#loader').addClass('d-none');
                        var errorMessage = xhr.status + ': ' + xhr.statusText;
                        display_error(errorMessage);
                    }
                });
            })
        })
    </script>
    <script>
        function beep() {
            var context = new AudioContext();
            var oscillator = context.createOscillator();
            oscillator.type = "sine";
            oscillator.frequency.value = 800;
            oscillator.connect(context.destination);
            oscillator.start();
            // Beep for 50 milliseconds
            setTimeout(function() {
                oscillator.stop();
            }, 50);
        }
    </script>
    <script>
        var channel = pusher.subscribe('kot');
        channel.bind('notify', function(data) {
            // Reload the page
            window.location.reload();

            // Play a beep sound
            beep();

            // Send a push notification
            if (Notification.permission === 'granted') {
                var notification = new Notification('New Kot Received', {
                    body: 'Please check for updates.',
                });
            }
        });
    </script>
@endsection
