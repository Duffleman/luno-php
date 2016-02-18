<?php

namespace Duffleman\Luno\Models;

class Session extends Base
{

    use Creatable, Findable, Savable, Deletable, CustomFields;

    protected $endpoint = '/sessions';
    protected $customFieldSetName = 'details';
    protected $editableFields = [
        'ip',
        'user_agent',
        'details',
    ];
}