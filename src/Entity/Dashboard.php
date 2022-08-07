<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Controller\Dashboard\GetAverageCartPriceAction;
use App\Controller\Dashboard\GetCartWaitingAction;
use App\Controller\Dashboard\GetNewUserAction;
use App\Controller\Dashboard\GetTotalOrderAction;
use App\Controller\Dashboard\GetTotalSalesAction;



#[ApiResource(
    collectionOperations: [
        'get_average_cart_price' => [
            'access_control' => 'is_granted("ROLE_STATS") or is_granted("ROLE_ADMIN")',
            'method' => 'GET',
            'path' => '/stats/average_cart_price',
            'controller' => GetAverageCartPriceAction::class,
        ],
        'get_cart_waiting' => [
            'access_control' => 'is_granted("ROLE_STATS") or is_granted("ROLE_ADMIN")',
            'method' => 'GET',
            'path' => '/stats/cart_waiting',
            'controller' => GetCartWaitingAction::class,
        ],
        'get_new_user' => [
            'access_control' => 'is_granted("ROLE_STATS") or is_granted("ROLE_ADMIN")',
            'method' => 'GET',
            'path' => '/stats/new_user',
            'controller' => GetNewUserAction::class,
        ],
        'get_total_orders' => [
            'access_control' => 'is_granted("ROLE_STATS") or is_granted("ROLE_ADMIN")',
            'method' => 'GET',
            'path' => '/stats/total_orders',
            'controller' => GetTotalOrderAction::class,
        ],
        'get_total_sales' => [
            'access_control' => 'is_granted("ROLE_STATS") or is_granted("ROLE_ADMIN")',
            'method' => 'GET',
            'path' => '/stats/total_sales',
            'controller' => GetTotalSalesAction::class,
        ],
    ],
    itemOperations: [],
)]
class Dashboard
{}