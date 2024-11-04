@extends('layouts.master')
@section('title')
    {{ $title }}
@endsection
@section('content')
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
                    <button id="printButton" class="btn btn-primary">
                        <i class="bi bi-printer-fill"></i>
                    </button>
                    <button id="csvButton" class="btn btn-success">
                        <i class="bi bi-file-earmark-spreadsheet-fill"></i>
                    </button>
                </div>


            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table align-middle" id="example">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Contact</th>
                                <th>Address</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->contact }}</td>
                                <td>{{ $user->address }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('printButton').addEventListener('click', function() {
                var printWindow = window.open('', '_blank');
                printWindow.document.open();
                printWindow.document.write('<html><head><title>Print</title>');
                printWindow.document.write('<style>');
                printWindow.document.write(`
                body {
                    background-color: #f8f9fa;
                    font-family: Arial, sans-serif;
                }
                .container-fluid {
                    padding: 20px;
                }
                .logo {
                    max-width: 200px;
                    margin-bottom: 10px;
                }
                .resort-name {
                    font-size: 24px;
                    color: #111316;
                    font-weight: bold;
                    text-align: center; /* Center the text */
                    margin: 0 auto; /* Center horizontally */
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    background-color: #fff;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                    vertical-align: middle;
                }
                th {
                    background-color: #007bff;
                    color: #fff;
                }
                .table-hover tbody tr:hover {
                    background-color: #f0f0f0;
                }
                .status-active {
                    color: #28a745;
                    font-weight: bold;
                }
                .status-inactive {
                    color: #dc3545;
                    font-weight: bold;
                }
            `);
                printWindow.document.write('</style></head><body>');
                printWindow.document.write('<div class="container-fluid">');
                printWindow.document.write(
                    '<img src="{{ asset('storage/' . $settings->logo_dark) }}" class="logo img-fluid">'
                    );
                printWindow.document.write('<div class="resort-name">Thimbiri Wewa Resort</div>');
                printWindow.document.write('<hr>');
                // printWindow.document.write('<h4>Users Report</h4>');
                printWindow.document.write(document.getElementById('example').outerHTML);
                printWindow.document.write('</div>');
                printWindow.document.write('</body></html>');
                printWindow.document.close();
                printWindow.print();
            });

            document.getElementById('csvButton').addEventListener('click', function() {
                var csvContent = "data:text/csv;charset=utf-8,";
                var rows = document.querySelectorAll("#example tbody tr");

                rows.forEach(function(row) {
                    var rowData = [];
                    row.querySelectorAll("td").forEach(function(cell) {
                        rowData.push(cell.innerText);
                    });
                    csvContent += rowData.join(",") + "\r\n";
                });

                var encodedUri = encodeURI(csvContent);
                var link = document.createElement("a");
                link.setAttribute("href", encodedUri);
                link.setAttribute("download", "customer.csv");
                document.body.appendChild(link);
                link.click();
            });
        });
    </script>
@endsection
