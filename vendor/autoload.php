<?php
spl_autoload_register(function($class){
    $map=['Controller\\'=>__DIR__.'/../Controller/','Model\\'=>__DIR__.'/../Model/'];
    foreach($map as $prefix=>$base){
        if(str_starts_with($class,$prefix)){
            $rel=substr($class,strlen($prefix));
            $file=$base.str_replace('\\','/',$rel).'.php';
            if(file_exists($file)) require $file;
            return;
        }
    }
});
require_once __DIR__.'/../Config/configuration.php';
