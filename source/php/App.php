<?php

namespace wpPageForTerm;

class App
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'enqueueStyles'));
        add_action('admin_enqueue_scripts', array($this, 'enqueueScripts'));

        $this->cacheBust = new \wpPageForTerm\Helper\CacheBust();
    }

    /**
     * Enqueue required style
     * @return void
     */
    public function enqueueStyles()
    {
        wp_register_style(
            'wp-page-for-term-css',
            WP_PLUGIN_PAGE_FOR_TERM_URL . '/dist/' .
            $this->cacheBust->name('css/wp-page-for-term.css')
        );

        wp_enqueue_style('wp-page-for-term-css');
    }

    /**
     * Enqueue required scripts
     * @return void
     */
    public function enqueueScripts()
    {
        wp_register_script(
            'wp-page-for-term-js',
            WP_PLUGIN_PAGE_FOR_TERM_URL . '/dist/' .
            $this->cacheBust->name('js/wp-page-for-term.js')
        );

        wp_enqueue_script('wp-page-for-term-js');
    }
}
