##MODX Fast Router

Используется вот эта замечательная библиотека https://github.com/nikic/FastRoute

Объявлять маршруты нужно в чанке **fastrouter** в таком формате:

```json
[
    ["GET","/fastrouter/{name}/{id:[0-9]+}","1"],
    ["GET","/fastrouter/{id:[0-9]+}","1"],
    ["GET","/hello/{name}","1"],
    ["GET","/contact","1"],
    ["GET","/maybe-ajax-request","snippet_for_ajax_request"],
]
```

- Первый параметр `GET` метод запроса, может быть `GET`, `POST`.
- Второй параметр `/fastrouter/{name}/{id:[0-9]+}` наш маршрут, где `{name}` именованный параметр принимающий любые символы, `{id:[0-9]+}` именованный параметр принимающий только цифры. Можно использовать любые валидные регулярные выражения.
- Третий параметр `2` ID ресурса куда будет направлен запрос или имя сниппета.

Если запрошенному URL не соответствует ни один объявленный маршрут, будет сгенерированна 404 ошибка.

Чтобы запрос не вошел в рекурсию при использовании в своих сниппетах и компонентах метода `sendErrorPage`, необходимо передать в метод `array('stop' => true)`. Должно получится вот так:

```php
$modx->sendErrorPage(array('stop' => true));
```

Если использовать в качестве обработчика ID ресурса, то все именованные параметры попадут в массив `fastrouter` в глобальном массиве `$_REQUEST`. В нашем случае по первому маршруту, например `http://site.com/fastrouter/vanchelo/10` получим вот такие данные:
```php
var_dump($_REQUEST);

Array
(
    [q] => user/vanchelo/10
    [fastrouter] => Array
        (
            [name] => vanchelo
            [id] => 10
        )
)
```

В случае, если обработчиком является сниппет, все параметры будут доступны в `$scriptProperties`, получить их можно будет таким образом:

```php
$key = $modx->getOption('fastrouter.paramsKey', null, 'fastrouter');
$params = $modx->getOption($key, $scriptProperties, array());

return '<pre>' . print_r($params, true) . '</pre>';
```

По умолчанию имя ключа со всеми параметрами маршрута - `fastrouter`.
Для задания своего ключа измените в настройках системы параметр `fastrouter.paramsKey`.

Скачать готовый пакет здесь https://www.dropbox.com/s/n363q1cuf5dwx37/fastrouter-1.0.2-pl.transport.zip?dl=1
Или последний релиз с GitHub https://github.com/vanchelo/modxFastRouter/releases 
