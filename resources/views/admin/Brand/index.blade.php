@extends('layouts.admin')
@section('content')

<table id="brand_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>            
            <th width="15%">Image</th>
            <th width="50%">Brand Name</th> 
            <th width="15%">Status</th>           
            <th width="10%">Actions</th>
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){ 
        oTable =  $('#brand_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/brand/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },            
            { data: 'image', name: 'image' },
            { data: 'brand', name: 'brand' },
            { data: 'status', name: 'status' },
            { data: 'updated_at', name: 'updated_at' }
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
                $('td:eq(3)', nRow).html(status);
                var Image = "<?php echo asset("public/asset/brand/") ?>/"+aData.image;  
                $('td:eq(1)', nRow).html('<img src="'+Image+'"  width="50" height="50" >');
                $('td:eq(4)', nRow).html('<a href="<?php echo url("/admin/brand") ?>/'+aData.id+'/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection