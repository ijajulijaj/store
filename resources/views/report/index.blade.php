@extends('layouts.app')

<!-- Custom styles for this template-->
<link href="{{ asset('admin_assets/css/bootstrap.min.css') }}" rel="stylesheet">
<link href="{{ asset('admin_assets/css/style.css') }}" rel="stylesheet">

@section('contents')
    <div class="row">
        <div class="container-fluid">
        <h3 class="mb-0 ml-2">Report</h3>
        <hr />
        <form method="POST" enctype="multipart/form-data" id="reportForm">
            @csrf
            <div class="row">
                <div class="col-md-12 border-right">
                    <div class="p-3 py-4">
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Report Type<span style="color:red;">*</span></label>
                                        <select class="form-select custom-select" aria-label="Default select example" name="option" id="reportType">
                                            <option selected>Please Choose Your Option</option>
                                            <option value="1">Master Data</option>
                                        
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Filter<span style="color:red;">*</span></label>
                                        <select class="form-select custom-select" aria-label="Default select example" name="filter" id="filter">
                                            <option selected>Please Choose Your Option</option>
                                            <option value="all">All</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-5 d-flex justify-content-end additional-fields">
                                <button id="btnExcel" class="btn btn-primary me-2" type="submit" name="export" value="excel">Export to Excel</button>
                                <button id="btnPdf" class="btn btn-primary" type="submit" name="export" value="pdf">Export to PDF</button>
                            </div>
                    </div>
                </div>
            </div>     
        </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
       $(document).ready(function() {
        // Initially hide all additional fields
            $('.additional-fields').hide();
            // Handle filter changes
            $('#filter').change(function() {
                var selectedFilter = $(this).val();
                $('.additional-fields').hide(); // Hide all additional fields initially
                
                switch (selectedFilter) {
                    case 'all':
                        // Do nothing as all fields should remain hidden
                        break;
                    default:
                        break;
                }
            });

            async function loadSelectOptions(selectId, routeUrl) {
                try {
                    const response = await fetch(routeUrl);
                    if (!response.ok) throw new Error('Network response was not ok');
                    
                    const data = await response.json();
                    console.log('Data:', data); // Log data here

                    const selectElement = $(selectId);
                    selectElement.empty();
                    selectElement.append('<option selected>Please select an option</option>');
                    
                } catch (error) {
                    console.error('Error loading options:', error);
                }
            }

            // Update form action based on report type
            $('#reportForm').submit(function(event) {
                var reportType = $('#reportType').val();
                var actionUrl = '';

                switch (reportType) {
                    case '1':
                        actionUrl = '{{ route('report.generate.master_data') }}';
                        break;
                    
                    default:
                        actionUrl = '#';
                        alert('Please select a valid report type');
                        event.preventDefault();
                        return false;
                }

                $(this).attr('action', actionUrl);
            });

            // Trigger change event on page load to set initial state
            $('#filter').trigger('change');
            $('#reportType').change(loadOptions); // Bind loadOptions to reportType change
        });
    </script>
@endsection


