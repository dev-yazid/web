@extends('layouts.admin')
@section('content')

<table id="response_index" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="15%">Request Id</th>
            <th width="15%">Seller Id</th>
            <th width="15%">Price (SAR)</th>
            <th width="15%">Status</th>
            <th width="20%">Response On</th>       
            <th width="10%">Actions</th>
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){ 
        oTable =  $('#response_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 25,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/response/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                { data: 'id', name: 'id' },            
                { data: 'request_id', name: 'request_id' },
                { data: 'seller_id', name: 'seller_id' },
                { data: 'price', name: 'price' },
                { data: 'status', name: 'status' },
                { data: 'created_at', name: 'created_at' },
                { data: 'updated_at', name: 'updated_at' }
            ],
            aoColumnDefs: [
            {
                bSortable : false,
                aTargets : [6]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                var status;
                var sellerReply;
                var fullName = aData.firstname+' '+aData.lastname;

                if(aData.status == 1)
                {
                    status = "In Processing";
                }
                else if(aData.status == 2)
                {
                    status = "Compleate";
                }
                else
                {
                    status = "Decline";
                }
                
                if(aData.is_seller_replied == 1)
                {
                    sellerReply = "Yes";
                }
                else
                {
                    sellerReply = "No";
                }
                //$('td:eq(1)', nRow).html(fullName);  
                //$('td:eq(5)', nRow).html(sellerReply);
                $('td:eq(4)', nRow).html(status);
                $('td:eq(6)', nRow).html('<a href="<?php echo url("/admin/response") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }
        });         
    });
</script>
@endsection