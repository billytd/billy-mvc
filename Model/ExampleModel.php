<?php
namespace BillyMVC\Model;

use BillyMVC\Configure;

class ExampleModel extends Model {
    protected $table = 'apps';

    public function __construct(array $data = null)
    {
        if ($data) {
            $this->setData($data);
        }
    }

    public function save()
    {
        $data = $this->getData();

        if (isset($data['id'])) {
            // update
            unset($data['id'], $data['updated'], $data['created']);
            return $this->update($data, ['id' => $this->getData('id')]);
        } else {
            // insert
            if ($insert_id = $this->add([$data])) {
                $this->loadById($insert_id);
            }

            return (bool)$insert_id;
        }
    }
}
