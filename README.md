# Code Challenge
The challenge was all about looking for the best offer for a bunch of products between `N` suppliers.<br>
The following example illustrates how to use the produced class `Compare` which resolves this need.<br>

```
$compare = new Compare();

$bestSupplier = $compare->doCompare(
    [
        'name' => 'Ibuprofen',
        'quantity' => 12
    ],
    [
        'name' => 'Dental Floss',
        'quantity' => 5
    ]
)->toString();
```

In the class `Compare`, you're gonna be able to find the following rules and functionalities: <br>

<ol>
  <li>You can supply as many products you want, but each one is gonna need to be a different argument.</li>
     <ol>
        <li>**Example** $compare->doCompare($product1, $product2, $product3, ...)</li>
     </ol>
  <li>If any provided product does not exist in the records, its gonna be simply ignored.</li>
  <li>You can get the mounted and compared records with `totals` after calling `doCompare` method.</li>
  <li>You can have the result in two different modes:
    <ol>
        <li>Calling the `toString` method, then getting the string result.</li>
        <li>Calling the `getBestSupplier` method, then getting the array with the fully record.
    </ol>
  </li>
</ol>

> This code was produced in Vanilla PHP :)
