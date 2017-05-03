@extends('layouts.admin')
@section('content') 
<table id="users_index" class="table table-striped m-b-none">
    <thead> 
        <tr>
            <th width="10%">Id</th>
            <th width="15%">Name (c)</th> 
            <th width="15%">Email (C)</th>
           <!--  <th width="10%">Verified(c)</th> -->
            <th width="15%">Name (S)</th> 
            <th width="15%">Email (S)</th>
            <!-- <th width="10%">Verified(S)</th> -->
            <th width="15%">User Type</th>
            <!-- <th width="10%">Verified</th> -->
            <th width="10%">Status</th>
            <th width="10%">Actions</th> 
        </tr>
    </thead>
</table>
<script>
    $(document).ready(function(){ 
        oTable =  $('#users_index').DataTable({
            "bProcessing": true,    
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 25,
            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/user/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name'},           
            { data: 'customer_email', name: 'customer_email' },
            /*{ data: 'mobile_verified', name: 'mobile_verified' },*/
            { data: 'seller_name', name: 'seller_name'},           
            { data: 'email', name: 'email' },
            /*{ data: 'seller_mobile_verified', name: 'seller_mobile_verified' },*/
            { data: 'usertype', name: 'usertype' },
            /*{ data: 'email_verified', name: 'email_verified' },*/
            { data: 'status', name: 'status' }, 
            { data: 'id', name: 'id' },
            ],
            aoColumnDefs: [
            {               
                bSortable : false,
                aTargets : [ 7 ]
            },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex) {
                if(aData.mobile_verified == "Yes" && aData.seller_mobile_verified == "Yes"){
                    user_type = "Both";
                }
                else if(aData.mobile_verified == "Yes" && aData.seller_mobile_verified == "No")
                {
                   user_type = "Buyer";
                }
                else if(aData.mobile_verified == "No" && aData.seller_mobile_verified == "Yes")
                {
                   user_type = "Seller";
                }
                else
                {
                    user_type = "Not Verified";
                }

                var status;               
                if(aData.status == 1){
                    status = "Active";
                }else{
                    status = "InActive";
                }
                $('td:eq(5)', nRow).html(user_type);  
                $('td:eq(6)', nRow).html(status);
                $('td:eq(7)', nRow).html('<a href="<?php echo url("/admin/user") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');         
            },
            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id); 
                /*$("#users_index thead tr").css('display','none');*/
            }
        });         
    });
 
</script>
@endsection