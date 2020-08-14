<!DOCTYPE html>
<html>
@include('layouts.header')
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
    @include('layouts.menu')
  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper pt-3">
    <!-- Content Header (Page header) -->
    <div class="container">
        <div class="row">
            <div class="col-12">
                @include('partials.form-status')
            </div>
        </div>
    </div>


    @yield('content')
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  {{-- <footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="http://adminlte.io">DSHR</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 3.0.2
    </div>
  </footer> --}}

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

    @include('layouts.footer')
    
    @if (Session::has('messageErr'))
        <script type="text/javascript">
            $(function(){
                Lobibox.alert('error', 
                    {   
                        title: "{{trans('auth.messages')}}",
                        msg: "{{ Session::get('messagePms') }}"
                     });
            });
        </script>
    @endif
    @if (Session::has('messageSS'))
        <script type="text/javascript">
            $(function(){
                Lobibox.alert('success', 
                    {   
                        title: "{{trans('auth.messages')}}",
                        msg: "{{ Session::get('messageSS') }}"
                     });
            });
        </script>
    @endif
    @yield('footer_scripts')
</body>
</html>