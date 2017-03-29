@extends('layouts.admin')
@section('content')

<table id="city_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>         
            <th width="25%">City</th>
            <th width="15%">Status</th>                      
            <th width="10%">Created At</th>          
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){
        
        oTable =  $('#city_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 100,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/city/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                { data: 'id', name: 'id' },               
                { data: 'name', name: 'name' },                
                { data: 'status', name: 'status' },             
                { data: 'created_at', name: 'created_at' },               
            ],
            aoColumnDefs: [
                {
                    bSortable : false,
                    aTargets : [ 3 ]
                },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var status;
                if(aData.status == 1){
                    status = "Active";
                }else{
                    status = "InActive";
                }   
                $('td:eq(2)', nRow).html(status);
                $('td:eq(3)', nRow).html('<a href="<?php echo url("/admin/city") ?>/'+aData.id+'/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection 