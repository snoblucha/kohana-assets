<?php

Route::set( 'css', 'css/<action>(/<id>)' )->defaults( array( 'controller' => 'Css', 'action' => 'index', ) );
Route::set( 'scripts', 'scripts/<id>' )->defaults( array( 'controller' => 'Scripts', 'action' => 'index', ) );