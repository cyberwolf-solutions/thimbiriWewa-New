<script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4="
    crossorigin="anonymous"></script>
<script src="{{ URL::asset('build/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/simplebar/simplebar.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/node-waves/waves.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/feather-icons/feather.min.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/plugins/lord-icon-2.1.0.js') }}"></script>
<script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/js/jsvectormap.min.js') }}"></script>
<script src="{{ URL::asset('build/libs/jsvectormap/maps/world-merc.js') }}"></script>
<script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
{{-- <script src="{{ URL::asset('build/js/plugins.js') }}"></script> --}}
<script type='text/javascript' src='{{ asset('build/libs/flatpickr/flatpickr.min.js') }}'></script>
<script src="{{ URL::asset('build/js/app.js') }}"></script>
<!-- select2 -->
<script src="{{ asset('build/libs/select2/select2.min.js') }}"></script>
<script src="{{ asset('build/js/pages/select2.init.js') }}"></script>
<!-- Sweet Alert -->
<script src="{{ URL::asset('build/libs/sweetalert2/sweetalert2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/11.6.16/sweetalert2.min.js"></script>
<!--datatable js-->
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="{{ URL::asset('build/js/pages/datatables.init.js') }}"></script>
<script src="{{ URL::asset('build/js/form.js') }}"></script>
<script src="{{ URL::asset('build/magnify-image/js/jquery.jqZoom.js') }}"></script>
<script src="{{ URL::asset('build/js/pages/fabric.min.js') }}"></script>
<script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
    var pusher = new Pusher('6fd54786974cfb62256a', {
        cluster: 'mt1'
    });
</script>
<script>
    $(document).ready(function() {
        $('.light-dark-mode').click(function() {
            $('#loader').removeClass('d-none');
            $.ajax({
                url: '{{ route('change.mode') }}',
                type: "POST",
                dataType: "JSON",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    $('#loader').addClass('d-none');
                    if (response.success) {
                        display_success(response
                            .message); // replace with your success message
                    } else {
                        display_error(response.message); // replace with your error message
                    }
                    if (response.url) {
                        setTimeout(function() {
                            window.location.href = response.url;
                        }, 1500);
                    }
                },
                error: function(xhr) {
                    $('#loader').addClass('d-none');
                    var errorMessage = xhr.status + ': ' + xhr.statusText;
                    display_error(errorMessage);
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function() {
        var currentUrl = window.location.href;

        // Loop through each navigation link
        $('#scrollbar .nav-link').each(function() {
            var linkUrl = $(this).attr('href');

            // Check if the current URL contains the link URL
            if (currentUrl.includes(linkUrl)) {
                // Add the "active" class to the parent li
                $(this).addClass('active');

                // Expand the parent dropdown if it exists
                var parentDropdown = $(this).closest('.collapse');
                if (parentDropdown.length > 0) {
                    parentDropdown.addClass('show');
                    parentDropdown.closest('.nav-item').addClass('active');
                }
                var navItem = $(this).closest('.nav-item');
                if (navItem.length > 0) {
                    navItem.addClass('active');
                }
            }
        });
    });
</script>
@yield('script')
@yield('script-bottom')
