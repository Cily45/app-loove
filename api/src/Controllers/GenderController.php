<?php
namespace App\Controllers;

use App\Models\GenderModel;

class GenderController extends BaseController
{
    public function liste()
    {
        $model = new GenderModel();
        return json_encode($model->all(), JSON_PRETTY_PRINT);
    }
}
