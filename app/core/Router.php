<?php

declare(strict_types=1);

namespace App\Core;

class Router
{
    public Request $request;
    public Response $response;
    /**
     * Stores registered routes, organized by HTTP method and path.
     *
     * @var array<string, array<string, array<string, string>>> $routes
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
        $this->routes[$method][$path] = ["controller" => $controller, "action" => $action];
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
        $this->add($path, $controller, $action, "get");
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
        $this->add($path, $controller, $action, "post");
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
        $this->add($path, $controller, $action, "put");
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
        $this->add($path, $controller, $action, "delete");
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
        $methods = ["get", "post", "put", "delete", "patch", "options", "head"];
        foreach ($methods as $method) {
            $this->add($path, $controller, $action, $method);
        }
    }

    public function resolve(): void
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        $methodRoutes = $this->routes[$method] ?? [];
        foreach ($methodRoutes as $key => $route) {
        }
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
    private function abort(int $code, string $message, string $description): void
    {
        require_once Application::$ROOT_DIR . "/app/controllers/ErrorController.php";
        $controller = new \ErrorController();
        $controller->index($code, $message, $description);
    }
}
