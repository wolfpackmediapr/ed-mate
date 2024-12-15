<style>
    /* Style to center the "Processing" text */
    .dataTables_wrapper .dataTables_processing {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        font-size: 1.2em;
        color: #fff;
        background-color: #5B92FF;
        padding: 0.32rem 0.8rem;
    }

    .dataTables_filter {
        text-align: end;
    }
</style>
<!-- Main container starts -->
<div class="container main-container" id="main-container">
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header border-0 bg-none">
            <div class="row">
                <div class="col-12 col-md">
                    <p class="fs15">Customers</p>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="row">
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group ">
                            <label>By Email</label>
                            <input oninput="getLeadsByDtT()" name="user_email" type="email" class="form-control" placeholder="example@gmail.com">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group ">
                            <label>By Client Name</label>
                            <input oninput="getLeadsByDTt()" name="user_name" type="text" class="form-control" placeholder="John doe">
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group ">
                            <label>By Source</label>

                            <select name="source" class="form-control">
                                <option value="">Select Source</option>
                                <option value="Physician referral">Physician referral</option>
                                <option value="Google">Google</option>
                                <option value="Facebook">Facebook</option>
                                <option value="Friend">Friend</option>
                                <option value="Email">Email</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group ">
                            <label>By Date</label>
                            <form class="form-inline ml-auto ml-sm-3">
                                <div id="daterangeadminux" class="form-control form-control-sm">
                                    <span></span> <i class="material-icons avatar avatar-26 text-template-primary cal-icon">event</i>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-4">
                        <div class="form-group ">

                            <button type="button" onclick="getLeadsByDT()" class="btn btn-primary float-right">Search</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table class="table datatable display responsive w-100">
                <thead>
                    <tr>
                        <th>Lead ID</th>
                        <th data-breakpoints="xs md">Client Info</th>
                        <th data-breakpoints="xs md">Preferred Date</th>
                        <th data-breakpoints="xs md">Preferred Time</th>
                        <th data-breakpoints="xs md">Source</th>
                        <th data-breakpoints="xs md">Comment</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be populated by DataTables -->
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        inITdatePicker(); // Initialize the date picker
        getLeadsByDT(); // Load data initially
    });

    function inITdatePicker() {
        // Fetch the default start and end dates from the span element inside #daterangeadminux
        var dateRangeText = $('#daterangeadminux span').text().trim();
        console.log(`Extracted date range text: ${dateRangeText}`);

        var dates = dateRangeText.split(' - '); // Assuming dates are separated by ' - '

        var start, end;

        if (dates.length === 2) {
            start = moment(dates[0], 'MMM DD, YY'); // Parse the first date
            end = moment(dates[1], 'MMM DD, YY'); // Parse the second date

            // Validate the parsed dates
            if (!start.isValid() || !end.isValid()) {
                console.warn('Invalid dates parsed, using fallback.');
                start = moment().subtract(29, 'days');
                end = moment();
            }
        } else {
            // If not found or invalid format, fallback to default values
            console.warn('Dates not found or format is incorrect, using fallback.');
            start = moment().subtract(29, 'days');
            end = moment();
        }

        console.log(`Start date: ${start.format('YYYY-MM-DD')} and End date: ${end.format('YYYY-MM-DD')}`);

        $('#daterangeadminux').daterangepicker({
            startDate: start,
            endDate: end,
            locale: {
                format: 'YYYY-MM-DD'
            }
        }, function(start, end, label) {
            $('#daterangeadminux').data('start-date', start.format('YYYY-MM-DD'));
            $('#daterangeadminux').data('end-date', end.format('YYYY-MM-DD'));

            // Fetch data every time the date range changes
            getLeadsByDT();
        });

        $('#daterangeadminux').data('start-date', start.format('YYYY-MM-DD'));
        $('#daterangeadminux').data('end-date', end.format('YYYY-MM-DD'));
    }

    function getLeadsByDT() {
        inITdatePicker();
        var by_email = document.getElementsByName("user_email")[0].value;
        var by_name = document.getElementsByName("user_name")[0].value;
        var by_source = document.getElementsByName("source")[0].value;

        var startDate = $('#daterangeadminux').data('start-date');
        var endDate = $('#daterangeadminux').data('end-date');

        // Ensure dates are defined before using them
        if (!startDate || !endDate) {
            console.error('Start date or end date is undefined.');
            return;
        }

        destroyDT();

        $('.datatable').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': {
                'url': '<?= base_url() ?>get_customers_data',
                'type': 'POST',
                'data': function(data) {
                    // Append additional data to the DataTables request
                    data.searchByFromdate = startDate;
                    data.searchByTodate = endDate;
                    data.user_email = by_email;
                    data.user_name = by_name;
                    data.source = by_source;
                },
                'dataSrc': function(json) {
                    console.log('Data:', json); // Log response to verify format
                    return json.data;
                },
                'error': function(xhr, error, thrown) {
                    console.error('AJAX Error:', error, thrown); // Log AJAX errors
                }
            },
            'columns': [{
                    data: 'lead_id'
                },
                {
                    data: 'client_info'
                },
                {
                    data: 'preferred_date'
                },
                {
                    data: 'preferred_time'
                },
                {
                    data: 'source'
                },
                {
                    data: 'comment'
                },
                {
                    data: 'actions',
                    orderable: false,
                    searchable: false
                }
            ],
            'pageLength': 10
        });
    }

    function destroyDT() {
        if ($.fn.DataTable.isDataTable('.datatable')) {
            $('.datatable').DataTable().clear().destroy();
        }
    }
</script>