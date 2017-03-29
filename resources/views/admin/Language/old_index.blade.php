@extends('layouts.admin')
@section('content')
<table id="language_label" class="table table-striped m-b-none">
    <thead>
        <tr>
            <th width="10%">Id</th>
            <th width="10%">Code</th> 
            <th width="40%">English (Fixed)</th>           
            <th width="40%">German</th>
        </tr>
    </thead>
</table>

<script>
    $(document).ready(function(){
            
        oTable =  $('#language_label').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            iDisplayLength: 25,

            sPaginationType: "full_numbers",
            ajax: '{{ url('/admin/language/getData') }}',
            sDom: "<'row'<'col-lg-6 leave_filter'><'col-lg-3'l><'col-lg-3'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                { data: 'id', name: 'id' },
                { data: 'lang_code', name: 'lang_code' },
                { data: 'label', name: 'label' },     
                { data: 'changed_label', name: 'changed_label' },
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
              
                $('td:eq(3)', nRow).html('<input id="skuid" type="hidden" name="skuid" value=' + aData.id + '><a href="javascript:;" id="translated_name' + k + '" data-type="text" data-pk="1" data-placement="right" class="translated_name" data-original-title="Enter German Label">' + german_label + '</a>');
            },
            initComplete: function (settings, json) {
                $('.translated_name').editable();
            },

            fnRowCallback: function(nRow, aData, iDisplayIndex) {
                nRow.setAttribute('id',"row_"+aData.id);
            }

        });

        $("#language_label").on("click", ".editable-submit", function (e) {
           
            var lable_id = $(this).closest('td').find("#skuid").val();
            var lable    = $(this).closest('td').find('.editable-input input:text').val();
            var base_url = {!! json_encode(url('/')) !!};
           
            $.ajax({
               data: {
                        "translated_lable" : lable,
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
        });       
    });
</script>
@endsection