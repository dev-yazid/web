@extends('layouts.admin')
@section('content')
<table id="language_label" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="10%">Url / Title</th>
            <th width="25%">English (Fixed)</th>           
            <th width="25%">Arabic</th>
            <th width="20%">Comments</th>
            <th width="10%">Action</th>

            <!-- <th width="10%">Del</th> -->
        </tr>
    </thead>
</table>

<script>
    $(document).ready(function(){            
        oTable =  $('#language_label').DataTable({
            processing      : true,
            serverSide      : true,
            autoWidth       : false,
            bRetrieve       : true,
            iDisplayLength  : 50,

            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/language/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'page_url', name: 'page_url' },
                { data: 'label', name: 'label' },     
                { data: 'changed_label', name: 'changed_label' },
                { data: 'comments', name: 'comments' },
                { data: 'created_at', name: 'created_at' },
            ],
            aoColumnDefs: [
                {
                    //bSortable : false,
                    //aTargets : [ 3 ]
                },
            ],
            fnCreatedRow: function(nRow, aData, iDataIndex){
                var k = iDataIndex + 1;
                var german_label = aData.changed_label || '';
                var english_name = aData.label || '';
                var page_url = aData.page_url || '';
                var comments = aData.comments || '';

                $('td:eq(1)', nRow).html('<input id="skuid" type="hidden" name="skuid" value=' + aData.id + '><a href="javascript:;" id="page_url' + k + '" data-type="text" data-pk="1" data-placement="right" class="page_url" data-original-title="Page Url / Title">' + page_url + '</a>');

                $('td:eq(3)', nRow).html('<input id="skuid" type="hidden" name="skuid" value=' + aData.id + '><a href="javascript:;" id="' + k + '" data-type="text" data-pk="1" data-placement="right" class="translated_name" data-original-title="Enter German Label">' + german_label + '</a>');

                $('td:eq(4)', nRow).html('<input id="skuid" type="hidden" name="skuid" value=' + aData.id + '><a href="javascript:;" id="translated_name' + k + '" data-type="text" data-pk="1" data-placement="right" class="comments" data-original-title="Enter Comments">' + comments + '</a>');

                $('td:eq(5)', nRow).html('<a href="<?php echo url("/admin/language") ?>/'+aData.id+'/edit"><i class="fa icon-muted fa-pencil icon-space"></i></a>'+'<a href="<?php echo url("/admin/language") ?>/'+aData.id+'"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
            },
            initComplete: function (settings, json) {
                $('.translated_name').editable();
                $('.page_url').editable();
                $('.comments').editable();
            },

            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }

        });

        $("#language_label").on("click", ".editable-submit", function (e) {           
            
            var base_url = {!! json_encode(url('/')) !!};
            var lable_id = $(this).closest('td').find("#skuid").val();
            
            var lable    = $(this).closest('td:nth-child(4)').find('.editable-input input:text').val();
            var page_url = $(this).closest('td:nth-child(2)').find('.editable-input input:text').val();
            var comments = $(this).closest('td:nth-child(5)').find('.editable-input input:text').val();
           
            $.ajax({
                data: {
                    "translated_lable" : lable,
                    "page_url"         : page_url,
                    "comments"         : comments,
                    "lable_id"         : lable_id,
                    // 'csrftoken' : '{{ csrf_token() }}' 
                },
                method: 'GET',
                headers: { 'csrftoken' : '{{ csrf_token() }}' },
                url: base_url + '/admin/language/updateLabel',
                success: function (attr) {
                    //$(".row_attributes").html(attr); 
                }
            });
        });

        $('#language_label').on('draw.dt', function () { //alert('d');
            $('.translated_name').editable();
            $('.page_url').editable();
            $('.comments').editable();
        });       
    });
</script>
@endsection