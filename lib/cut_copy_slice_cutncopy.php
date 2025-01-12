<?php
/**
 * cut_copy_slice_status class - basic functions for the plugin.
 */
class cut_copy_slice_cutncopy extends cut_copy_slice_abstract
{
    /**
     * The name of the plugin.
     *
     * @var string
     */
    protected static $plugin_name = 'cutncopy';

    /**
     * Initializes the plugin.
     */
    public static function init(rex_extension_point $ep)
    {
        if (rex::isBackend()) {
            // call the backend functions
            cut_copy_slice_cutncopy_backend::init($ep);
        }
    }
}
