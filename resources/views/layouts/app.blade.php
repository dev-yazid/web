<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>Feeh | Admin Panel </title>
  <meta name="description" content="Nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  
  {{Html::style("/resources/assets/css/bootstrap.css")}}
  {{Html::style("/resources/assets/css/animate.css")}}
  {{Html::style("/resources/assets/css/font-awesome.min.css")}}
  {{Html::style("/resources/assets/css/font.css")}}
  {{Html::style("/resources/assets/css/app.css")}}

    <!--[if lt IE 9]>
    {{Html::script("/resources/assets/js/ie/html5shiv.js")}}
    {{Html::script("/resources/assets/js/ie/respond.min.js")}}
    {{Html::script("/resources/assets/js/ie/excanvas.js")}}
    <![endif]-->
  </head>
  <body class="">
    <section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
      <div class="container aside-xxl">
        <a class="navbar-brand block" href="{{ url('/') }}">
        {{ HTML::image('/public/app/build/images/logo.png', 'Feeh', array('class' => 'm-r-sm')) }}</a>
        <section class="panel panel-default bg-white m-t-lg">
         <header class="panel-heading text-center">
          <strong>
            <?php 
            $action =  Request::segment(2); 
            if( $action === 'login' ) {
              echo 'Sign In';
            }
            else 
            {
              echo 'Forgot Password';
            }
            ?>
          </strong>
        </header>
        @include('errors.common_errors')
        @yield('content')
      </section>
    </div>
  </section>

  <footer id="footer">
    <div class="text-center padder">
      <p>
          Copyright &copy; <?php echo  date('Y'); ?> Feeh. &nbsp; All rights reserved.
      </p>
    </div>
  </footer>

  {{Html::script("/resources/assets/js/jquery.min.js")}}
  {{Html::script("/resources/assets/js/bootstrap.js")}}
  {{Html::script("/resources/assets/js/app.js")}}
  {{Html::script("/resources/assets/js/slimscroll/jquery.slimscroll.min.js")}}
  {{Html::script("/resources/assets/js/app.plugin.js")}}

 

  @yield('scripts')
</body>
</html>