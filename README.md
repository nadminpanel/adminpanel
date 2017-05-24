# NAdminPanel\AdminPanel

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Total Downloads][ico-downloads]][link-downloads]

N Admin Panel's central package, which includes:
- admin login interface, using AdminLTE;
- basic menu;
- role & permission


## Install on Laravel 5.4

1) Run in your terminal:

``` bash
$ composer require nadminpanel/adminpanel
```

2) Add the service providers in config/app.php:
``` php
NAdminPanel\AdminPanel\AdminPanelServiceProvider::class,
```

3) Then run a few commands in the terminal:
``` bash
$ php artisan vendor:publish --provider="NAdminPanel\AdminPanel\AdminPanelServiceProvider"
$ php artisan migrate #generates users table (using Laravel's default migrations)
$ php artisan db:seed #seed admin account
```


## Credits

- [Cristian Tabacitu][link-author]
- [All Contributors][link-contributors]

## License

Admin Panel is free for non-commercial use.

[ico-version]: https://img.shields.io/packagist/v/backpack/base.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/backpack/base.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/backpack/base
[link-downloads]: https://packagist.org/packages/backpack/base
[link-author]: http://tabacitu.ro
[link-contributors]: ../../contributors