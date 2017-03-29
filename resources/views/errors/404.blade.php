<!DOCTYPE html>
<html>
<head>
    <title>#404 Error | JobBookers</title>
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,400i,500,500i,600,600i,700,700i,800,800i" rel="stylesheet">
    <link media="all" type="text/css" rel="stylesheet" href="<?php echo url('/'); ?>/resources/assets/css/animate.css">
</head>
<body class="">
    <section>
    <?php $baseUrl = url('/'); ?>
        <div class="page-logo">
            <a href="<?php echo $baseUrl; ?>" class="navbar-brand block">
                <img src= "<?php echo $baseUrl; ?>/public/app/build/images/logo.png" class="m-r-sm" alt="JobBookers">
            </a>
        </div>   
        <div class="container animated fadeInUp">            
            <section class="panel-404">
                <div class="page-title">
                    <h3 class='errorMsg'>#404 Error, Page Not Found.</h3>
                    <h3>Whoops, looks like something went wrong ...</h3>
                </div>
                <div class="page-content">
                    <p>The page you requested was not found, and we have a fine guess why.</p>
                    <ul class="disc">
                        <li>If you typed the URL directly, please make sure the spelling is correct.</li>
                        <li>If you clicked on a link to get here, the link is outdated.</li>
                    </ul>
                    <ul class="disc links">
                        <li>
                            <a href="<?php echo $baseUrl ?>">Home Page</a> |
                            <a href="<?php echo $baseUrl ?>/#/login">Login</a> |
                            <a href="<?php echo $baseUrl ?>/#/register">Register</a>
                        </li>
                    </ul>
                </div>                           
            </section>
        </div>
        <div class="page-footer">
            <p>Copyright &copy; <?php echo  date('Y'); ?> JobBookers.ch. All rights reserved.</p>
        </div> 
    </section>

</body>
</html>
<style>
    body {
        font-family: "Raleway",sans-serif;
        font-size: 16px;
        background-color: #fff;
        color: #333;
        font-family: "Raleway","Open Sans","Helvetica Neue",Helvetica,Arial,sans-serif;    
        text-align: center;
    }
    ul li {list-style: none;}
    .container{width:50%; margin: 10% auto;}
    .container a{ color: #333; text-decoration: none; font-weight: bold; }
    .container a:hover{color:#FFC420}
    .page-logo{background-color: #333; padding: 10px 0px;}
    .page-footer{
        background-color:#333;
        padding: 10px 0px;
        color: #fff;
        position: absolute;
        right: 0;
        bottom: 0;
        left: 0;
    }
    .errorMsg {font-size: 30px;}
    .animated {
    animation-duration: 1s;
    animation-fill-mode: both;
}
</style>
