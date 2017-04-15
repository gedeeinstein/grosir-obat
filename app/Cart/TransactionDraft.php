<?php

namespace App\Cart;

use App\Product;

/**
 * Transaction Draft Interface.
 */
abstract class TransactionDraft
{
    public $items = [];

    public function toArray()
    {
        return [
            'invoice_no' => 2,
            'date'       => 1,
            'items'      => [],
            'total'      => 0,
            'payment'    => 0,
            'customer'   => 0,
            'status_id'  => 0,
            'creator_id' => 0,
            'remark'     => '',
        ];
    }

    public function items()
    {
        return collect($this->items);
    }

    public function addItem(Item $item)
    {
        $this->items[] = $item;

        return $item->product;
    }

    public function removeItem($itemKey)
    {
        unset($this->items[$itemKey]);
    }

    public function empty()
    {
        $this->items = [];
    }

    public function getTotal()
    {
        return $this->items()->sum('subtotal');
    }

    public function getTotalQty()
    {
        return $this->items()->sum('qty');
    }

    public function getDiscountTotal()
    {
        return $this->items()->sum('item_discount_subtotal');
    }

    public function updateItem($itemKey, $newItemData)
    {
        if (!isset($this->items[$itemKey])) {
            return;
        }

        $item = $this->items[$itemKey];

        $this->items[$itemKey] = $item->updateAttribute($newItemData);

        return $item;
    }

    public function search(Product $product)
    {
        $productItem = $this->items()->where('id', $product->id)->first();
        return $productItem;
    }
}