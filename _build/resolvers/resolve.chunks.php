<?php
/**
 * Resolves chunks
 *
 * @var xPDOObject $object
 * @var array $options
 */

if ($object->xpdo) {
    $modx = $object->xpdo;

    switch ($options[xPDOTransport::PACKAGE_ACTION]) {
        case xPDOTransport::ACTION_INSTALL:
            break;

        case xPDOTransport::ACTION_UPGRADE:
            if (!empty($options['chunks']) && !empty($options['update_chunks'])) {
                foreach ($options['update_chunks'] as $v) {
                    if (!empty($options['chunks'][$v]) && $chunk = $modx->getObject('modChunk', ['name' => $v])) {
                        $chunk->set('snippet', $options['chunks'][$v]);
                        $chunk->save();

                        $modx->log(modX::LOG_LEVEL_INFO, "Updated chunk \"{$v}\"");
                    }
                }
            }

            break;

        case xPDOTransport::ACTION_UNINSTALL:
            break;
    }
}

return true;
