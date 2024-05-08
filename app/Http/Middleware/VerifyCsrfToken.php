<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/orders',
        '/admin',
        '/login',
        '/register',
        '/travel-package',
        '/travel-package/datatables',
        '/travel-package/{id}',
        '/kategori-artikel',
        '/kategori-artikel/datatables',
        '/kategori-artikel/{id}',
        '/category',
        '/category/datatables',
        '/category/{id}',
        '/penggunas',
        '/penggunas/{id}',
        '/pengguna/datatables',
        '/penggunas/{id}',
        '/product/{id}',
        '/product',
        '/product/datatables',
        '/signup',
        '/signin',
    ];
}
