<?php

declare(strict_types=1);

namespace Mini\Core;

final class Router
{
    private array $routes;

    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }



    public function dispatch(string $method, string $uri): void
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';


        $pathParts = explode('/', trim($path, '/'));

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            if ($method !== $routeMethod) {
                continue;
            }


            if (strpos($routePath, '{') !== false) {
                $routeParts = explode('/', trim($routePath, '/'));


                if (count($pathParts) !== count($routeParts)) {
                    continue;
                }

                $params = [];
                $match = true;

                for ($i = 0; $i < count($routeParts); $i++) {

                    if (strpos($routeParts[$i], '{') !== false) {
                        $params[] = $pathParts[$i];
                    } elseif ($routeParts[$i] !== $pathParts[$i]) {
                        $match = false;
                        break;
                    }
                }

                if ($match) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    call_user_func_array([$controller, $action], $params);
                    return;
                }
            } else {
                if ($path === $routePath) {
                    [$class, $action] = $handler;
                    $controller = new $class();
                    $controller->$action();
                    return;
                }
            }
        }

        http_response_code(404);
        echo '404 - Page non trouvée';
    }
}


