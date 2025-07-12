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

        // Users delete (route của bạn đã đúng)
        $builder->connect('/users/delete/{id}', ['controller' => 'Users', 'action' => 'delete'])
                ->setPatterns(['id' => '\d+'])
                ->setPass(['id']);

        // Users index (định nghĩa rõ ràng)
        $builder->connect('/users', ['controller' => 'Users', 'action' => 'index']);

        // Nếu bạn muốn có action view, định nghĩa nó cụ thể (ví dụ: users/view/1)
        // $builder->connect('/users/view/{id}', ['controller' => 'Users', 'action' => 'view'])
        //         ->setPatterns(['id' => '\d+'])
        //         ->setPass(['id']);


        // CUỐI CÙNG: fallbacks() sẽ xử lý tất cả các route còn lại theo quy tắc mặc định của CakePHP.
        // Bao gồm cả các route dạng /controller/action hoặc /controller/action/id
        // Ví dụ: /users/index, /users/add, /users/edit/1, users/view/1
        // Nếu bạn đã định nghĩa rõ các route trên, fallbacks sẽ hoạt động như một "mạng lưới an toàn".
        $builder->fallbacks();
    });
};