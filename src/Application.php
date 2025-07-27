<?php
declare(strict_types=1);

namespace App;

use Authentication\AuthenticationService;
use Authentication\AuthenticationServiceInterface;
use Authentication\AuthenticationServiceProviderInterface;
use Authentication\Middleware\AuthenticationMiddleware;
use Cake\Core\Configure;
use Cake\Core\ContainerInterface;
use Cake\Datasource\FactoryLocator;
use Cake\Error\Middleware\ErrorHandlerMiddleware;
use Cake\Http\BaseApplication;
use Cake\Http\Middleware\BodyParserMiddleware;
use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Http\Middleware\RequestHandlerMiddleware;
use Cake\Http\MiddlewareQueue;
use Cake\ORM\Locator\TableLocator;
use Cake\Routing\Middleware\AssetMiddleware;
use Cake\Routing\Middleware\RoutingMiddleware;
use Psr\Http\Message\ServerRequestInterface;

class Application extends BaseApplication implements AuthenticationServiceProviderInterface
{
    public function bootstrap(): void
    {
        parent::bootstrap();

        if (PHP_SAPI !== 'cli') {
            FactoryLocator::add(
                'Table',
                (new TableLocator())->allowFallbackClass(false)
            );
        }
    }

    public function middleware(MiddlewareQueue $middlewareQueue): MiddlewareQueue
    {
        $middlewareQueue
            // Xử lý lỗi trước tiên
            ->add(new ErrorHandlerMiddleware(Configure::read('Error'), $this))

            // Xử lý các yêu cầu tài sản (assets)
            ->add(new AssetMiddleware([
                'cacheTime' => Configure::read('Asset.cacheTime'),
            ]))

            // Phân tích cú pháp các body yêu cầu
            ->add(new BodyParserMiddleware([
                'json' => true,  // Bật phân tích JSON
                'xml' => true    // Bật phân tích XML (nếu cần)
            ]))

            // Xử lý xác thực người dùng
            ->add(new AuthenticationMiddleware($this))

            // Xử lý định tuyến các yêu cầu
            ->add(new RoutingMiddleware($this))

            // Bảo vệ CSRF (Cross-Site Request Forgery)
            ->add(new CsrfProtectionMiddleware([
                'httponly' => true,
                'secure' => true,
                'whitelist' => [
                    '/dynamic-tables/*', // Bỏ qua CSRF cho các route API
                    '/api/*'            // Thêm các route API khác nếu cần
                ]
            ]));

        return $middlewareQueue;
    }

    public function services(ContainerInterface $container): void
    {
    }

    public function getAuthenticationService(ServerRequestInterface $request): AuthenticationServiceInterface
    {
        $service = new AuthenticationService();

        $service->setConfig([
            'unauthenticatedRedirect' => '/qlcv/login',
            'queryParam' => 'redirect',
        ]);

        $service->loadIdentifier('Authentication.Password', [
            'fields' => [
                'username' => 'username',
                'password' => 'password',
            ],
            'resolver' => [
                'className' => 'Authentication.Orm',
                'userModel' => 'Users',
                'finder' => 'auth',
            ],
            'passwordHasher' => [
                'className' => \Authentication\PasswordHasher\DefaultPasswordHasher::class,
            ],
        ]);

        $service->loadAuthenticator('Authentication.Session');

        $service->loadAuthenticator('Authentication.Form', [
            'fields' => [
                'username' => 'username',
                'password' => 'password',
            ],
            'loginUrl' => '/qlcv/login',
        ]);

        return $service;
    }

}
