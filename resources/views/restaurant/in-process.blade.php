<form action="">
    <div class="modal-body">
        <div class="row mb-3">
            <ul class="nav nav-pills mb-3 nav-fill" id="pills-tab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-custom-light active" id="pills-in-progress-tab" data-bs-toggle="pill"
                        data-bs-target="#in-progress" type="button" role="tab" aria-controls="in-progress"
                        aria-selected="true">In Progress ({{ $inProgress->count() }})</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link nav-custom-light" id="pills-ready-tab" data-bs-toggle="pill"
                        data-bs-target="#ready" type="button" role="tab" aria-controls="ready"
                        aria-selected="false">Ready ({{ $ready->count() }})</button>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <div class="tab-pane fade show active" id="in-progress" role="tabpanel"
                    aria-labelledby="in-progress-tab" tabindex="0">

                    <div class="row gy-2 overflow-auto" style="max-height: 75vh">
                        {{-- Items --}}
                        @foreach ($inProgress as $item)
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header">
                                        <h5>
                                            Order #{{ $settings->invoice($item->id) }}
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 0%"
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                        </div>
                                        <dl class="row">
                                            <dt class="col-sm-3">Type:</dt>
                                            <dd class="col-sm-9">{{ $item->type }}</dd>
                                            @if ($item->room_id != 0)
                                                <dt class="col-sm-3">Room:</dt>
                                                <dd class="col-sm-9">{{ $item->room->name }}</dd>
                                            @endif
                                            @if ($item->table_id != 0)
                                                <dt class="col-sm-3">Table:</dt>
                                                <dd class="col-sm-9">{{ $item->table->name }}</dd>
                                            @endif

                                            <dt class="col-sm-3">Customer:</dt>
                                            @if ($item->customer_id == 0)
                                                <dd class="col-sm-9">Walking Customer</dd>
                                            @else
                                                <dd class="col-sm-9">
                                                    <p>{{ $item->customer->name }},</p>
                                                    <p>{{ $item->customer->contact }},</p>
                                                    <p>{{ $item->customer->email }},</p>
                                                    <p>{{ $item->customer->address }}.</p>
                                                </dd>
                                            @endif

                                            <dt class="col-sm-3">Created at:</dt>
                                            <dd class="col-sm-9">
                                                {{ \Carbon\Carbon::parse($item->order_date)->format($settings->date_format) }}
                                            </dd>
                                            <dt class="col-sm-3">Last updated at:</dt>
                                            <dd class="col-sm-9">
                                                {{ \Carbon\Carbon::parse($item->updated_at)->format($settings->date_format) }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                        @if ($inProgress->count() == 0)
                            <div
                                class="select-none bg-blue-gray-100 rounded flex flex-wrap content-center justify-center opacity-25">
                                <div class="w-full text-center">
                                    <i class="ri-file-warning-line fs-1"></i>
                                    <p class="text-xl">
                                        Attention! record not found
                                    </p>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
                <div class="tab-pane fade" id="ready" role="tabpanel" aria-labelledby="ready-tab" tabindex="0">
                    {{-- <div class="row justify-content-end mb-3 pe-3">
                        <div class="col-3">
                            <select name="" id="" class="form-control">
                                <option disabled selected>Filter by Type</option>
                                <option value="">All</option>
                                <option value="">Dining</option>
                                <option value="">Takeaway</option>
                            </select>
                        </div>
                        <div class="col-3">
                            <select name="" id="" class="form-control">
                                <option disabled selected>Filter by Table</option>
                                <option value="">All</option>
                                <option value="">Table 01</option>
                                <option value="">Table 02</option>
                            </select>
                        </div>
                    </div> --}}
                    <div class="row gy-2 overflow-auto" style="max-height: 75vh">
                        @if ($ready->count() == 0)
                            <div
                                class="select-none bg-blue-gray-100 rounded flex flex-wrap content-center justify-center opacity-25">
                                <div class="w-full text-center">
                                    <i class="ri-file-warning-line fs-1"></i>
                                    <p class="text-xl">
                                        Attention! record not found
                                    </p>
                                </div>
                            </div>
                        @endif
                        @foreach ($ready as $item)
                            <div class="col-12">
                                <div class="card border">
                                    <div class="card-header">
                                        <h5>
                                            Order #202402736
                                        </h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="progress mb-3">
                                            <div class="progress-bar bg-info" role="progressbar" style="width: 0%"
                                                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                                        </div>
                                        <dl class="row">
                                            <dt class="col-sm-3">Type:</dt>
                                            <dd class="col-sm-9">Dining</dd>
                                            <dt class="col-sm-3">Table:</dt>
                                            <dd class="col-sm-9">Table 05</dd>
                                            <dt class="col-sm-3">Customer:</dt>
                                            <dd class="col-sm-9">Walking Customer</dd>
                                            <dt class="col-sm-3">Created at:</dt>
                                            <dd class="col-sm-9">12-02-2024, 12:50:40</dd>
                                            <dt class="col-sm-3">Last updated at:</dt>
                                            <dd class="col-sm-9">12-02-2024, 12:50:40</dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
