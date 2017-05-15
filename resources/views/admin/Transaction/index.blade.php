@extends('layouts.admin')
@section('content')

<table id="cms_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="20%">IP</th> 
            <th width="10%">CID</th>
            <th width="10%">PID</th>
            <th width="10%">FID</th>
            <th width="10%">Payment</th>
            <th width="15%">Created At</th>           
            <th width="15%">Actions</th>
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){
        
        oTable =  $('#cms_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 25,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/transaction/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },
            { data: 'ip', name: 'ip' },
            { data: 'cid', name: 'cid' },
            { data: 'pid', name: 'pid' },
            { data: 'fid', name: 'fid' },
            { data: 'pay_status', name: 'pay_status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }
            ],
            aoColumnDefs: [
            {
                bSortable : false,
                aTargets : [ 7 ]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
               /* var status;
                if(aData.status == 1)
                {
                    status = "Active";
                }
                else
                {
                    status = "InActive";
                }
                $('td:eq(3)', nRow).html(status); */
                $('td:eq(7)', nRow).html('<a href="<?php echo url("/admin/transaction") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection