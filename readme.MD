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