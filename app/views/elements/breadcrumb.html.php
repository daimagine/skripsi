<?php
/**
 * structure of the $breadcrumb :
 * array of ( url => viewName )
 */
?>

<div id="breadcrumb">
    <ul>
        <?php
            foreach($breadcrumb as $crumbs) {
                $crumb = '';
                //assign the variable
                $url = $crumbs[0];
                $detail = $crumbs[1];
                //debug dude
//                print $url." : ".$detail." <br>";
                //
                //check the active crumb
                if($detail !== '#') {
                    $crumb .= "<li class='active'>";
                    $crumb .= "<a href='$url'>$detail</a> &raquo;";
                } else {
                    $crumb .= "<li>";
                    $crumb .= $url;
                }
                $crumb .= "</li>";
                //finally 
                echo $crumb;
            }
        ?>
    </ul>
</div>

<pre>
<?//=print_r($breadcrumb);?>
</pre>