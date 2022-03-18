<?php

namespace App\Http\Controllers;

use App\Http\Requests\Shop\BuyRequest;
use App\Http\Resources\BuyResource;
use App\Models\Item;
use App\Models\User;
use App\UseCases\Shop\BuyAction;
use App\UseCases\Shop\MoneyAction;
use App\UseCases\Shop\StockAction;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    // Itemはモデル結合ルートにて注入される
    // 引数にusecaseを入れると勝手にインジェクションされる。
    public function buy(BuyRequest $request, Item $item, BuyAction $action, MoneyAction $moneyAction, StockAction $stockAction)
    {
        // 今回は認証を使っていないので、やっているていで書いてます。
        $user = User::find($request->user_id)->first();

        // ポリシーの実行(今回はやってない)
        // $this->

        // 認証使ってないので$userを渡してます
        $userStock = $request->makeUserStock($user,$item);

        // エラーは都度返してます
        $result = new BuyResource($action->execute($userStock,$moneyAction,$stockAction)); 
        
        return $result;
    }

}
