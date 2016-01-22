<div class="row">
    <div class="col-md-4">
    </div>
    <div class="col-md-4">
        <ul class="nav nav-pills">
            <li class="<?php if ($action=="main"){echo "active";}?>"><a href="<?php echo $mod_page.'&action=main'; ?>"><?php echo $lang["menu1"];?></a></li>
            <li class="<?php if ($action=="forms"){echo "active";}?>"><a href="<?php echo $mod_page.'&action=forms'; ?>"><?php echo $lang["menu2"];?></a></li>
            <li class="<?php if ($action=="settings"){echo "active";}?>"><a href="<?php echo $mod_page.'&action=settings'; ?>"><?php echo $lang["menu3"];?></a></li>
        </ul>
    </div>
    <div class="col-md-4">
    </div>
</div>