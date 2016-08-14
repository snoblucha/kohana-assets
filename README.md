Assets module pipeline for Kohana
=================================

This module merges assets into single file.


Example Usage:

    Css::fe()->add( '/bower_components/dropzone/dist/min/dropzone.min.css', 'dropzone' );
    Css::fe()->add( '/bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.css', 'tagsinput' );
    Css::fe()->add( '/css/jquery-ui-1.10.3.custom.min.css' );
    Css::fe()->add( '/css/main.css' );

    JS::footer()->add( 'bower_components/bootstrap/dist/js/bootstrap.min.js', 'bootstrap' );
    JS::footer()->add( 'bower_components/metisMenu/dist/metisMenu.min.js', 'metisMenu' );
    JS::footer()->add( 'bower_components/dropzone/dist/min/dropzone.min.js', 'dropzone' );
    JS::footer()->add( 'bower_components/bootstrap-tagsinput/dist/bootstrap-tagsinput.js', 'bootstrap-tagsinput' );
    JS::footer()->add( 'bower_components/typeahead.js/dist/typeahead.bundle.js', 'typeahead.js' );

    //JS::footer()->add( 'bower_components/typeahead.js/dist/bloodhound.js', 'bloodhound.min.js' );
    JS::footer()->add( 'js/main.js', 'main' );



In Your template then add 

    <link rel="stylesheet" href="/css/index">

## Config

In config you can define start path for assets group. For exampe for CSS you may define.

    <?php
    return array(
        'default' => array(
            'dir' => 'css',
            'cached' => false, # should cache asset, disabled for development
        ),

        'frontend' => array(
            'dir' => ''
        ),

        'backend' => array(
            'dir' => 'admin/css'
        ),

    );



## Css

Or any action available for CSS. There are predefined groups for asseets. in `Css` it is `::fe()` for frontend and path `/css/index`,
`::be()` for backend assets and path `/css/admin` and `::fePrint()` for print stylesheet at `/css/print`

For custom keys there is defined path `/css/get/:id` that retrieves the correct key. For exampe `/css/get/test` will retrieve the
combined assets for `Css::instance('test')`.

## Js

It has a bit different key predefined. `JS::footer` is intended for combining script for the footer. On the place you put it

    <script src="/scripts/footer"></script>






