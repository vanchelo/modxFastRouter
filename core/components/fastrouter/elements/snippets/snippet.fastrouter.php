<?php
return '<pre>' . print_r($_REQUEST[$modx->getOption('fastrouter.paramsKey', null, 'fastrouter')], true) . '</pre>';
