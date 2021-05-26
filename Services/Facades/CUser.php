<?php

namespace Prokl\BitrixOrdinaryToolsBundle\Services\Facades;

use Prokl\FacadeBundle\Services\AbstractFacade;

/**
 * Class CUser
 * @package Local\Guta\Facades
 *
 * @method static int|false Add(array $fields)
 * @method static bool Authorize(int $userId, bool $save = false, bool $update = true)
 * @method static Delete(int $id)
 * @method static GetByID(int $id)
 * @method static GetByLogin(string $login)
 * @method static string GetEmail()
 * @method static string GetFirstName()
 * @method static string GetFullName()
 * @method static int GetID()
 * @method static string GetLastName()
 * @method static array GetUserGroup(int $id)
 * @method static array GetUserGroupArray()
 * @method static bool IsAdmin()
 * @method static bool IsAuthorized()
 * @method static void Logout()
 * @method static mixed Login(string $login, string $password, string $remember = "N", string $password_original = "Y")
 * @method static bool Update(int $id, array $fields, string $authActions = true)
 * @method static void SetUserGroup(int $userId, array $groups)
 * @method static void SetUserGroupArray(array $groups)
 * @method static void SavePasswordHash()
 */
class CUser extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getFacadeAccessor() : string
    {
        return 'bitrix_ordinary_tools.cuser';
    }
}
