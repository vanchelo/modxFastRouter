<?php

$settings = array();

$tmp = array(
    'paramsKey' => array(
        'xtype' => 'textarea',
        'value' => 'fastrouter',
        'area' => 'general',
    ),
);

foreach ($tmp as $k => $v) {
    /* @var modSystemSetting $setting */
    $setting = $modx->newObject('modSystemSetting');
    $setting->fromArray(array_merge(
        array(
            'key' => PKG_NAME_LOWER . '.' . $k,
            'namespace' => PKG_NAME_LOWER,
        ), $v
    ), '', true, true);

    $settings[] = $setting;
}

unset($tmp);

return $settings;
