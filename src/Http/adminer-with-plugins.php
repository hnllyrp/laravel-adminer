<?php

if (!function_exists('adminer_object')) {
    /**
     * 加载 adminer 插件
     * @return AdminerPlugin
     */
    function adminer_object()
    {
        // required to run any plugin
        include_once __DIR__ . "/Plugins/plugin.php";

        // autoloader
        foreach (glob(__DIR__ . "/Plugins/*.php") as $filename) {
            include_once "$filename";
        }

        $designs = array();
        foreach (glob(__DIR__ . "/../../public/designs/*", GLOB_ONLYDIR) as $file) {
            $design = basename($file);
            $designs["vendor/adminer/designs/{$design}/adminer.css"] = basename($design);
        }

        $plugins = [
            // specify enabled plugins here
            // new AdminerDumpXml,
            // new AdminerTinymce,
            // new AdminerFileUpload("data/"),
            // new AdminerSlugify,
            // new AdminerTranslation,
            // new AdminerForeignSystem,
            new AdminerTablesFilter,
            new AdminerDesigns($designs),
        ];

        /* It is possible to combine customization and plugins:
        class AdminerCustomization extends AdminerPlugin {
        }
        return new AdminerCustomization($plugins);
        */

        return new AdminerPlugin($plugins);
    }
}


