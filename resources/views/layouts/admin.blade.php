<!DOCTYPE html>
<html lang="en" class="app">
<head>
    <meta charset="UTF-8">
    <?php $controller_name =  Request::segment(2); ?>
    <title>Feeh Admin</title>
    <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 

    {{Html::style("/resources/assets/css/bootstrap.css")}}
    {{Html::style("/resources/assets/css/animate.css")}}
    {{Html::style("/resources/assets/css/font-awesome.min.css")}}
    {{Html::style("/resources/assets/css/font.css")}}
    {{Html::style("/resources/assets/js/datatables/datatables.css")}}
    {{Html::style("/resources/assets/css/app.css")}} 
    {{Html::script("/resources/assets/js/jquery.min.js")}}
    {{Html::script("/resources/assets/js/jquery.validate.js")}}
    {{Html::script("/resources/assets/js/datatables/jquery.dataTables.min.js")}}
    {{Html::script("/resources/assets/js/datatables/jquery.dataTables.columnFilter.js")}}
    {{Html::script("/resources/assets/js/time_picker/js/bootstrap-timepicker.js")}}

    <!--[if lt IE 9]>
    {{Html::script("/resources/assets/js/ie/html5shiv.js")}}
    {{Html::script("/resources/assets/js/ie/respond.min.js")}}
    {{Html::script("/resources/assets/js/ie/excanvas.js")}}
    <![endif]-->

    @yield('styles')
    <script>
        /* data tables */
        var oTable;
        function  delete_record(id, slider = null) {
            var controller = '<?php echo $controller_name?>';
            if(slider != null) { 
               controller = "sliders";
           }
           var token = '<?php echo csrf_token() ?>';
           var pageurl = '<?php echo url('/admin') ?>/'+ controller + '/' + id;
           var confirm_flag = confirm("Are you sure you want to delete # "+id+"?");
           if(confirm_flag === true) {
            $.ajax({
                url: pageurl,
                method:'DELETE',
                data: {'_token': token},
                success:function(result) {
                    if(slider != null) {
                        jQuery("#"+slider).fadeOut('slow', function() {
                            jQuery("#"+slider).remove();
                        });
                    }
                    else {
                        if(result == 1) {
                            $("#row_"+id).fadeOut('slow', function(){
                                oTable.ajax.reload();
                            });
                        }
                    }
                }
            });
        }
    }   
    </script>
</head>
<body class="">
    <?php $date_format = "Y-m-d";?>
    <section class="vbox ">
        @include('layouts.header')
        <section>
            <section class="hbox stretch">
                @include('layouts.navigation')
                <section id="content">
                    <section class="vbox">
                        <section class="top-margin scrollable padder">
                        <?php list(, $action_name) = explode('@', Route::getCurrentRoute()->getActionName()); ?>
                        
                        @include('layouts.notifications')
                        <section class="panel panel-default">
                                <?php
                                    $content_container = "panel-body";
                                    $content_container = "table-responsive";
                                ?> 
                                <header class="panel-heading">
                                    <strong><?php echo $title_for_layout;?></strong>

                                    <?php if($controller_name != "dashboard" && $controller_name !='request' && $controller_name !='create' && $controller_name !='transaction' && $controller_name !='response') { ?>
                                            <a class="btn btn-xs btn-dark pull-right" href="{{ url('/admin/'.$controller_name.'/create') }}">
                                                <i class="fa fa-plus"></i><?php echo $controller_name ?>
                                            </a>
                                    <?php } ?>
                                    <?php if($controller_name == "language") { ?>
                                    <a class="btn btn-refresh btn-xs btn-dark pull-right" 
                                    href="{{ url('/admin/'.$controller_name.'/refresh') }}"><i class="fa fa-refresh" aria-hidden="true"></i>Refresh</a>
                                    <?php } ?>
                                </header>
                                
                                <div class="<?php echo $content_container ?>">
                                    @include('errors.common_errors')
                                    @yield('content')
                                </div>
                                <footer class="footer">
                                    <p>Copyright &copy; <?php echo  date('Y'); ?> Feeh. &nbsp; All rights reserved.  </p>
                                </footer>
                            </section>
                            <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen, open" 
                            data-target="#nav,html"></a>
                        </section>
                    </section>
                </section>
            </section>
        </section>
    </section>    
    
    {{Html::script("/resources/assets/js/bootstrap.js")}}
    {{Html::script("/resources/assets/js/app.js")}}
    {{Html::script("/resources/assets/js/slimscroll/jquery.slimscroll.min.js")}}
    {{Html::script("/resources/assets/js/ckeditor/ckeditor.js")}}
    {{Html::script("/resources/assets/js/tinymce/tinymce.js")}}

    <script>
        var base_url = {!! json_encode(url('/')) !!};
        tinymce.init({
            selector: 'textarea.tinymce',
            theme: "modern",
            relative_urls: true,
            menubar: true,
            statusbar: true,
            height: 300,
            browser_spellcheck : true ,
            fontsize_formats: "8pt 9pt 10pt 11pt 12pt 26pt 36pt 40pt 46pt 50pt",
            theme: 'modern',
            plugins: [
            "advlist autolink link image lists charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars insertdatetime media nonbreaking",
            "table contextmenu directionality emoticons paste textcolor responsivefilemanager code"
            ],
            toolbar1: "bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | styleselect | fontselect  ",
            toolbar2: "| responsivefilemanager | link unlink anchor | image media | forecolor backcolor  | print preview code | fontsizeselect ",
            image_advtab: false ,
            external_filemanager_path: base_url + "/resources/assets/filemanager/",
            filemanager_title:"File Manager" ,
            external_plugins: { "filemanager" : base_url + "/resources/assets/filemanager/plugin.min.js"},
        });
    </script>

    {{Html::script("/resources/assets/js/app.plugin.js")}}
    {{Html::script("/resources/assets/js/file-input/bootstrap-filestyle.min.js")}}
    {{Html::script("/resources/assets/js/common.js")}}
    
    {{Html::style("/resources/assets/js/datepicker/jquery-datepicker-ui.css")}}
    {{Html::script("/resources/assets/js/datepicker/jquery-datepicker-ui.js")}}

    {{Html::style("/resources/assets/js/select2/select2.css")}}
    {{Html::script("/resources/assets/js/select2/select2.min.js")}}
    {{Html::script("/resources/assets/js/select2/select2.min.js")}}

    {{Html::script("/resources/assets/js/editable/bootstrap-editable.js")}}
    {{Html::script("/resources/assets/js/editable/jquery.mockjax.js")}}
    
       
    


    @yield('scripts')

</body>
</html>