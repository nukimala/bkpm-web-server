<?php
// app/Controllers/HomeController.php
require_once __DIR__ . '/../Core/BaseController.php';

class HomeController extends BaseController
{
    public function index(): void
    {
        $this->view('home/index');
    }
}