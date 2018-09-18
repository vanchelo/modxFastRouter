<?php

$snippets = [];

$tmp = [
    'fastrouter' => [
        'file' => 'fastrouter',
        'source' => true,
        'description' => '',
    ],
];

foreach ($tmp as $k => $v) {
    /* @avr modSnippet $snippet */
    $snippet = $modx->newObject('modSnippet');
    $snippet->fromArray([
        'id' => 0,
        'name' => $k,
        'description' => $v['description'],
        'snippet' => getSnippetContent($sources['source_core'] . '/elements/snippets/snippet.' . $v['file'] . '.php'),
        'static' => BUILD_SNIPPET_STATIC,
        'source' => (bool) $v['source'],
        'static_file' => 'core/components/' . PKG_NAME_LOWER . '/elements/snippets/snippet.' . $v['file'] . '.php',
    ], '', true, true);

    $snippets[] = $snippet;
}

unset($tmp);

return $snippets;
