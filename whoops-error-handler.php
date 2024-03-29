<?php
/**
 * Whoops PHP Error Handler for your WordPress.
 *
 * There are some themes that you can use.
 * Define a const `RD_WHOOPS_THEME` in wp-config.php with value
 *  'default-original' # default whoops theme
 *  'gray'
 *  'material-dark'
 *  'material-dark-contrast'
 *  'original-optimized'
 *
 * Author: Andrei Pisarevskii
 * Author Email: renakdup@gmail.com
 * Author Site: https://wp-yoda.com/en/
 *
 * Version: 1.0
 * Source Code: https://github.com/renakdup/whoops-error-handler
 * Licence: MIT License
 */

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

if ( wp_get_environment_type() === 'production'
     || ! class_exists( Run::class )
     || ! class_exists( PrettyPageHandler::class )
) {
	return;
}

const RD_WHOOPS_THEMES = [
	'default-original', # default whoops theme
	'gray',
	'material-dark',
	'material-dark-contrast',
	'original-optimized',
];

$whoops = new Run();
$whoops->allowQuit( false );
$handler = new PrettyPageHandler();

$define_theme = function () use ( $handler ) {

	$handler->addResourcePath( __DIR__ . '/whoops/themes/' );

	if ( defined( 'RD_WHOOPS_THEME' ) && RD_WHOOPS_THEME === 'default-original' ) {
		return;
	} else if (
		defined( 'RD_WHOOPS_THEME' )
		&& in_array( RD_WHOOPS_THEME, RD_WHOOPS_THEMES )
	) {
		$handler->addCustomCss( RD_WHOOPS_THEME . '.css' );
		return;
	}

	$handler->addCustomCss( 'material-dark-contrast.css' );
};

$define_theme();
$whoops->pushHandler( $handler );
$whoops->register();
