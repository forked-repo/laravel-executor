<p align="center">
<img src="https://ashallendesign.co.uk/images/custom/laravel-executor-logo.png" width="500">
</p> 

<p align="center">
<a href="https://packagist.org/packages/ashallendesign/laravel-executor"><img src="https://img.shields.io/packagist/v/ashallendesign/laravel-executor.svg?style=flat-square" alt="Latest Version on Packagist"></a>
<a href="https://travis-ci.com/ash-jc-allen/laravel-executor"><img src="https://img.shields.io/travis/ash-jc-allen/laravel-executor/master.svg?style=flat-square" alt="Build Status"></a>
<a href="https://packagist.org/packages/ashallendesign/laravel-executor"><img src="https://img.shields.io/packagist/dt/ashallendesign/laravel-executor.svg?style=flat-square" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/ashallendesign/laravel-executor"><img src="https://img.shields.io/packagist/php-v/ashallendesign/laravel-executor?style=flat-square" alt="PHP from Packagist"></a>
<a href="https://github.com/ash-jc-allen/laravel-executor/blob/master/LICENSE"><img src="https://img.shields.io/github/license/ash-jc-allen/laravel-executor?style=flat-square" alt="GitHub license"></a>
</p>

## Table of Contents

- [Overview](#overview)
- [Installation](#installation)
    - [Requirements](#requirements)
    - [Install the Package](#install-the-package)
- [Usage](#usage)
    - [Creating an Executor](#creating-an-executor)
        - [Creating a New Executor](#creating-a-new-executor)
        - [Creating an Executor with a Command](#creating-an-executor-with-a-command)
    - [Updating an Executor](#updating-an-executor)
        - [Adding an Artisan Command](#adding-an-artisan-command)
        - [Adding a Command](#adding-a-command)
        - [Adding a Closure](#adding-a-closure)
        - [Adding Desktop Notifications](#adding-desktop-notifications)
        - [Pinging a URL](#pinging-a-url)
    - [Running the Executors](#running-the-executors)
        - [Running via the Console](#running-via-the-console)
        - [Running Manually](#running-manually)
- [Examples](#examples)
- [Security](#security)
- [Contribution](#contribution)
- [Credits](#credits)
- [Changelog](#changelog)
- [License](#license)

## Overview

A Laravel package that simplifies running code and commands when installing or updating your web app.

## Installation

### Requirements
The package has been developed and tested to work with the following minimum requirements:

- PHP 7.2
- Laravel 6

### Install the Package
You can install the package via Composer:

```bash
composer require ashallendesign/laravel-executor
```

## Usage
### Creating an Executor
#### Creating a New Executor
To create a new Executor, you can use the following command:

```bash
php artisan make:executor YourExecutorNameHere
```

The above command would create an Executor named ``` YourExecutorNameHere ``` that can be found in the ``` app/Executor ```
folder.

#### Creating an Executor with a Command
Generally, Executors are expected to be run within a console. So, when creating a new Executor, if you intend for it to
be run in the console, you can use the following command:

```bash
php artisan make:executor YourExecutorNameHere -c
```

The command above will create the exact same boilerplate for your new Executor as the command in [Creating a New Executor](#creating-a-new-executor).
However, it will create a new command in your ``` app/Commands ``` folder named ``` RunYourExecutorNameHereExecutor ```.
This means that you won't need a new command manually to run your executor.

Learn more in [Running via the Console](#running-via-the-console) to find out how to run the Executor inside the commands.

### Updating an Executor
#### Adding an Artisan Command
To run an Artisan command via your Executor class, you can add the ``` runArtisan() ``` method to your Executor's ``` run() ```
method. For example, the code below shows how you could set the Executor to run the built-in Laravel ``` php artisan cache:clear ```
command:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->runArtisan('cache:clear');
    }
}
```

#### Adding a Command
To run a command (that can't be run with Artisan) via your Executor class, you can add the ``` runExternal() ``` method to your Executor's ``` run() ```
method. For example, the code below shows how you could set the Executor to run the built-in Composer ``` composer install ```
command:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->runExternal('composer install');
    }
}
```

#### Adding a Closure
Sometimes you might want to run some code that doesn't necessarily fit into an existing command. In this case, you can add a closure
to your Executor instead. The example below shows how to pass a simple closure to your Executor class:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->runClosure(function () {
            return 'I am running inside a closure.';
        });
    }
}
```

#### Adding Desktop Notifications

If you are running your Executor via the console, you may want to display desktop notifications between some steps. To display
a desktop notification you can use either ``` ->simpleDesktopNotification() ``` or ``` ->desktopNotification() ```.

By using ``` ->simpleDesktopNotification() ``` you can pass just a title and body that should be displayed. The example below
shows how to create a simple desktop notification:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->simpleDesktopNotification('Notification title', 'Notification body');
    }
}
```

If you want to customise your notification, you can use ``` ->desktopNotification() ``` and pass a ``` Joli\JoliNotif\Notification ```
object as the parameter. For more information on building these types of notifications, check out the [``` Joli\JoliNotif ```
documentation here](https://github.com/jolicode/JoliNotif).

You can also add the ``` ->completeNotification() ``` to your Executor so that a desktop notification can be displayed
once all the code inside the class has been run.

#### Pinging a URL

If you're using your Executor for updating your application on a live server, you might want to ping a URL when it's finished.
This could be useful for sending a webhook to alert you that the scripts have run successfully. To ping a URL, you can simply
use the ``` ->ping() ``` method.

The example below shows how to ping a website:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->ping('https://ashallendesign.co.uk/executor-webhook-route');
    }
}
```

If you want to send headers in your ``` ping() ```, you can pass them as a second parameter. This can be useful for if you
want to add a signature to your webhook requests to assert that they've been sent from an authorised sender.

The example below shows how to ping a website with headers:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->ping('https://ashallendesign.co.uk/executor-webhook-route', [
            'X-Webhook-Signature' => 'secret-signature-to-go-here'
        ]);
    }
}
```

### Running the Executors
#### Running via the Console
As mentioned above, Executors are mainly intended for being run from within the console. This makes them ideal for adding
to deploy scripts; such as the ones that can be found one Laravel Forge and Runcloud.

If you created a command at the same time as the Executor class by using the command above found in [Creating an Executor with a Command](#creating-an-executor-with-a-command),
your command will already have been given a signature. The signature is created by converting the Executor's classname into kebab case.
For example, an Executor with the name ``` AppInstall ``` will be given the command signature of ``` executor:app-install ```.

The example below shows how a command (that has been unaltered) can be run the ``` AppInstall ``` Executor:

```bash
php artisan executor:app-install
```

Note: To register the command with your Laravel application, you will want to add the command class name to the ``` $commands``` array in your
``` app/Console/Kernel.php ``` file.

#### Running manually
There may be times when you want to run an Executor class outside of the command line. To do this, you simply need to call
the ``` ->run() ``` method on your class. The example below shows how to manually run an Executor named ``` AppInstall ```:

```php
<?php

namespace App\Http\Controllers;

use App\Executor\AppInstall;

class Controller
{
    public function index()
    {
        (new AppInstall())->run();
    }
}
```

## Examples
The example below shows how to create an Executor class that can be run after pulling a new branch of project down from
a remote repository:

```php
<?php

namespace App\Executor;

use AshAllenDesign\LaravelExecutor\Classes\Executor;

class AppUpdate extends Executor
{
    public function run(): Executor
    {
        return $this->simpleDesktopNotification('Starting Executor', 'Starting the AppUpdate Executor.')
                    ->runExternal('composer install')
                    ->runArtisan('migrate')
                    ->runArtisan('cache:clear')
                    ->completeNotification();
    }
}
```

Assuming that the above Executor class is still using the default command signature, each time the branch is pulled down,
the following command could be run: ``` php artisan executor:app-update ```.

The image below shows how a simple Executor command could be run. It's only executing ``` composer du -o ``` but demonstrates
how Laravel Executor can provide feedback with real-time output and desktop notifications.

<p align="center">
<img src="https://ashallendesign.co.uk/images/custom/executor-desktop-notification.gif">
</p>

## Security

If you find any security related issues, please contact me directly at [mail@ashallendesign.co.uk](mailto:mail@ashallendesign.co.uk) to report it.

## Contribution

If you wish to make any changes or improvements to the package, feel free to make a pull request.

Note: A contribution guide will be added soon.

## Credits

- [Ash Allen](https://ashallendesign.co.uk)
- [Jess Pickup](https://jesspickup.co.uk) (Logo)
- [All Contributors](https://github.com/ash-jc-allen/laravel-executor/graphs/contributors)

## Changelog

Check the [CHANGELOG](CHANGELOG.md) to get more information about the latest changes.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
