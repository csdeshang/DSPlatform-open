# Pimple Container

[![PHPUnit](https://github.com/hyperf-cloud/pimple-integration/actions/workflows/test.yml/badge.svg)](https://github.com/hyperf-cloud/pimple-integration/actions/workflows/test.yml)

`hyperf/pimple` 是基于 `pimple/pimple` 实现的轻量级符合 `PSR11 规范` 的容器组件。可以减少其他框架使用 Hyperf 组件时的成本。

## 安装

```
composer require "hyperf/pimple:1.1.*"
```

## 使用

```php
<?php

use Hyperf\Pimple\ContainerFactory;

$container = (new ContainerFactory())();

```

### `EasySwoole` 接入 `hyperf/translation`

因为 `EasySwoole` 的容器组件暂时并没有实现 `PSR11` 规范，所以无法直接使用。

1. 首先引入相关组件

```
composer require "hyperf/translation:1.1.*"
composer require "hyperf/config:1.1.*"
```

2. 添加 国际化相关的 Provider

```php
<?php

declare(strict_types=1);

namespace App\Provider;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\TranslatorLoaderInterface;
use Hyperf\Pimple\ProviderInterface;
use Hyperf\Translation\FileLoader;
use Hyperf\Utils\Filesystem\Filesystem;

class TranslatorLoaderProvider implements ProviderInterface
{
    public function register(ContainerInterface $container)
    {
        $container->set(TranslatorLoaderInterface::class, function () use ($container) {
            $config = $container->get(ConfigInterface::class);
            $files = $container->get(Filesystem::class);
            $path = $config->get('translation.path');

            return make(FileLoader::class, compact('files', 'path'));
        });
    }
}
```

```php
<?php

declare(strict_types=1);

namespace App\Provider;

use Hyperf\Contract\ConfigInterface;
use Hyperf\Contract\ContainerInterface;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Contract\TranslatorLoaderInterface;
use Hyperf\Pimple\ProviderInterface;
use Hyperf\Translation\Translator;

class TranslatorProvider implements ProviderInterface
{
    public function register(ContainerInterface $container)
    {
        $container->set(TranslatorInterface::class, function () use ($container) {
            $config = $container->get(ConfigInterface::class);
            $locale = $config->get('translation.locale');
            $fallbackLocale = $config->get('translation.fallback_locale');

            $loader = $container->get(TranslatorLoaderInterface::class);

            $translator = make(Translator::class, compact('loader', 'locale'));
            $translator->setFallback((string) $fallbackLocale);

            return $translator;
        });
    }
}

```

3. `EasySwoole` 事件注册器在 `EasySwooleEvent.php` 中，所以我们需要在 `initialize()` 中初始化我们的容器和国际化组件。

> 以下 Config 组件，可以自行封装，这里方便起见直接配置。

```php
<?php

declare(strict_types=1);

namespace EasySwoole\EasySwoole;

use EasySwoole\EasySwoole\AbstractInterface\Event;
use EasySwoole\EasySwoole\Swoole\EventRegister;
use EasySwoole\Http\Request;
use EasySwoole\Http\Response;
use Hyperf\Config\Config;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Pimple\ContainerFactory;
use App\Provider\TranslatorProvider;
use App\Provider\TranslatorLoaderProvider;

class EasySwooleEvent implements Event
{
    public static function initialize()
    {
        date_default_timezone_set('Asia/Shanghai');
        $container = (new ContainerFactory([
            TranslatorProvider::class,
            TranslatorLoaderProvider::class,
        ]))();
        $container->set(ConfigInterface::class, new Config([
            'translation' => [
                'locale' => 'zh_CN',
                'fallback_locale' => 'en',
                'path' => EASYSWOOLE_ROOT . '/storage/languages',
            ],
        ]));
    }
}
```

4. 修改控制器，使用国际化组件

```php
<?php

declare(strict_types=1);

namespace App\HttpController;

use EasySwoole\Http\AbstractInterface\Controller;
use Hyperf\Contract\TranslatorInterface;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Codec\Json;

class Index extends Controller
{
    public function index()
    {
        $container = ApplicationContext::getContainer();
        $translator = $container->get(TranslatorInterface::class);

        $data = [
            'message' => $translator->trans('message.hello', ['name' => 'Hyperf']),
        ];

        $this->response()->write(Json::encode($data));
    }
}

```

5. 添加国际化配置

```php
// storage/languages/en/message.php
return [
    'hello' => 'Hello :name',
];

// storage/languages/zh_CN/message.php
return [
    'hello' => '你好 :name',
];
```

6. 测试

```
$ curl http://127.0.0.1:9501/
{"message":"你好 Hyperf"}
```



