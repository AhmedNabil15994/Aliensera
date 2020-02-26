    <!-- jQuery -->
    {{--<script  src="{{ asset('assets/vendors/jquery/dist/jquery.min.js')}}"></script>--}}
    <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
    <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

    <!-- Bootstrap -->
    <script  src="{{ asset('assets/vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>
    <!-- FastClick -->
    <script  src="{{ asset('assets/vendors/fastclick/lib/fastclick.js')}}"></script>
    <!-- NProgress -->
    <script  src="{{ asset('assets/vendors/nprogress/nprogress.js')}}"></script>
    <!-- Chart.js -->
    <script  src="{{ asset('assets/vendors/Chart.js/dist/Chart.min.js')}}"></script>
    <!-- ECharts -->
    <script src="{{ asset('assets/vendors/echarts/dist/echarts.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/echarts/map/js/world.js')}}"></script>
    <!-- gauge.js -->
    <script  src="{{ asset('assets/vendors/gauge.js/dist/gauge.min.js')}}"></script>
    <!-- bootstrap-progressbar -->
    <script  src="{{ asset('assets/vendors/bootstrap-progressbar/bootstrap-progressbar.min.js')}}"></script>
    <!-- iCheck -->
    <script  src="{{ asset('assets/vendors/iCheck/icheck.min.js')}}"></script>
    <!-- Datatables -->
    <script src="{{ asset('assets/vendors/datatables.net/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-bs/js/dataTables.bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/dataTables.buttons.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons-bs/js/buttons.bootstrap.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.flash.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.html5.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-buttons/js/buttons.print.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-fixedheader/js/dataTables.fixedHeader.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-keytable/js/dataTables.keyTable.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-responsive/js/dataTables.responsive.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-responsive-bs/js/responsive.bootstrap.js')}}"></script>
    <script src="{{ asset('assets/vendors/datatables.net-scroller/js/dataTables.scroller.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/jszip/dist/jszip.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/pdfmake/build/pdfmake.min.js')}}"></script>
    <script src="{{ asset('assets/vendors/pdfmake/build/vfs_fonts.js')}}"></script>
    <script src="{{ asset('assets/vendors/jquery-sparkline/dist/jquery.sparkline.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/vendors/dropzone/dist/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/lib/sweetalert.min.js')}}"></script>
    <!-- Skycons -->
    <script  src="{{ asset('assets/vendors/skycons/skycons.js')}}"></script>
    <!-- Flot -->
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.js')}}"></script>
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.pie.js')}}"></script>
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.time.js')}}"></script>
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.stack.js')}}"></script>
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.resize.js')}}"></script>
    <script  src="{{ asset('assets/vendors/Flot/jquery.flot.categories.js')}}"></script>
    <!-- Flot plugins -->
    <script  src="{{ asset('assets/vendors/flot.orderbars/js/jquery.flot.orderBars.js')}}"></script>
    <script  src="{{ asset('assets/vendors/flot-spline/js/jquery.flot.spline.min.js')}}"></script>
    <script  src="{{ asset('assets/vendors/flot.curvedlines/curvedLines.js')}}"></script>
    <!-- DateJS -->
    <script  src="{{ asset('assets/vendors/DateJS/build/date.js')}}"></script>
    <!-- JQVMap -->
    <script  src="{{ asset('assets/vendors/jqvmap/dist/jquery.vmap.js')}}"></script>
    <script  src="{{ asset('assets/vendors/jqvmap/dist/maps/jquery.vmap.world.js')}}"></script>
    <script  src="{{ asset('assets/vendors/jqvmap/examples/js/jquery.vmap.sampledata.js')}}"></script>
    <!-- bootstrap-daterangepicker -->
    <script  src="{{ asset('assets/vendors/moment/min/moment.min.js')}}"></script>
    <script  src="{{ asset('assets/vendors/bootstrap-daterangepicker/daterangepicker.js')}}"></script>
    <script src="{{ asset('assets/production/js/toastr.min.js')}}"></script>
    <!-- bootstrap-datetimepicker -->
    <script src="{{ asset('assets/vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js')}}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <!-- editor-->
    <script src="{{ asset('assets/plugins/summernote/dist/summernote.js') }}"></script>
    <script src="{{ asset('assets/js/lib/fancybox/jquery.fancybox.pack.js')}}"></script>
    <script src="{{ asset('assets/plugins/lou-multi-select/js/jquery.multi-select.js')}}"></script>
    <script src="{{ asset('assets/plugins/quicksearch/jquery.quicksearch.js')}}"></script>

    <!-- Custom Theme Scripts -->
    <script  src="{{ asset('assets/build/js/custom.js')}}"></script>
    <script src="{{ asset('assets/components/notifications.js')}}"></script>
    <script src="{{ asset('assets/components/master.js')}}"></script>
    <script src="{{ asset('assets/js/load_languages.js')}}"></script>
    {{-- <script type="text/javascript">
        document.addEventListener('contextmenu', function(e) {
          e.preventDefault();
        });
    </script> --}}
    @yield('script')
