<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

// Requirement:
// 1) Do not store runtime data in database
// 2) Link runtime data lifecycle to session
// Therefore no advantage to use Eloquent Models inside runtime
class SessionRuntime extends Model
{
    protected $fillable = ['shops', 'storage'];

    public function __construct(array $attributes = [])
    {
        parent::__construct(array_merge(SessionRuntime::getDefaultAttributes(),$attributes));
    }

    public function shopping($itemsBought) {
        // TODO
    }

    static private function getDefaultAttributes() {
        return [
            'shops' =>  [
                [
                    'id' => 1,
                    'productTypes' => [ProductType::PROD_TYPE_FOOD],
                    'name' => 'Продукты у дома',
                ],
                [
                    'id' => 2,
                    'productTypes' => [ProductType::PROD_TYPE_BUILD],
                    'name' => 'Строительный рынок',
                ],
                [
                    'id' => 3,
                    'productTypes' => [ProductType::PROD_TYPE_HOME],
                    'name' => 'Всё для дома',
                ],
                [
                    'id' => 4,
                    'productTypes' => [ProductType::PROD_TYPE_HOME, ProductType::PROD_TYPE_HOME],
                    'name' => 'Супермаркет',
                ],
            ],
            'storage' => Product::all()->map(function(Product $product) {
                return [
                    'id' => $product->getAttribute('id'),
                    'count' => 1000
                ];
            })
        ];
    }
}
