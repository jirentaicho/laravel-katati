<?php

namespace App\UseCases\Shop;

use App\Exceptions\NotEnouthSpaceException;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserStock;

class StockAction{
    // TODO fix-function_name
    public function execute(UserStock $userStock) : Stock{

        // 在庫の確認をして在庫更新する
        $stock = Stock::where($userStock->getIds())->first();
        // 既に在庫を持っている場合はカウントを増やすだけ
        if(!is_null($stock)){
            $stock->count += $userStock->getCount();
            $stock->save();
            $userStock->setStock($stock);
            return $stock;
        }

        // 新規の場合は格納可能チェックを行う
        if(!$userStock->haveSpace()){
            throw new NotEnouthSpaceException("登録できるアイテムが既にいっぱいです");
        }
        $stock = new Stock($userStock->getIds());
        $stock->count = $userStock->getCount();
        $stock->save();
        $userStock->setStock($stock);
        return $stock;

    }
}
