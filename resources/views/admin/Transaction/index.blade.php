@extends('layouts.admin')
@section('content')

<table id="cms_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="10%">cust Id</th> 
            <th width="10%">seller Id</th>
            <th width="10%">Req Id</th>
            <th width="15%">Is Confirmed</th>
           <!--  <th width="15%">Seller Confirmation</th> -->
            <th width="15%">Created At</th>           
            <th width="10%">Actions</th>
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
            iDisplayLength: 50,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/transaction/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },
            { data: 'cust_id', name: 'cust_id' },
            { data: 'seller_id', name: 'seller_id' },
            { data: 'request_id', name: 'request_id' },
            { data: 'cust_confirmation', name: 'cust_confirmation' },
            /*{ data: 'seller_confirmation', name: 'seller_confirmation' },*/
            { data: 'created_at', name: 'created_at' },
            { data: 'updated_at', name: 'updated_at' }
            ],
            aoColumnDefs: [
            {
                bSortable : false,
                aTargets : [ 6 ]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var cust_confirmation;
                if(aData.cust_confirmation == 1)
                {
                    cust_confirmation = "Yes";
                }
                else
                {
                    cust_confirmation = "No";
                }
                $('td:eq(4)', nRow).html(cust_confirmation); 
                $('td:eq(6)', nRow).html('<a target="_blank" href="<?php echo url("/admin/request") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection