<ul class="nav navbar-nav navbar-right">
    <li class="dropdown">
      <a href="#" class="dropdown-toggle" data-toggle="dropdown">   <?php echo lang('msg_hello');?>
       <?php
       if(isset($_SESSION['user'])){
        $user=$_SESSION['user'][0];
        echo $user->user_name;
    }

    ?><b class="caret"></b></a>
    <ul class="dropdown-menu">
        <li><a href="<?php echo base_url().'admin/dashboard/update_profile';?>" class="red"><?php echo lang('msg_update_profile');?></a></li>
        <li><a href="<?php echo base_url().'admin/dashboard/update_pwd';?>" class="red"><?php echo lang('msg_update_pwd');?></a></li>
        <li><a href="<?php echo base_url().'admin/dashboard/logout';?>"><?php echo lang('msg_logout');?></a></li>
    </ul>
</li>
</ul>