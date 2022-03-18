<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
/**
 * ドメインモデル
 */
class UserStock extends Authenticatable
{
    private User $user;
    private Item $item;
    private Stock $stock;
    private int $count;

    public function getUser(): User { return $this->user; }
    public function getItem(): Item { return $this->item; }
    public function getStock(): Stock { return $this->stock; }
    public function getCount(): int { return $this->count; }

    public function setStock(Stock $stock): void { $this->stock = $stock; }

    public function __construct(User $user, Item $item, Stock $stock,int $count)
    {
        $this->user = $user;
        $this->item = $item;
        $this->stock = $stock;
        $this->count = $count;
    }

    /**
     * それぞれのidを返却します
     */
    public function getIds() : array {
        return [
            'user_id' => $this->user->id,
            'item_id' => $this->item->id,
        ];
    }

    /**
     * 個数とItemの金額から合計金額を算出します
     */
    public function getTotalAmt():int {
        return $this->item->price * $this->count;
    }

    /**
     * ユーザーがアイテムごとに５件以上在庫を抱えている場合は登録できない
     */
    public function haveSpace() : bool 
    {
        $count = $this->user->stocks()->count();
        return $count < 5;
    }

    /**
     * 作成時に取得でもいい
     */
    public function getResult() : array
    {
        return [
            'balance' => $this->user->money,
            'item' => $this->item->name,
            'count' => $this->stock->count,
        ];
    }


}
