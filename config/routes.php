<?php
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);

    // Route cho API nếu cần
    $routes->prefix('Api', function (RouteBuilder $routes) {
        $routes->setExtensions(['json']);
        $routes->resources('DynamicTables');
        $routes->fallbacks();
    });

    // Route chính
    $routes->scope('/', function (RouteBuilder $builder): void {
        // Static pages
        $builder->connect('/', ['controller' => 'Pages', 'display', 'home']);
        $builder->connect('/pages/*', ['controller' => 'Pages', 'action' => 'display']);

        // Authentication routes
        $builder->connect('/login', ['controller' => 'Users', 'action' => 'login']);
        $builder->connect('/logout', ['controller' => 'Users', 'action' => 'logout']);

        // User management
        $builder->scope('/users', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Users', 'action' => 'index']);
            $builder->connect('/profile', ['controller' => 'Users', 'action' => 'profile']);
            $builder->connect('/add', ['controller' => 'Users', 'action' => 'add']);
            $builder->connect('/edit/{id}', ['controller' => 'Users', 'action' => 'edit'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['id']);
            $builder->connect('/delete/{id}', ['controller' => 'Users', 'action' => 'delete'])
                   ->setMethods(['POST'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['id']);
            $builder->connect('/upload-avatar/{id}', ['controller' => 'Users', 'action' => 'uploadAvatar'])
                   ->setMethods(['POST'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['id']);
        });

        // Admin area
        $builder->prefix('Admin', function (RouteBuilder $builder) {
            $builder->connect('/', ['controller' => 'Dashboard', 'action' => 'index']);
            $builder->connect('/cat-tables', ['controller' => 'CatTables', 'action' => 'index']);
            $builder->fallbacks();
        });

        // Dynamic Tables - RESTful style
        $builder->scope('/dynamic-tables', function (RouteBuilder $builder) {
            // CRUD operations
            $builder->connect('/', ['controller' => 'DynamicTables', 'action' => 'index'])
                   ->setMethods(['GET']);
            
            $builder->connect('/view/{tableName}', ['controller' => 'DynamicTables', 'action' => 'view'])
                   ->setMethods(['GET'])
                   ->setPass(['tableName']);
            
            $builder->connect('/add', ['controller' => 'DynamicTables', 'action' => 'add'])
                   ->setMethods(['GET', 'POST']);
            
            $builder->connect('/edit/{tableName}', ['controller' => 'DynamicTables', 'action' => 'edit'])
                   ->setMethods(['GET', 'POST', 'PUT'])
                   ->setPass(['tableName']);
            
            $builder->connect('/delete/{tableName}', ['controller' => 'DynamicTables', 'action' => 'delete'])
                   ->setMethods(['POST', 'DELETE'])
                   ->setPass(['tableName']);
            
            // Row operations
            $builder->connect('/{tableName}/rows/add', ['controller' => 'DynamicTables', 'action' => 'addRow'])
                   ->setMethods(['GET', 'POST'])
                   ->setPass(['tableName']);
            
            $builder->connect('/{tableName}/rows/edit/{id}', ['controller' => 'DynamicTables', 'action' => 'editRow'])
                   ->setMethods(['GET', 'POST', 'PUT'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['tableName', 'id']);
            
            $builder->connect('/{tableName}/rows/delete/{id}', ['controller' => 'DynamicTables', 'action' => 'deleteRow'])
                   ->setMethods(['POST', 'DELETE'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['tableName', 'id']);
            
            // Special operations
            $builder->connect('/{tableName}/move-row-up/{id}', ['controller' => 'DynamicTables', 'action' => 'moveRowUp'])
                   ->setMethods(['POST'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['tableName', 'id']);
            
            $builder->connect('/{tableName}/move-row-down/{id}', ['controller' => 'DynamicTables', 'action' => 'moveRowDown'])
                   ->setMethods(['POST'])
                   ->setPatterns(['id' => '\d+'])
                   ->setPass(['tableName', 'id']);
            
            // Structure operations
            $builder->connect('/{tableName}/structure', ['controller' => 'DynamicTables', 'action' => 'structure'])
                   ->setMethods(['GET', 'POST'])
                   ->setPass(['tableName']);
        });

        // Fallback for other controllers
        $builder->fallbacks();
    });

    // Enable pretty URLs (remove /index.php from URL)
    $routes->setExtensions(['html']);
};