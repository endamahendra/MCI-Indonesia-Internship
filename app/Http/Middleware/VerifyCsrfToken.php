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
        '/travel-package/*',
        '/kategori-artikel',
        '/kategori-artikel/datatables',
        '/kategori-artikel/*',
        '/category',
        '/category/datatables',
        '/category/*',
        '/penggunas',
        '/penggunas/*',
        '/pengguna/datatables',
        '/penggunas/*',
        '/product/*',
        '/product',
        '/product/datatables',
        '/signup',
        '/signin',
        '/artikel',
        '/ratings',
        '/ratings/*',
    ];
}
