<?php
/**
 * Whoops PHP Error Handler for your WordPress.
 * Available themes:
 *   'material-dark-smooth'
 *   'material-dark'
 *   'gray'
 *   'original-optimized'
 *   'default-original' # default whoops theme
 *
 * Author: Andrei Pisarevskii
 * Author Email: renakdup@gmail.com
 * Author Site: https://wp-yoda.com/en/
 *
 * Source Code: https://github.com/renakdup/whoops-error-handler
 * Licence: MIT License
 */

namespace Pisarevskii\WhoopsErrorHandler;

use Whoops\Handler\PrettyPageHandler;
use Whoops\Run;

class ErrorHandler {
	// Whoops default original theme
	public const ORIGINAL_THEME = 'default-original';
	protected const DEFAULT_PLUGIN_THEME = 'material-dark-smooth';
	public const WHOOPS_THEMES = [
		self::DEFAULT_PLUGIN_THEME,
		'material-dark',
		'gray',
		self::ORIGINAL_THEME,
		'original-optimized',
	];

	/**
	 * @var string
	 */
	protected $theme;

	/**
	 * @var array
	 */
	protected $prohibited_envs = [];

	/**
	 * @throws \Exception
	 */
	public function __construct( string $theme = self::DEFAULT_PLUGIN_THEME ) {
		if ( ! defined( 'WPINC' ) ) {
			throw new \Exception( "Perhaps WordPress you run Whoops before `WPINC` defined or you run it in non WordPress environment." );
		}

		if ( ! in_array( $theme, self::WHOOPS_THEMES ) ) {
			throw new \Exception( "The theme `{$theme}` is not found." );
		}

		$this->theme = $theme;
	}

	public function init() {
		$this->prohibited_envs = apply_filters( "pisarevskii/whoops-error-handler/prohibited-envs", [ "production" ] );

		if ( ! in_array( wp_get_environment_type(), $this->prohibited_envs )
		     || apply_filters( "pisarevskii/whoops-error-handler/not-enable", false )
		) {
			return;
		}

		if ( ! WP_DEBUG || ! WP_DEBUG_DISPLAY ) {
			return;
		}

		$whoops = new Run();
		$whoops->allowQuit( false );
		$handler = new PrettyPageHandler();

		$setup_theme = function () use ( $handler ) {
			if ( $this->theme === self::ORIGINAL_THEME ) {
				return;
			}

			$handler->addResourcePath( __DIR__ . '/whoops/themes/' );
			$handler->addCustomCss( $this->theme . '.css' );
		};

		$setup_theme();
		$whoops->pushHandler( $handler );
		$whoops->register();
	}
}
