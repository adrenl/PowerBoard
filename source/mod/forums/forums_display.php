<?php
    if(!defined('IN_POWERBOARD')){
        exit('Access Denied');
    }
    $topics=array();
    $fid=gpcget('fid');
    $page=gpcget('page')?gpcget('page'):1;
    $forumname=($db->query('SELECT name FROM forums WHERE fid='.$fid))->fetch_array(MYSQLI_NUM)[0];
    $res=$db->query('SELECT * FROM forums_topic WHERE fid='.$fid .' ORDER BY tid DESC');
    while($row=$res->fetch_array(MYSQLI_ASSOC)){
        $topics[]=$row;
    }
    $navtitle=$forumname.' - '.lang('common','forum');
    include template('forums/display');
?>