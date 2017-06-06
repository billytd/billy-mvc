<?php
namespace BillyMVC\Controller;

use BillyMVC\Model\ExampleModel;

class IndexController extends BaseController
{

    public function index()
    {
        ?>
        <h1>Landing Page</h1>
        <p>Welcome!</p>
        <?php
    }
}
