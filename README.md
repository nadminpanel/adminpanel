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

3) Change auth user's model in config/auth.php:
```
'model' => NAdminPanel\AdminPanel\Models\User::class,
```

4) Then run a few commands in the terminal:
``` bash
$ php artisan vendor:publish
$ php artisan migrate
```

5) Add developer seed for admin account in database/seeds/DatabaseSeeder.php: (Optional)
```
$this->call(DeveloperSeeder::class);
```
Run seed command
```
$ php artisan db:seed
```


## Credits

- [Pyae Hein][link-author]
- [All Contributors][link-contributors]

## License

Admin Panel is free for non-commercial use.

[ico-version]: https://img.shields.io/packagist/v/nadminpanel/adminpanel.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/nadminpanel/adminpanel.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/nadminpanel/adminpanel
[link-downloads]: https://packagist.org/packages/nadminpanel/adminpanel
[link-author]: https://github.com/pyaehein
[link-contributors]: ../../contributors