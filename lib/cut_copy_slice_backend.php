<?php
/**
 * cut_copy_slice_backend class - basic backend functions for the addon and its plugins.
 */
class cut_copy_slice_backend
{
    public static function settings($key = null, $default = null)
    {
        return rex_config::get('cut_copy_slice', $key, $default);
    }

    /**
     * Selects a value of a slice from the database.
     *
     * @param (int)    $slice_id ID of the slice
     * @param (string) $key      name of the value
     * @param (mixed)  $default  if the value is not contained in the database or set to NULL return this value (default is NULL)
     *
     * @return (mixed) The slice's value
     */
    public static function getValueOfSlice($slice_id, $key, $default = null)
    {
        $slice_id = (int) $slice_id;
        $value = $default;

        if (!is_nan($slice_id) && $slice_id > 0) {
            $sql = rex_sql::factory();
            $sql->setTable(rex::getTablePrefix().'article_slice');
            $sql->setWhere(['id' => $slice_id]);
            $sql->select();

            if ($sql->hasValue($key)) {
                $value = $sql->getValue($key);
            }

            unset($sql);
        }

        return $value;
    }
    /**
     * Initializes the addon in the backend.
     */
    public static function init(rex_extension_point $ep)
    {
        // only aexecute this function within the backend and when a user is logged in
        if (rex::isBackend() && rex::getUser()) {
            // let's register the permission for this addon / plugin
            static::addPerm();

            if (false !== strpos(rex_request('page'), 'content/edit')) {
                if (!static::plugin()) {
                    // hook into SLICE_SHOW extension point so we can change the display of the slice a bit
                    rex_extension::register('SLICE_SHOW', ['cut_copy_slice_backend', 'showSlice'], rex_extension::EARLY);
                }

                // and only on content/edit pages we load the css and js files
                $package = static::package();

                // and load assets
                rex_view::addCssFile($package->getAssetsUrl('css/be.css'));
                rex_view::addJsFile($package->getAssetsUrl('js/be.js'));
            }
        }
    }

    /**
     * Retrieves the permission name by getting (a) the addon name and (b) the plugin name (if
     * this class is extending a plugin_backend class).
     *
     * @return (string) e.g. "cut_copy_slice[status]" or "cut_copy_slice[]"
     */
    public static function getPermName()
    {
        $perm = '';
        if ($addon = static::addon()) {
            $perm = $addon->getName();

            $suffix = '';

            if ($plugin = static::plugin()) {
                $suffix = $plugin->getName() . (!empty($suffix) ? '_' : '') . $suffix;
            }

            $perm .= '[' . $suffix . ']';
        }

        unset($addon, $plugin, $suffix);
        return $perm;
    }

    /**
     * Registers a permisson in Redaxo.
     */
    public static function addPerm()
    {
        if ($perm = static::getPermName()) {
            if (!rex_perm::has($perm)) {
                $group = preg_match('/\[\]$/', $perm) ? rex_perm::GENERAL : rex_perm::OPTIONS;

                $name = 'perm_description';
                if ($plugin = static::plugin()) {
                    $name = $plugin->getName() . '_' . $name;
                }

                rex_perm::register($perm, static::package()->i18n($name), $group);
            }
            return $perm;
        }

        return false;
    }

    /**
     * Checks if a user has the permission to edit a module AND if the user has the
     * permission to use this addon / plugin.
     *
     * @param rex_user $user The user to check
     * @param  (number)            the id of the module
     *
     * @return bool TRUE if the user has all neccessary rights
     */
    public static function hasModulePerm(rex_user $user, $module_id)
    {
        if (!$user->hasPerm('admin[]')) {
            if (static::getPermName()) {
                if (!$user->hasPerm(static::getPermName())) {
                    return false;
                }
            }
        }

        return $user->getComplexPerm('modules')->hasPerm($module_id);
    }

    /**
     * Wraps a LI around the slice within the backend and call
     * a custom extension point SLICE_SHOW_cut_copy_slice_BE we can use to hook
     * in with our plugins.
     *
     * @return string the slice content
     */
    public static function showSlice(rex_extension_point $ep)
    {
        $slice_content = $ep->getSubject();

        $slice_content = rex_extension::registerPoint(new rex_extension_point(
            'SLICE_SHOW_cut_copy_slice_BE',
            $slice_content,
            $ep->getParams()
        ));

        return $slice_content;
    }

    public static function addButton(rex_extension_point $ep, array $btn)
    {
        $items = (array) $ep->getSubject();
        $items[] = $btn;
        $ep->setSubject($items);
    }
}
