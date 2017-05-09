@extends('layouts.admin')
@section('content')

<table id="setting_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="15%">Id</th>
            <th width="25%">Seller Status</th>
            <th width="20%">Created Time</th>  
            <th width="20%">Updated Time</th>           
            <th width="10%">Actions</th>
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){ 
        oTable =  $('#setting_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 20,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/setting/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },            
            { data: 'status', name: 'status' },
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'updated_at', name: 'updated_at' },
            ],
            aoColumnDefs: [
            {
                bSortable : false,
                aTargets : [ 4 ]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var status;
                if(aData.status == 1)
                {
                    status = "Active";
                }
                else
                {
                    status = "InActive";
                }   
                $('td:eq(1)', nRow).html(status);                        
                $('td:eq(4)', nRow).html('<a href="<?php echo url("/admin/setting") ?>/'+aData.id+'/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection