

<aside class="bg-light lter b-r aside-md hidden-print hidden-xs" id="nav">
   <section class="vbox">
      <section class="w-f scrollable">
         <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
            <nav class="nav-primary hidden-xs">
               <ul class="nav">
               <?php
                  $controller =  Request::segment(2);
                  $class='';
                  if($controller=='dashboard') {
                    $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-tachometer" aria-hidden="true">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Dashboard</span>
                  </a>
                  <ul class="nav lt">
                     <li  class="active">
                        <a href="{{ url('/admin/dashboard')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>My Dashboard</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php
                  $class='';
                  if($controller=='user') {
                    $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-users" aria-hidden="true">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Users</span>
                  </a>
                  <ul class="nav lt">
                     <li  class="active">
                        <a href="{{ url('/admin/user')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Users</span>
                        </a>
                     </li>
                     <li >
                        <a href="{{ url('/admin/user/create')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>Add Users</span>
                        </a>
                     </li>
                     <li >
                        <a href="{{ url('/admin/setting/1/edit')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>User Setting</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php
                  $class='';
                  if($controller=='request') {
                    $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-tasks icon">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Broadcasts</span>
                  </a>
                  <ul class="nav lt">
                     <li>
                        <a href="{{ url('/admin/request')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Request</span>
                        </a>
                     </li>
                     <li>
                        <a href="{{ url('/admin/response')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Response</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php
                  $class='';
                  if($controller=='transaction') {
                   $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-money">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Transactions</span>
                  </a>
                  <ul class="nav lt">
                     <li>
                        <a href="{{ url('/admin/transaction')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Transaction</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php
                  $class='';
                  if($controller=='product') {
                   $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-file-text icon">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Products</span>
                  </a>
                  <ul class="nav lt">
                     <li>
                        <a href="{{ url('/admin/product')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Products</span>
                        </a>
                     </li>
                     <li>
                        <a href="{{ url('/admin/product/create')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>Add Product</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php
                  $class='';
                  if($controller=='brand') {
                     $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-bold" aria-hidden="true">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Brands</span>
                  </a>
                  <ul class="nav lt">
                     <li>
                        <a href="{{ url('/admin/brand')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Brands</span>
                        </a>
                     </li>
                     <li>
                        <a href="{{ url('/admin/brand/create')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>Add Brand</span>
                        </a>
                     </li>
                  </ul>
               </li>
               <?php /*
                  $class='';
                  if($controller=='language') {
                   $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">
                  <i class="fa fa-flag-checkered" aria-hidden="true">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Language</span>
                  </a>
                  <ul class="nav lt">
                     <li>
                        <a href="{{ url('/admin/language')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>All Labels</span>
                        </a>
                     </li>
                     <li>
                        <a href="{{ url('/admin/language/create')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>Add Label</span>
                        </a>
                     </li>
                  </ul>
               </li>
               */ ?>
               <?php
                  $class='';
                  if($controller=='country' || 'state' || 'city') {
                   $class='class="active"';
                  }
                  ?>
               <li <?php echo $class ?>>
                  <a <?php echo $class ?> href="javascript:void(0);">                           
                  <i class="fa fa-globe" aria-hidden="true">
                  <b class="bg-success"></b>
                  </i>
                  <span class="pull-right">
                  <i class="fa fa-angle-down text"></i>
                  <i class="fa fa-angle-up text-active"></i>
                  </span>
                  <span>Location Manager</span>
                  </a>
                  <ul class="nav lt">
                     <!-- <li>
                        <a href="{{ url('/admin/country')}}">
                         <i class="fa fa-angle-right"></i>
                         <span>Countries</span>
                        </a>
                        </li>
                        <li>
                        <a href="{{ url('/admin/state')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>States</span>
                        </a>
                        </li> -->
                     <li>
                        <a href="{{ url('/admin/city')}}">
                        <i class="fa fa-angle-right"></i>
                        <span>Cities</span>
                        </a>
                     </li>
                  </ul>

                  <?php /*
                  $class='';
                  if($controller=='setting') {
                     $class='class="active"';
                  }
                  ?>
                  <li <?php echo $class ?>>
                    <a <?php echo $class ?> href="javascript:void(0);">
                    <i class="fa fa-bold" aria-hidden="true">
                    <b class="bg-success"></b>
                    </i>
                    <span class="pull-right">
                    <i class="fa fa-angle-down text"></i>
                    <i class="fa fa-angle-up text-active"></i>
                    </span>
                    <span>Settings</span>
                    </a>
                    <ul class="nav lt">
                       <li>
                          <a href="{{ url('/admin/setting')}}">
                          <i class="fa fa-angle-right"></i>
                          <span>User Settings</span>
                          </a>
                       </li>
                       <!-- <li>
                          <a href="{{ url('/admin/brand/create')}}">
                          <i class="fa fa-angle-right"></i>
                          <span>Add Brand</span>
                          </a>
                       </li> -->
                    </ul>
                 </li> */ ?>
            </nav>
         </div>
      </section>
      <footer class="footer lt hidden-xs b-t b-light">
      <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-light btn-icon">
      <i class="fa fa-angle-left text"></i>
      <i class="fa fa-angle-right text-active"></i>
      </a>
      </footer>
   </section>
</aside>

