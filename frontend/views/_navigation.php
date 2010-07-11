<!-- #navigation (start) -->
<div id="navigation" class="yui3-menu yui3-menu-horizontal yui3-menubuttonnav">
    <div class="yui3-menu-content">
        <ul>
<?php 
    $i = 0;
    foreach ($menu_items as $index => $data) :
        $i++;
        $class = "";
        $selected = "";
        if ($file_name.".html" === $data["url"]) :
            $selected = " on";
        endif;
        switch (true) :
            case ($i===1) :
                $class = " first-of-type";
                break;
        endswitch;
?>
            <li<?php echo (isset($data["items"])) ? "" : " class=\"yui3-menuitem\""; ?>>
<?php   if (isset($data["items"])) : ?>
                <a class="yui3-menu-label <?php echo $index; ?><?php echo $class; ?>" href="<?php echo $data["url"]; ?>">
                    <em><?php echo $data["text"]; ?></em>
                </a>
<?php   else : ?>
                <a class="yui3-menuitem-content <?php echo $index; ?><?php echo $class; ?>" href="<?php echo $data['url']; ?>"><?php echo $data['text']; ?></a>
<?php   endif; ?>
<?php   if (isset($data["items"])) : ?>
                <div class="yui3-menu">
                    <div class="yui3-menu-content">
                        <ul>
<?php
            $y = 0;
            foreach ($data["items"] as $sub_index => $sub_data) :
                $y++;
                $sub_class = "";
                switch (true) :
                    case ($y===1) :
                        $sub_class = " first-of-type";
                        break;
                endswitch;
?>
                            <li class="yui3-menuitem">
                                <a class="yui3-menuitem-content" href="<?php echo $sub_data["url"]; ?>"><?php echo $sub_data["text"]; ?></a>
                            </li>
<?php       endforeach; ?>
                        </ul>
                    </div>
                </div>
<?php   endif; ?>
            </li>
<?php 
    endforeach; 
?>
        </ul>
    </div>
</div>
<!-- #navigation (end) -->
