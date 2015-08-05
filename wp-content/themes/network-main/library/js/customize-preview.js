( function( $ ) {

  //Primary Color
  wp.customize( 'primary_color', function( value ) {
    value.bind( function( to ) {
      $( 'button' ).html( to );
    } );
  } );

  //Secondary Color
  wp.customize( 'secondary_color', function( value ) {
    value.bind( function( to ) {
      $( '' ).html( to );
    } );
  } );

  //Accent Color
  wp.customize( 'accent_color', function( value ) {
    value.bind( function( to ) {
      $( 'aside' ).html( to );
    } );
  } );


  //Page Background Color
  wp.customize( '', function( value ) {
    value.bind( function( to ) {
      $( 'body' ).html( to );
    } );
  } );


  //Post Background Color
  wp.customize( '', function( value ) {
    value.bind( function( to ) {
      $( 'article.post' ).html( to );
    } );
  } );


  //Heading Color
  wp.customize( '', function( value ) {
    value.bind( function( to ) {
      $( 'h1' ).html( to );
      $( 'h2' ).html( to );
      $( 'h3' ).html( to );
      $( 'h4' ).html( to );
    } );
  } );


  //Text Color
  wp.customize( 'text_color', function( value ) {
    value.bind( function( to ) {
      $( 'p' ).html( to );
      $( 'li' ).html( to );
      $( 'ol' ).html( to );
    } );
  } );


  //Link Color
  wp.customize( '', function( value ) {
    value.bind( function( to ) {
      $( 'a' ).html( to );
    } );
  } );


} )( jQuery );