[english](README.md) | **русский**
- - -

# MODX Fast Router

## Основные особенности

* Гибкая маршрутизация благодаря библиотеке <https://github.com/nikic/FastRoute>.
* Ресурс или сниппет в качестве обработчика маршрута.
* Простое создание и конфигурирование маршрутов.

## Использование

Объявление маршрутов в чанке **fastrouter** в следующем формате:
```json
[
    ["GET","/fastrouter/{name}/{id:[0-9]+}","1"],
    ["GET","/fastrouter/{id:[0-9]+}","1"],
    ["GET","/hello/{name}","1"],
    ["GET","/contact","1"],
    ["GET","/maybe-ajax-request","snippet_for_ajax_request"]
]
```

- Первый параметр `GET` метод запроса, может быть `GET`, `POST`, `PATCH`, `PUT`, `DELETE` или любой другой HTTP метод.
- Второй параметр `/fastrouter/{name}/{id:[0-9]+}` маршрут, где `{name}` именованный параметр принимающий любые символы, `{id:[0-9]+}` именованный параметр принимающий только цифры. Разрешено использовать регулярные выражения.
- Третий параметр ID ресурса (`2`) куда будет направлен запрос или имя сниппета (`snippet_for_ajax_request`).

Если запрошенному URL не соответствует ни один объявленный маршрут, будет сгенерирована 404 ошибка.

Чтобы запрос не вошел в рекурсию при использовании в своих сниппетах и компонентах метода `sendErrorPage`, необходимо передать в метод `array('stop' => true)`.  
Должно получится вот так:
```php
$modx->sendErrorPage(array('stop' => true));
```

Если использовать в качестве обработчика ID ресурса, то все именованные параметры попадут в массив `fastrouter` в глобальном массиве `$_REQUEST`.  
В нашем случае по первому маршруту, например `http://site.com/fastrouter/vanchelo/10` получим следующие данные:
```php
var_dump($_REQUEST);

// Array
// (
//     [q] => user/vanchelo/10
//     [fastrouter] => Array
//         (
//             [name] => vanchelo
//             [id] => 10
//         )
// )
```

В случае, если обработчиком является сниппет, все параметры будут доступны в `$scriptProperties`, получить их можно следующим образом:
```php
$key = $modx->getOption('fastrouter.paramsKey', null, 'fastrouter');
$params = $modx->getOption($key, $scriptProperties, array());

return '<pre>' . print_r($params, true) . '</pre>';
```

По умолчанию имя ключа со всеми параметрами маршрута - `fastrouter`.  
Для задания своего ключа измените в настройках системы параметр `fastrouter.paramsKey`.

Скачать свежий релиз с GitHub <https://github.com/vanchelo/modxFastRouter/releases>. 

## Есть вопросы?

Если у вас есть вопросы или предложения, не стесняйтесь и создайте [Github issue](https://github.com/vanchelo/modxFastRouter/issues/new).

## Участие

1. Сделайте форк (<https://github.com/vanchelo/modxFastRouter/fork>)
2. Создайте ветку (`git checkout -b feature/fooBar`)
3. Зафиксируйте изменения (`git commit -am 'Add some fooBar'`)
4. Отправьте изменения в свой репозиторий (`git push origin feature/fooBar`)
5. Создайте Pull Request

## Лицензия и авторское право

Данное программное обеспечение выпускается под [лицензией MIT](https://github.com/vanchelo/modxFastRouter/LICENSE).
