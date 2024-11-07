<?php

declare(strict_types=1);

namespace App\Core;

use App\Controllers\ErrorController;
use App\Exceptions\CustomException;
use App\Exceptions\RouteNotFoundException;

class Router
{
    public Request $request;
    public Response $response;
    /**
     * Stores registered routes, organized by HTTP method and path.
     *
     * @var array<string, array<string, array<string, string>>>
     */
    private array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }

    /**
     * Adds a new route to the router.
     *
     * @param string $path URL path for the route.
     * @param string $controller Controller associated with the route.
     * @param string $action Action method within the controller.
     * @param string $method HTTP method (e.g., GET, POST).
     *
     * @return void
     */
    private function add(string $path, string $controller, string $action, string $method): void
    {
        $this->routes[$method][$path] = ['controller' => $controller, 'action' => $action];
    }

    /**
     * Registers a route for GET requests.
     *
     * @param string $path URL path for the route.
     * @param string $controller Controller handling the route.
     * @param string $action Method in the controller for the route.
     *
     * @return void
     */
    public function get(string $path, string $controller, string $action): void
    {
        $this->add($path, $controller, $action, 'get');
    }

    /**
     * Registers a route for POST requests.
     *
     * @param string $path URL path for the route.
     * @param string $controller Controller handling the route.
     * @param string $action Method in the controller for the route.
     *
     * @return void
     */
    public function post(string $path, string $controller, string $action): void
    {
        $this->add($path, $controller, $action, 'post');
    }

    /**
     * Registers a route for PUT requests.
     *
     * @param string $path URL path for the route.
     * @param string $controller Controller handling the route.
     * @param string $action Method in the controller for the route.
     *
     * @return void
     */
    public function put(string $path, string $controller, string $action): void
    {
        $this->add($path, $controller, $action, 'put');
    }

    /**
     * Registers a route for DELETE requests.
     *
     * @param string $path URL path for the route.
     * @param string $controller Controller handling the route.
     * @param string $action Method in the controller for the route.
     *
     * @return void
     */
    public function delete(string $path, string $controller, string $action): void
    {
        $this->add($path, $controller, $action, 'delete');
    }

    /**
     * Registers a route that responds to any HTTP method.
     *
     * @param string $path The URL path for the route.
     * @param string $controller The controller class that will handle the request.
     * @param string $action The method within the controller that will handle the request.
     *
     * @return void
     */
    public function any(string $path, string $controller, string $action): void
    {
        $methods = ['get', 'post', 'put', 'delete', 'patch', 'options', 'head'];
        foreach ($methods as $method) {
            $this->add($path, $controller, $action, $method);
        }
    }

    /**
     * Resolves the current request by matching it against the defined routes.
     *
     * This method retrieves the request path and method, then iterates through the routes defined for that method.
     * If a matching route is found, it extracts the controller and action, instantiates the controller, and calls the action with the route parameters.
     * If no matching route is found, it sends a 404 response.
     * If the controller or action is not found, it sends a 500 response.
     *
     * @return void
     */
    public function resolve(): void
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $methodRoutes = $this->routes[$method] ?? [];

        foreach ($methodRoutes as $routePath => $route) {
            $routeParams = $this->matchRoute($routePath, $path);

            if ($routeParams !== null) {
                $controllerName = 'App\Controllers\\' . $route['controller'];
                $action = $route['action'];

                if (class_exists($controllerName) && method_exists($controllerName, $action)) {
                    $controller = new $controllerName();
                    call_user_func_array([$controller, $action], $routeParams);

                    return;
                } else {
                    throw new CustomException(500, 'Internal server error', 'The controller or action does not exist.');
                }
            }
        }

        throw new RouteNotFoundException();
    }

    /**
     * Matches a given request path against a defined route path and extracts parameters if any.
     *
     * @param string $routePath The defined route path, which may contain dynamic segments.
     * @param string $requestPath The actual request path to match against the route.
     * @return array|null An associative array of parameters if the paths match, or null if they do not.
     */
    private function matchRoute(string $routePath, string $requestPath): ?array
    {
        $routeSegments = explode('/', trim($routePath, '/'));
        $pathSegments = explode('/', trim($requestPath, '/'));

        // Check if the segment counts are the same
        if (count($routeSegments) !== count($pathSegments)) {
            return null;
        }

        $params = [];

        // Compare each segment
        foreach ($routeSegments as $index => $segment) {
            if ($segment === $pathSegments[$index]) {
                // Exact match, continue
                continue;
            } elseif (str_starts_with($segment, '{') && str_ends_with($segment, '}')) {
                // Dynamic segment found, capture as parameter
                $paramName = trim($segment, '{}');
                $params[$paramName] = $pathSegments[$index];
            } else {
                // Segment mismatch
                return null;
            }
        }

        return $params;
    }

    /**
     * Aborts the current process and triggers the error handling mechanism.
     *
     * @param int $code The HTTP status code to be used for the error.
     * @param string $message A brief message describing the error.
     * @param string $description A detailed description of the error.
     *
     * @return void
     */
    public function abort(int $code, string $message, string $description): void
    {
        $controller = new ErrorController();
        $controller->index($code, $message, $description);
    }
}
