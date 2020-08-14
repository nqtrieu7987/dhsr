{{-- FYI: Datatables do not support colspan or rowpan --}}

<script type="text/javascript" src="{{ URL::asset('/js/jquery.dataTables.min.js')}}"></script>
<script type="text/javascript" src="{{ URL::asset('/js/dataTables.bootstrap.min.js')}}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $('.data-table').dataTable({
            "aLengthMenu":[100,50,20],
            "paging": false,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": true,
            "dom": 'T<"clear">lfrtip',
            "sPaginationType": "full_numbers",
            "bPaginate": false,
            "aaSorting": [[0, 'desc']],     //Sắp xếp theo id giảm dần
            'aoColumnDefs': [{
                'bSortable': false,
                'searchable': false,
                'aTargets': ['no-search'],
                'bTargets': ['no-sort']
            }]
        });
    });
</script>