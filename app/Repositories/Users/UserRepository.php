<?php
namespace App\Repositories\Users;

use App\Models\User;
use App\Repositories\Interfaces\Users\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{

    /**
     * stor user details.
     *  @param array of $userData
     *  @return object of created $user
     */
    public function storeUser($userData)
    {
        $user = User::create($userData);
        return $user;
    }
}
