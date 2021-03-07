<?php

/**
 * Part of starter project.
 *
 * @copyright  Copyright (C) 2021 __ORGANIZATION__.
 * @license    __LICENSE__
 */

declare(strict_types=1);

namespace App\Front\Home;

use Windwalker\Core\Attributes\Controller;
use Windwalker\Core\Auth\AuthService;
use Windwalker\Core\Manager\DatabaseManager;
use Windwalker\Core\Manager\SessionManager;
use Windwalker\Core\Security\CsrfService;
use Windwalker\Database\DatabaseAdapter;
use Windwalker\Http\Response\Response;
use Windwalker\ORM\ORM;
use Windwalker\Session\Session;

/**
 * The HomeController class.
 */
#[Controller(

)]
class HomeController
{
    public function index(Session $session, AuthService $auth)
    {
        $r = $auth->authenticate(
            [
                'username' =>'admin',
                'password' => '1234'
            ]
        );

        $user =$r->getCredential();

        $r = $auth->authorise('can.save', $user, 'sss');

        show($r);

        return 'asd';
    }
}
