# Laravel Service Layer
Laravel Service Layer is a package for Laravel 5 which is used to abstract the bussiness logic in service layer. This makes applications much easier to maintain.

## Installation

Run the following command from you terminal:


 ```bash
composer require kesmenenver/servicelayer
 ```

or add this to require section in your composer.json file:

 ```
  "kesmenenver/servicelayer": "dev-master"
 ```

then run ```composer update```

## Usage

Imagine an application where users create products. You can't do this process in contoller because controllers have to be glue code for all layers so you need a service layer for that. It's easy with this package.

First 
```
php artisan make:service CreateProduct
 ```
 
 This command will created one interface and one class in app/Services folder. You have to implement make() method in ```app/services/CreateProduct```. We will use Interface for DI in controller later.
 
 ```app/services/CreateProduct``` looks like this.
 ```php
<?php

namespace App\Services;
use App\Services\Contracts\CreateProductServiceInterface;

class CreateProductService implements CreateProductServiceInterface
{
    public function make(array $request)
        {
             // TODO: Implement make() method.
            // put all the logic in this class
        }
}
```

Wee need to implement ```make()``` method. All your logic must be in your ```make()``` methods. 
By implementing ```make()``` method you telling  what does this service do.

Now, implement ```make()``` method:
 ```php
<?php

namespace App\Services;
use App\Services\Contracts\CreateProductServiceInterface;

class CreateProductService implements CreateProductServiceInterface
{
    public function make(array $request)
        {
            $product = \App\Product::create([
                'name' => $request['name'],
                'amount' => $request['amount'],
                'quantity' => $request['quantity']
            ]);
            return $product;
        }
}
```

And finally, use the service in the controller:

```php
public function store(CreateProductRequest $request, CreateProductServiceInterface $createProductService)
{
    $product = $createProductService->make($request->toArray());
    return response()->json($product);
}
```
We use Interface in controller for DI. Laravel needs to know where this interface is implemented. Just add this code snippet to the ```register()``` method in the ```/app/Providers/AppServiceProvider.php``` class.

```php
 $this->app->bind(
            'App\Services\Contracts\CreateProductServiceInterface',
            'App\Services\CreateProductService'
        );
```

Consider creating products in different locations. The only thing you need to do is maintain your services.

