<!DOCTYPE html>
<html lang="en">

@include('partials.header')

<body>

<div class="container-fluid page-body-wrapper full-page-wrapper">

    <div class="main-panel w-100">
        <div class="content-wrapper">

            {{-- CONTENT --}}
            @yield('content')

        </div>

        @include('partials.footer')

    </div>

</div>

</body>
</html>