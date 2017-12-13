# Trucker

Trucker is a PHP package for mapping remote API resources (usually RESTful) as models in an ActiveResource style. The benefit is easier use of remote APIs in a fast and clean programming interface.

```php
<?php

//create a class to use
class Product extends Trucker\Resource\Model {}

//create a new entity
$p = new Product(['name' => 'My Test Product']);
$success = $p->save();
echo $p->getId();

//find an existing entity
$found = Product::find(1);

//update an entity
$found->name = 'New Product Name';
$success = $found->save();

//destroy an entity
$success = $found->destroy();

//find a collection
$results = Product::all();
```


View the [README](https://github.com/Indatus/trucker/blob/master/README.md) for full usage documentation.