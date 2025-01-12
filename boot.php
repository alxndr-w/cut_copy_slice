<?php

    rex_extension::register('PACKAGES_INCLUDED', ['cut_copy_slice', 'init'], rex_extension::EARLY);

    /**
     * Initialize the plugin.
     */
    rex_extension::register('PACKAGES_INCLUDED', ['cut_copy_slice_cutncopy', 'init']);
