<?php

namespace App\UseCases\Shop;

use App\Exceptions\NotEnouthMoneyException;
use App\Models\User;

class MoneyAction{
    // TODO fix-function_name
    public function execute(User $user, int $amt) : User{

        if($user->money < $amt){
            throw new NotEnouthMoneyException(['result' => '金額が足りません']);
        }
        $user->money += (-1) * $amt; // negate
        $user->save();
        return $user;
    }
}
