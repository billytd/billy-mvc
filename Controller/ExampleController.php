<?php
namespace BillyMVC\Controller;

use BillyMVC\Model\ExampleModel;

class ExampleController extends BaseController
{

    public function index()
    {
        ?>
        <h1>Title Example</h1>
        <ul>
            <li>One</li>
            <li>Two</li>
            <li>Three</li>
        </ul>
        <?php
    }

    /**
     * This creates an endpoint at /example/fake/ (/#controller#/#action#/)
     */
    public function fakeAction()
    {
        return;

        // Simple examples (Requires Doctrine DBAL)


        // Empty table: logs
        $qb = $this->getDb()->createQueryBuilder();
        $qb->delete('logs');
        $result = $qb->execute();


        // custom select with returned result rows as arrays:
        foreach ($this->getDb()->query("SELECT * FROM apps ORDER BY id DESC") as $row) {
            $row['id'];
        }


        // interacting with a model:
        $app = new ExampleModel();
        $app->loadById($id); // load by ID
        $app->loadByData(['name' => $somename]); // or build where clause by array of key => value pairs
        $app->getData('id'); // get column value


        // create new row:
        $app = new ExampleModel($app_data); // $app_data is key, value pairs
        $app->save();
    }
}
