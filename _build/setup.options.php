<?php

/**
 * @var array $options
 */

$exists = $chunks = false;
$output = null;

switch ($options[xPDOTransport::PACKAGE_ACTION]) {
    case xPDOTransport::ACTION_INSTALL:
        break;

    case xPDOTransport::ACTION_UPGRADE:
        if (!empty($options['attributes']['chunks'])) {
            $chunks = '<ul id="formCheckboxes" style="height:200px;overflow:auto;">';
            foreach ($options['attributes']['chunks'] as $k => $v) {
                $chunks .= '
				<li>
					<label>
						<input type="checkbox" name="update_chunks[]" value="' . $k . '"> ' . $k . '
					</label>
				</li>';
            }
            $chunks .= '</ul>';
        }
        break;

    case xPDOTransport::ACTION_UNINSTALL:
        break;
}

$output = '';

if ($chunks) {
    switch ($modx->getOption('manager_language')) {
        case 'ru':
            $output .= 'Выберите чанки, которые необходимо <b>перезаписать</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">Выбрать все</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">Снять выделение</a>
				</small>
			';
            break;

        default:
            $output .= 'Select chunks, which need to <b>overwrite</b>:<br/>
				<small>
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = true;});">Select All</a> |
					<a href="#" onclick="Ext.get(\'formCheckboxes\').select(\'input\').each(function(v) {v.dom.checked = false;});">Deselect All</a>
				</small>
			';
    }

    $output .= $chunks;
}

return $output;
