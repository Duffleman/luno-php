<?php

namespace Duffleman\Luno\Models;

class Event extends Base
{

    use Creatable, Findable, Savable, Deletable, CustomFields;

    protected $endpoint = '/events';
    protected $customFieldSetName = 'details';
    protected $editableFields = [
        'details',
    ];

}