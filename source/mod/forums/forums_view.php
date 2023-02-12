<?php
    if(!defined('IN_POWERBOARD')){
        exit('Access Denied');
    }
    $tid=gpcget('tid');
    $page=gpcget('page')?gpcget('page'):1;
    $topic=($db->query('SELECT * FROM forums_topic WHERE tid='.$tid))->fetch_array(MYSQLI_ASSOC);
    $topic['profile']=getuserprofile($topic['author']);
    $topic['floor']=1;
    $res=$db->query('SELECT * FROM forums_post WHERE tid='.$tid.' ORDER BY floor ASC');
    if($res){
        while($row=$res->fetch_array(MYSQLI_ASSOC)){
            $post[$row['floor']]=$row;
            $post[$row['floor']]['profile']=getuserprofile($row['author']);
        }
    }
    $navtitle=$topic['title'].' - '.lang('common','topic');
    include template('forums/view');
?>