<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {

    $routes->setRouteClass(DashedRoute::class);

    $routes->scope('/', function (RouteBuilder $builder): void {

        // Định tuyến trang chủ và trang tĩnh
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        $builder->connect('/pages/*', 'Pages::display');

        // Định tuyến cho trang Admin (để nó không bị ghi đè bởi route users)
        // Đặt các route cụ thể hơn lên trên
        $builder->connect('/admin', ['controller' => 'Admin', 'action' => 'index']);

        // Định tuyến cụ thể cho UsersController
        // Users add
        $builder->connect('/users/add', ['controller' => 'Users', 'action' => 'add']); // Định nghĩa rõ ràng cho 'add'

        // Users edit (nếu bạn có)
        $builder->connect('/users/edit/{id}', ['controller' => 'Users', 'action' => 'edit'])
                ->setPatterns(['id' => '\d+'])
                ->setPass(['id']);

        $builder->connect('/users/delete/{id}', ['controller' => 'Users', 'action' => 'delete'])
                ->setPatterns(['id' => '\d+'])
                ->setPass(['id']);

        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index']);

        $builder->connect('/users/upload-avatar/{id}', ['controller' => 'Users', 'action' => 'uploadAvatar'])
                ->setPatterns(['id' => '\d+'])
                ->setPass(['id']);

        $builder->fallbacks();
    });
};