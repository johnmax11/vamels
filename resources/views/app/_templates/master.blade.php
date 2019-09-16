<!DOCTYPE html>
<html lang="en">
    <!-- header html -->
    @include ('app._templates.header')
    <body>
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
    