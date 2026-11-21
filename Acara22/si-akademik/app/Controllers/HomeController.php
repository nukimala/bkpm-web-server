<?php
// app/Controllers/HomeController.php
require_once __DIR__ . '/../Core/Controller.php';

class HomeController extends Controller
{
    public function index(): void
    {
        $this->view('home/index.php');
    }
}