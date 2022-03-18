## ItemShop


## モデル結合ルート

* ルートに紐づくeloquentモデルを自動注入する

[公式](https://readouble.com/laravel/master/ja/routing.html#route-model-binding)


## ポリシー

* ユーザーがItemを購入する金額を持っているかどうか？
  * ユーザーのステータスに関するチェック

ポリシーの作成

> php artisan make:policy BuyPolicy
※今回は使っていません。


## フォームリクエストの作成

> php artisan make:request BuyRequest

* app\http\requestsに作成されるので、フォルダ切ってリファクタしておく
* ここでEloquentモデルの作成を行うメソッドを用意する

## リソースの作成

* eloquentモデルをそのままAPIレスポンスの形式に変換する
* JsonResourceを継承したクラスのtoArrayメソッドにeloquentモデルを渡す

コマンド

> php artisan make:resource BuyResource

## リレーション


[今回のテーブル](https://volkruss.com/?p=1586)

[Laravel 8.x Eloquent:リレーション](https://readouble.com/laravel/8.x/ja/eloquent-relationships.html)




## UseCase


例：登録する

* ドメインバリデーションを行う
* Eloquentモデルを介してデータベースを更新する

## 複数のUseCaseが実行される

* 買う(buy) 
  * パラメータ構築とコントロールフロー
  * トランザクション
    * ユーザーの金額を更新する(useMoney)
      * User Item
    * ユーザーの在庫を更新する(stockItem)
      * User Item Stock

※ドメインサービスは単一責務のユースケース

[参考](https://qiita.com/nunulk/items/bc7c93a3dfb43b01dfab#usecase-%E3%82%92%E3%81%A4%E3%81%8F%E3%82%8B%E9%9A%9B%E3%81%AE%E3%82%AC%E3%82%A4%E3%83%89%E3%83%A9%E3%82%A4%E3%83%B3)

> 大きな処理の場合、他の UseCase を組み合わせてひとつの UseCase となるパターンがありえます。その場合、パラメータの構築とコントロールフローのみに注力します。

usecaseがeloquentモデルに依存する
→ インフラストラクチャ層については思考更新する

buyというユースケースには複数のモデル(User,Item,Stock)が必要になる。



## フロー

APIリクエスト→リクエスト→コントローラー→ドメインモデル作成(リクエスト内)→ユースケース実行→ドメインバリデーション→永続化→リソース→レスポンス

## ドメインモデル

* リクエストで作成される。
* 複数のユースケースに対応できる

以下の$parametersに相当する？

```php
  public function invoke($parameters)
  {
    [$params1, $params2, $params3] = $this->divideParameters($parameters);
    $this->useCase1->invoke($params1);
    $this->useCase2->invoke($params2);
    $this->useCase3->invoke($params3);
  }
```

ドメインモデルの作成について

> Eloquent Model を内包する Domain Model を作成しても構いません。最初から UseCase 内でデータベースアクセスの副作用を抽象化することは諦めているためです。この際， Domain Model のコンストラクタが複数の Eloquent Model や，それに付随する何らかの付加情報を一緒に受け取っても良いため，応用範囲は非常に広いと考えられます。

#### ドメインモデルとした場合

UserStockModel？？

* ~金額の妥当性判定~
* 在庫の妥当性判定

他のユースケースでの利用について

* 売る時
  * 金額更新
  * 在庫更新

→ 妥当性検証の必要はない

一旦、包括するだけのエンティティとして作成してみる。

## サービス

DDDの場合

* アプリケーションサービス
  * ドメインオブジェクトを利用してアプリケーションの要求を満たす
* ドメインサービス
  * エンティティ・バリューオブジェクト以外のビジネスロジック
  * エンティティやバリューオブジェクトを利用した計算処理など

今回

* ユースケースの中でドメインサービスの実行を行う

## test

ユニットテストの作成

> php artisan make:test BuyRequest --unit

パスまで指定する場合

>php artisan make:test App/Http/Requests/EventRequestTest

テストの実行

> php artisan test