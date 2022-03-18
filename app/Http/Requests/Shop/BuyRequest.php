<?php

namespace App\Http\Requests\Shop;

use App\Models\Item;
use App\Models\Stock;
use App\Models\User;
use App\Models\UserStock;
use Illuminate\Foundation\Http\FormRequest;

class BuyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // 今回は認証処理を行っていないのでtrueに設定しています。
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'count' => 'required',
        ];
    }

    // InputBoundary
    // UseCaseが必要とするデータの生成に注力する
    // Userは認証にて取得します
    public function makeUserStock(User $user,Item $item): UserStock
    {
        $userStock = new UserStock(
            $user, 
            $item,
            new Stock(['user_id' => $user->id , 'item_id' => $item->id]),
            $this->count
        );
        return $userStock;      
    }

    

}
