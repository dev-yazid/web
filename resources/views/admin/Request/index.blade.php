@extends('layouts.admin')
@section('content')

<table id="brodcast_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            
            <th width="10%">Brand</th>
            <th width="15%">Product</th>
            <th width="10%">Year</th>
            <th width="15%">Responded</th>
            <th width="15%">Status</th>         
            <th width="10%">Actions</th>

        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){ 
        oTable =  $('#brodcast_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 25,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/request/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },
            { data: 'brand', name: 'brand' },
            { data: 'pname', name: 'pname' },
            { data: 'prod_year', name: 'prod_year' },
            { data: 'is_seller_replied', name: 'is_seller_replied' },
            { data: 'status', name: 'status' },
            { data: 'updated_at', name: 'updated_at' }
            ],
            aoColumnDefs: [
            {
                bSortable : false,
                aTargets : [ 5 ]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var status;
                var sellerReply;
              

                if(aData.status == 1)
                {
                    status = "New Request";
                }
                else if(aData.status == 2)
                {
                    status = "In Processing";
                }
                else
                {
                    status = "Compleate";
                }
                
                if(aData.is_seller_replied == 1)
                {
                    sellerReply = "Yes";
                }
                else
                {
                    sellerReply = "No";
                }
        
                $('td:eq(4)', nRow).html(sellerReply);
                $('td:eq(5)', nRow).html(status);
                $('td:eq(6)', nRow).html('<a href="<?php echo url("/admin/request") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection