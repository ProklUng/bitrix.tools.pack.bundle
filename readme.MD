# Бандл ординарных инструментов для Bitrix+Symfony

Цель: борьба с копипастой всякой мелочи между проектами. Содержит в основном мусорный legacy код, доставшийся в наследство от
предыдущих поколений, но прижившийся.

***INTERNAL***

## Установка

composer.json:

```json
    "repositories": [
        {
            "type": "git",
            "url": "https://github.com/proklung/bitrix-tools-pack-bundle"
        }
    ]
```

```bash
composer require proklung/bitrix-tools-pack-bundle
``` 
 
## Трэйты для миграций

- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\ClearCacheTrait` - очищает все виды кэша
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\EmailEventTrait` - создает новый тип почтовых событий
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\EmailTemplateTrait` - создает новый шаблон для почтового сообщения
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\IblockPropertyTrait` - создает новое пользовательское свойство инфоблока
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\MigrationsHlBlocksHelpersTrait` - хэлперы для HighLoad инфоблоков
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\ModuleTrait` - установка-удаление модулей
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\UserFieldTrait` - создание пользовательских полей
- `Prokl\BitrixOrdinaryToolsBundle\Services\Migrations\UserGroupTrait` - создание групп пользователей 

## Фасады

- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\Container` - Экземпляр сервис-контейнера
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\Application` - $APPLICATION
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\ApplicationD7` - `Bitrix\Main\Application`
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CMain` - `CMain`
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CUser` - `CUser`
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\CUser` - `CFile`
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\EventManager` - `Bitrix\Main\EventManager`
- `Prokl\BitrixOrdinaryToolsBundle\Services\Facades\EventMail` - `Bitrix\Main\Mail\Event`

## Логгеры

- `Prokl\BitrixOrdinaryToolsBundle\Services\Logger\EventLogLogger` - monolog-логгер для записи логов 
в журнал событий /bitrix/admin/event_log.php?lang=ru.

Конфигурация Monolog Bundle:

```yaml
monolog:
  handlers:
    myHandler:
      type: service
      id:  Prokl\BitrixOrdinaryToolsBundle\Services\Logger\EventLogLogger
      level: error
```

Использование:

```php
        use Monolog\Logger;
        /** @var Logger $logger */
        $logger = container()->get('public_logger');
        $logger->error(
            'Testing',
            ['context' => 'OK', 'MODULE_ID' => 'My module', 'ITEM_ID' => get_class($this)]
        );
```

## Использование битриксовых почтовых событий и их шаблонов

```php
        use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender\BitrixMailEventSender;
    
        /** @var BitrixMailEventSender $bitrixEventHandler */ 
        $bitrixEventHandler = container()->get('notifier_bitrix_event_sender.mail');

        // Массив, идентичный с параметром fields при отправке Битриксом сообщений
        // См. https://dev.1c-bitrix.ru/api_help/main/reference/cevent/send.php 
        $arFields = ['NAME' => 'testing email', 'EMAIL' => 'recipitient@gmail.com'];

        // Будет оправлено сообщение в канал email и сделана запись в таблице b_event.
        $bitrixEventHandler->send('CODE_MAIL_EVENT', $arFields);
```

Ошибки отправки не глушатся. Если что-то пойдет не так, то выбросится исключение.

### Использование битриксовых SMS событий и их шаблонов

```php
        use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender\BitrixSmsSender;

        /** @var BitrixSmsSender $bitrixEventHandler */ 
        $bitrixEventHandler = container()->get('notifier_bitrix_event_sender.sms');
        // Массив, идентичный с параметром fields при отправке Битриксом сообщений
        $arFields = [
                'SENDER' => 'test', 'RECEIVER' => '+7926111111', 
                'USER_PHONE' => '+7926111111', 'CODE' => '123'
        ];

        // Будет оправлено SMS и сделана запись в таблице b_event.
        $bitrixEventHandler->send('SMS_USER_RESTORE_PASSWORD', $arFields);
```

Ошибки отправки глушатся. Если что-то с доставкой SMS пойдет не так, то будет тихо, но в таблице `b_event`
появится запись с признаком неудачи и текстом ошибки.

### Отправка битриксовых почтовых шаблонов в Телеграм

```php
        use Prokl\BitrixOrdinaryToolsBundle\Services\Email\EventBridge\Sender\BitrixTelegramEventSender;

        /** @var BitrixTelegramEventSender $bitrixEventHandler */ 
        $bitrixEventHandler = container()->get('notifier_bitrix_event_sender.telegram');

        $arFields = ['CODE' => '2222', 'LINK' => 'http://site.loc/'];

        $bitrixEventHandler->send('TEST_EVENT', $arFields);
```

Должен быть установлен `symfony/telegram-notifier` и зарегистрирован транспорт `telegram`.

Нюанс:

- Telegram плохо переваривает html (даже в режиме `parse_mode = html`). Посему под капотом html шаблона превращается в markdown
разметку.