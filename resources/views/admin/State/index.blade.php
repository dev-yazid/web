@extends('layouts.admin')
@section('content')

<table id="state_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="30%">Country</th>
            <th width="30%">State</th>            
            <th width="15%">Status</th>                      
            <th width="15%">Created At</th>
           
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){
        
        oTable =  $('#state_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 50,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/state/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'cname', name: 'cname' },
                { data: 'name', name: 'name' },               
                { data: 'status', name: 'status' },             
                { data: 'created_at', name: 'created_at' },
               
            ],
            aoColumnDefs: [
                {
                    bSortable : false,
                    aTargets : [ 4 ]
                },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var status;
                if(aData.status == 1){
                    status = "Active";
                }else{
                    status = "InActive";
                }   
                $('td:eq(3)', nRow).html(status);

                $('td:eq(4)', nRow).html('<a href="<?php echo url("/admin/state") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>'+'<a href="<?php echo url("/admin/state") ?>/'+aData.id+'/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection 