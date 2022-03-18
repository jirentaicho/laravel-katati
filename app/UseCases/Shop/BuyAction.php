<?php

namespace App\UseCases\Shop;

use App\Models\UserStock;
use Exception;
use Illuminate\Support\Facades\DB;

// オーケストレーションするだけのユースケース
class BuyAction{
    // TODO fix-function_name
    public function execute(UserStock $userStock,MoneyAction $moneyAction,StockAction $stockAction){
        
        assert($userStock->getUser()->exists);
        assert($userStock->getStock()->exists);

        try{
            DB::beginTransaction();
            // 金額更新
            $moneyAction->execute($userStock->getUser(),$userStock->getTotalAmt());
            // 在庫更新
            $stockAction->execute($userStock);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
        return $userStock;
    }
}
