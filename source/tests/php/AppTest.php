<?php
namespace wpPageForTerm;

use wpPageForTerm\App;

use Brain\Monkey\Functions;
use Mockery;

class AppTest extends \PluginTestCase\PluginTestCase
{
    public function testAddHooks()
    {
        new App();
    
        self::assertNotFalse(has_action('admin_enqueue_scripts', 'wpPageForTerm\App->enqueueStyles()'));
        self::assertNotFalse(has_action('admin_enqueue_scripts', 'wpPageForTerm\App->enqueueScripts()'));
    }

    public function testEnqueueStyles()
    {
        Functions\expect('wp_register_style')->once();
        Functions\expect('wp_enqueue_style')->once()->with('wp-page-for-term-css');

        $app = new App();

        $app->enqueueStyles();
    }

    public function testEnqueueScripts()
    {
        Functions\expect('wp_register_script')->once();
        Functions\expect('wp_enqueue_script')->once()->with('wp-page-for-term-js');

        $app = new App();

        $app->enqueueScripts();
    }
}
