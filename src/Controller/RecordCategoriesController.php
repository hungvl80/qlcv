<?php
declare(strict_types=1);

namespace App\Controller;

class RecordCategoriesController extends AppController
{
    public function index()
    {
        $categories = $this->paginate($this->RecordCategories);
        $this->set(compact('categories'));
    }
}
