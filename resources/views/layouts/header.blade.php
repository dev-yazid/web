<header class="bg-dark dk header navbar navbar-fixed-top-xs">
    <div class="navbar-header aside-md header-part">
        <a class="btn btn-link visible-xs" data-toggle="class:nav-off-screen,open" data-target="#nav,html">
            <i class="fa fa-bars"></i>
        </a>
        <a href="javascript:void(0);" class="navbar-brand" data-toggle="fullscreen">
            {{ HTML::image('/public/app/build/images/logo.png', 'JobBookers', array('class' => 'm-r-sm')) }}
        </a>
       <!--  <a class="btn btn-link visible-xs" data-toggle="dropdown" data-target=".nav-user">
            <i class="fa fa-cog"></i>
        </a> -->
    </div>

    <ul class="nav navbar-nav navbar-right m-n hidden-xs nav-user">
        <li class="my-dashboard">
            <a href="{{ url('/admin/dashboard')}}">
                <i class="fa fa-tachometer" aria-hidden="true"></i>
                <span>My Dashboard</span>
            </a>
        <li>
        <li class="dropdown">
            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown">
                <span class="thumb-sm avatar pull-left">
                   <?php
                    if(Auth::check())
                    {                        
                        $profileImage  = Auth::user()->profile_image;
                        $defaultPath = URL::to('/public/asset/User/Profile/thumb').'/'.'avatar.jpg';
                        if($profileImage && $profileImage !="")
                        {
                            $profileImage;
                            $imgPath     = URL::to('/public/asset/User/Profile/thumb').'/'.$profileImage;
                            if (file_exists($imgPath)) 
                            {
                                $imgPath = $defaultPath;;
                            }
                            else
                            {
                                $imgPath = $imgPath;
                            } 
                        }
                        else
                        {
                            $imgPath = $imgPath; 
                        }                   
                    }
                    ?>
                    <img class="m-r-sm" alt="Admin" src="<?php echo $imgPath; ?>">                
                </span>
                <?php
                if(Auth::check())
                { 
                    Auth::user()->firstname.Auth::user()->lastname;
                    $authId = Auth::user()->id;
                }
                ?>
                <b class="caret"></b>
               <?php /*  echo $base_url="http://".$_SERVER['SERVER_NAME'].$_SERVER["REQUEST_URI"]; */ ?>
            </a>

            <ul class="dropdown-menu animated fadeInRight">
                <span class="arrow top"></span>                            
                <li><a href="<?php echo url("/admin/user/".$authId."/edit") ?>">Edit Profile</a></li> 
                <li class="divider"></li>              
                <li><a href="{{ url('/admin/logout') }}">Logout</a></li>
            </ul>
        </li>
    </ul>      
</header>
