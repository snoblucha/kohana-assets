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

