<?php

use \Co;

$cid = go(function () {
    echo "co 1 start\n";
    co::yield();
    echo "co 1 end\n";
});

go(function() use($cid){
    while(true) {
        go(function () use ($cid) {
            echo "co 2 start\n";
            co::sleep(10);
            co::resume($cid);
            echo "co 2 end\n";
        });
        var_dump(111);
    }
});