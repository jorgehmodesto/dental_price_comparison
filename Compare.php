<?php

/**
 * Class Compare
 */
class Compare
{
    /**
     * @var array $records
     *   Records to be compared.
     */
    private $records;

    /**
     * Compare constructor.
     */
    public function __construct()
    {
        $this->setRecords([
            'sup_a' => [
                'name' => 'Supplier A',
                'products' => [
                    [
                        'name' => 'Dental Floss',
                        'unit' => 1,
                        'price' => 9,
                    ],[
                        'name' => 'Dental Floss',
                        'unit' => 20,
                        'price' => 160,
                    ],[
                        'name' => 'Ibuprofen',
                        'unit' => 1,
                        'price' => 5,
                    ],[
                        'name' => 'Ibuprofen',
                        'unit' => 10,
                        'price' => 48,
                    ],[
                        'name' => 'Dipyrone',
                        'unit' => 1,
                        'price' => 10,
                    ],
                ],
            ],
            'sup_b' => [
                'name' => 'Supplier B',
                'products' => [
                    [
                        'name' => 'Dental Floss',
                        'unit' => 1,
                        'price' => 8,
                    ],[
                        'name' => 'Dental Floss',
                        'unit' => 10,
                        'price' => 71,
                    ],[
                        'name' => 'Ibuprofen',
                        'unit' => 1,
                        'price' => 6,
                    ],[
                        'name' => 'Ibuprofen',
                        'unit' => 5,
                        'price' => 25,
                    ],[
                        'name' => 'Ibuprofen',
                        'unit' => 100,
                        'price' => 410,
                    ],
                ],
            ],
        ]);
    }

    /**
     * @param array $records
     * @return Compare
     */
    public function setRecords(array $records) : self
    {
        $this->records = $records;
        return $this;
    }

    /**
     * @return array
     */
    public function getRecords() : array
    {
        return $this->records;
    }

    /**
     * Calculates best offers and prices to decide who's gonna be the supplier.
     */
    public function doCompare() : self
    {
        $records = $this->getRecords();

        array_map(function($product = []) use (&$records) {

            foreach ($records as $supplier => $supplierData) {
                isset($records[$supplier]['totals'])
                    ?: $records[$supplier]['totals'] = 0;

                if (!$this->hasProduct($supplier, $product['name'])) {
                    continue;
                }

                $records[$supplier]['totals'] += $this->getBestPrices(
                    $records[$supplier]['products'], $product['name'], $product['quantity']
                );
            }

        }, func_get_args());

        $this->setRecords($records);
        return $this;
    }

    /**
     * Brings results to a string.
     */
    public function toString() : string {
        $bestSupplier = $this->getBestSupplier();
        return "{$bestSupplier['name']} is cheaper - {$bestSupplier['totals']} EUR";
    }

    /**
     * Gets the best supplier.
     */
    public function getBestSupplier() : array
    {
        $bestSupplier = [];

        foreach ($this->getRecords() as $supplier => $record) {
            if (empty($bestSupplier)) {
                $bestSupplier = $record;
                continue;
            }

            if ($bestSupplier['totals'] > $record['totals']) {
                $bestSupplier = $record;
            }
        }

        return $bestSupplier;
    }

    /**
     * @param string $supplier
     * @param string $productName
     * @return bool
     */
    private function hasProduct(string $supplier, string $productName) : bool
    {
        $records = $this->getRecords();

        if (empty($records[$supplier]['products'])) {
            return false;
        }

        foreach ($records[$supplier]['products'] as $product) {
            if ($product['name'] === $productName) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $products
     * @param string $productName
     * @param int $quantity
     * @param float $total
     * @return float
     */
    private function getBestPrices(array $products, string $productName, int $quantity, float &$total = 0) : float
    {
        if ($quantity > 0) {
            foreach ($products as $product) {
                if (
                    $product['name'] != $productName
                    || $quantity < $product['unit']
                ) {
                    continue;
                }

                $bestOffer = $product;
            }

            $total += $bestOffer['price'];
            $quantity -= $bestOffer['unit'];

            $this->getBestPrices($products, $productName, $quantity, $total);
        }

        return $total;
    }
}
