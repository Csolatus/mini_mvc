<?php
// Active le mode strict pour les types
declare(strict_types=1);
// Espace de noms du noyau
namespace Mini\Core;
// Déclare le routeur HTTP minimaliste
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

        // On récupère les segments de l'URL actuelle (ex: /orders/5 -> ['orders', '5'])
        $pathParts = explode('/', trim($path, '/'));

        foreach ($this->routes as [$routeMethod, $routePath, $handler]) {
            if ($method !== $routeMethod) {
                continue;
            }

            // On regarde si c'est une route avec paramètre (ex: /orders/{id})
            if (strpos($routePath, '{') !== false) {
                $routeParts = explode('/', trim($routePath, '/'));

                // Si pas le même nombre de bouts, ça ne matche pas
                if (count($pathParts) !== count($routeParts)) {
                    continue;
                }

                $params = [];
                $match = true;

                for ($i = 0; $i < count($routeParts); $i++) {
                    // Si c'est un paramètre {id}, on garde la valeur
                    if (strpos($routeParts[$i], '{') !== false) {
                        $params[] = $pathParts[$i];
                    }
                    // Sinon, ça doit être identique (ex: "orders" == "orders")
                    elseif ($routeParts[$i] !== $pathParts[$i]) {
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
            }
            // Route simple (ex: /products)
            else {
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


