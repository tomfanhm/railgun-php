<?php

declare(strict_types=1);

namespace App\Core;

class Application
{
    /**
     * @property static $ROOT_DIR The root directory of the application.
     */
    public static string $ROOT_DIR;
    /**
     * @property static $app Application $app The instance of the application.
     */
    public static Application $app;
    /**
     * @property Router $router The router component responsible for handling routes.
     */
    public Router $router;
    /**
     * @property Request $request The request component that handles HTTP requests.
     */
    public Request $request;
    /**
     * @property Response $response The response component that handles HTTP responses.
     */
    public Response $response;
    /**
     * @property Database $db The database component for database interactions.
     */
    public Database $db;
    /**
     * @property Session $session The session component for managing user sessions.
     */
    public Session $session;
    /**
     * @property View $view The view component for rendering views.
     */
    public View $view;

    public function __construct(string $rootDir)
    {
        $this->loadEnv();
        self::$ROOT_DIR = $rootDir;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->router = new Router($this->request, $this->response);
        $this->db = new Database();
        $this->session = new Session();
        $this->view = new View();
    }

    /**
     * Loads environment variables from a .env file located in the document root.
     *
     * @throws \Exception If the .env file is not found.
     */
    private function loadEnv(): void
    {
        $path = $_SERVER['DOCUMENT_ROOT'] . '/.env';
        if (!file_exists($path)) {
            throw new \Exception("Environment file not found: {$path}");
        }
        $env = file_get_contents($path);
        $lines = explode("\n", $env);

        foreach ($lines as $line) {
            preg_match("/([^#]+)\=(.*)/", $line, $matches);
            if (isset($matches[2])) {
                putenv(trim($line));
            }
        }
    }

    /**
     * Runs the application by resolving the current route.
     * If an exception occurs during route resolution, it aborts with a 500 status code and an error message.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            $this->router->resolve();
        } catch (\Exception $e) {
            $this->router->abort(500, 'Internal Server Error', $e->getMessage());
        }
    }
}
