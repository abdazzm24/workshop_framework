<!DOCTYPE html>
<html lang="en">

@include('partials.header')

<body>
<div class="container-scroller">

    @include('partials.navbar')

    <div class="container-fluid page-body-wrapper">

        @include('partials.sidebar')

        <div class="main-panel">
            <div class="content-wrapper">

                {{-- CONTENT --}}
                @yield('content')

            </div>

            @include('partials.footer')

        </div>
    </div>
</div>

{{-- JAVASCRIPT GLOBAL --}}
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/vendors/chart.js/chart.umd.js') }}"></script>
<script src="{{ asset('assets/vendors/bootstrap-datepicker/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>
<script src="{{ asset('assets/js/dashboard.js') }}"></script>

{{-- datatables --}}
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

{{-- JAVASCRIPT PAGE --}}
@yield('js-page')

</body>
</html>
