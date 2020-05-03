**english** | [русский](./README.ru.md)
- - -

# MODX Fast Router

## Key featured

* Fast and flexible routing based on powerful PHP router <https://github.com/nikic/FastRoute>.
* Resource or Snippet as route handler.
* Easy route creation and configuration.

## Usage

Routes must be declared in the chunk **fastrouter** in the following format:

```json
[
    ["GET","/fastrouter/{name}/{id:[0-9]+}","1"],
    ["GET","/fastrouter/{id:[0-9]+}","1"],
    ["GET","/hello/{name}","1"],
    ["GET","/contact","1"],
    ["GET","/maybe-ajax-request","snippet_for_ajax_request"]
]
```

- First argument request method (`GET`), can be one of `GET`, `POST`, `PATCH`, `PUT`, `DELETE`.
- Second argument route path (`/fastrouter/{name}/{id:[0-9]+}`, where `{name}` named param with any character, `{id:[0-9]+}` must be a number). Allowed using regular expressions.
- The third parameter is the resource ID (`2`) where the request will be sent, or the name of the snippet (`snippet_for_ajax_request`).

If the requested URL does not match any declared routes, a 404 error will be generated.

To prevent request from a recursion when using the `sendErrorPage` method in your snippets and components, you must pass `array('stop' => true)` to the method.  
It should look like this:
```php
$modx->sendErrorPage(array('stop' => true));
```

If you use the resource ID as a handler, then all named parameters will be available in the `$_REQUEST` array associates by `fastrouter` key.    
In our case, the first route, eg `http://site.com/fastrouter/vanchelo/10` we get the following data:
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

If the handler is a snippet, all parameters will be available in `$scriptProperties`, you can get them this way:

```php
$key = $modx->getOption('fastrouter.paramsKey', null, 'fastrouter');
$params = $modx->getOption($key, $scriptProperties, array());

return '<pre>' . print_r($params, true) . '</pre>';
```

By default, the key of request params is `fastrouter`.
To define custom key, change `fastrouter.paramsKey` in system settings as you need.

Download the latest release from: <https://github.com/vanchelo/modxFastRouter/releases>.

## Got questions?

If you have questions or general suggestions, don't hesitate to submit a new [Github issue](https://github.com/vanchelo/modxFastRouter/issues/new).

## Contributing

1. Fork it (<https://github.com/vanchelo/modxFastRouter/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request

## License and Copyright

This software released under the terms of the [MIT license](./LICENSE).
