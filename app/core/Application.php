<?php

declare(strict_types=1);

namespace App\Core;

class Application
{
    public static string $ROOT_DIR;
    public static Application $app;
    public Router $router;
    public Request $request;
    public Response $response;
    public Database $db;
    public Session $session;
    public View $view;

    public function __construct(string $rootDir)
    {
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        $this->session = new Session();
        $this->view = new View();
    }

    public function run(): void
    {
        $this->router->resolve();
        $this->request->getPath();
    }
}
