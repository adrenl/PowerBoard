<?php
    require 'source/functions/functions_application.php';
    $powerboard=new application;
    $powerboard->init();
    $navtitle=lang('common','index');
    $res=$db->query('SELECT * FROM area');
    while($row=$res->fetch_array(SQLITE3_ASSOC)){
        $out[$row['arid']]=array('name'=>$row['name'],'hidden'=>$row['hidden']);
        $res1=$db->query('SELECT * FROM forums WHERE inaid='.$row['arid']);
        while($row1=$res1->fetch_array(MYSQLI_ASSOC)){
            $out[$row['arid']]['childern'][$row1['fid']]=array('fid'=>$row1['fid'],'inaid'=>$row1['inaid'],'name'=>$row1['name'],'description'=>$row1['description'],'totaltopic'=>$row1['totaltopic'],'lasttopic'=>$row1['lasttopic'],'hidden'=>$row1['hidden']);
        }
    }
    include template('common/index');
?>