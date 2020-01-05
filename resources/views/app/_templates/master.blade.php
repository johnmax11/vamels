<!DOCTYPE html>
<html lang="en">
    <!-- header html -->
    @include ('app._templates.header')
    <body>
        <input type="hidden" id="_token" name="_token" value="{{ csrf_token() }}" />
        @if(isset($_token_update))
            <input type="hidden" id="_token_update" name="_token_update" value="{{$_token_update}}" />
        @endif
        <!-- header menu html -->
        @include ('app._templates.header_menu')
        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- START MAIN -->
        <div id="main">
            <!-- START WRAPPER -->
            <div class="wrapper">
                <!-- left menu html -->
                @include ('app._templates.menu_left')
                <!-- breadcrums -->
                @include ('app._templates.breadcrums')
                <!-- ////////////////////////////////////////////////////////////////// -->
                <section id="content"  class="container">
                    @yield ('content')
                </section>
            </div>
        </div>
        <!-- //////////////////////////////////////////////////////////////////////////// -->
        <!-- footer html -->
        @include ('app._templates.footer')
    </body>
</html>
    