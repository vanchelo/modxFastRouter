<?php

$plugins = [];

$tmp = [
    'fastrouter' => [
        'file' => 'fastrouter',
        'description' => '',
        'events' => [
            'OnPageNotFound' => [],
            'OnChunkSave' => [],
        ],
    ],
];

foreach ($tmp as $k => $v) {
    /* @var modPlugin $plugin */
    $plugin = $modx->newObject('modPlugin');
    $plugin->fromArray([
        'name' => $k,
        'category' => 0,
        'description' => $v['description'],
        'plugincode' => getSnippetContent($sources['source_core'] . '/elements/plugins/plugin.' . $v['file'] . '.php'),
        'static' => BUILD_PLUGIN_STATIC,
        'source' => 1,
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/plugins/plugin.' . $v['file'] . '.php',
    ], '', true, true);

    $events = [];
    if (!empty($v['events'])) {
        foreach ($v['events'] as $k2 => $v2) {
            /* @var modPluginEvent $event */
            $event = $modx->newObject('modPluginEvent');
            $event->fromArray(
                array_merge([
                    'event' => $k2,
                    'priority' => 0,
                    'propertyset' => 0,
                ], $v2),
                '',
                true,
                true
            );
            $events[] = $event;
        }
        unset($v['events']);
    }

    if (!empty($events)) {
        $plugin->addMany($events);
    }

    $plugins[] = $plugin;
}

unset($tmp, $properties);

return $plugins;
