# phlib/config

[![Code Checks](https://img.shields.io/github/actions/workflow/status/phlib/config/code-checks.yml?logo=github)](https://github.com/phlib/config/actions/workflows/code-checks.yml)
[![Codecov](https://img.shields.io/codecov/c/github/phlib/config.svg?logo=codecov)](https://codecov.io/gh/phlib/config)
[![Latest Stable Version](https://img.shields.io/packagist/v/phlib/config.svg?logo=packagist)](https://packagist.org/packages/phlib/config)
[![Total Downloads](https://img.shields.io/packagist/dt/phlib/config.svg?logo=packagist)](https://packagist.org/packages/phlib/config)
![Licence](https://img.shields.io/github/license/phlib/config.svg)

```php
$config = [
    'db' => [
        'host' => '10.1.0.1',
        'username' => 'sam',
        'password' => 'SuperSafePass',
    ],
];

$host = \Phlib\Config\get($config, 'db.host');
$port = \Phlib\Config\get($config, 'db.port', 3306);
```

## License

This package is free software: you can redistribute it and/or modify
it under the terms of the GNU Lesser General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Lesser General Public License for more details.

You should have received a copy of the GNU Lesser General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
