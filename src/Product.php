<?php

class Product {
    public string $sku;
    public string $name;
    public int $categoryId;
    public int $price;
    public int $qty;

    public function __construct(string $sku, string $name, int $categoryId, int $price, int $qty) {
        $this->sku = $sku;
        $this->name = $name;
        $this->categoryId = $categoryId;
        $this->price = $price;
        $this->qty = $qty;
    }

    public function lineTotal(): int {
        return $this->price * $this->qty;
    }

    public function stockLevel(): string {
        if ($this->qty >= 5) {
            return 'Du';
        } elseif ($this->qty >= 2) {
            return 'Sap het';
        }
        return 'Can nhap';
    }

    public function toArray(): array {
        return [
            'sku' => $this->sku,
            'name' => $this->name,
            'category_id' => $this->categoryId,
            'price' => $this->price,
            'qty' => $this->qty
        ];
    }
}
?>