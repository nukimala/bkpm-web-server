<?php
// app/Controllers/HomeController.php
class HomeController
{
    public function index(): void
    {
        $content = __DIR__ . '/../Views/home/index.php';
        require __DIR__ . '/../Views/layouts/main.php';
    }
}