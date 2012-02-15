<?php require( '../../../wp-load.php' ); ?>
<?php
$options = get_option('int_nav_options');
$p = initIntNav (); 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" >
<head>  <meta name="robots" content="noindex,nofollow" />
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <title>Intuitive Navigation Frame Content</title>
  <?php if ( ( trim( $options['int_nav_style_url'] ) == "" )  &&  ( $options['int_nav_style'] == 1 ) ) { ?>
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_url'); ?>" type="text/css" media="screen" />
  <?php } elseif ( ( trim( $options['int_nav_style_url'] ) != "" )  &&  ( $options['int_nav_style'] == 1 ) ) { ?>
    <link rel="stylesheet" href="<?php echo trim( $options['int_nav_style_url'] ); ?>" type="text/css" media="screen" />
  <?php } ?>
  <?php if ( trim( $options['int_nav_style_embed'] ) != "" ) { ?>
  <style TYPE="text/css">
     <?php echo trim( $options['int_nav_style_embed'] ); ?>
  </style>    
  <?php } ?>
</head>
<body class="int-nav-body">
<?php 
buildIntNav( $p ); 
?>
</body>
</html>
