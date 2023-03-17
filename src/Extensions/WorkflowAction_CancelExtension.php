<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\Forms\CheckboxField;
use SilverStripe\Forms\FieldList;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\FieldType\DBBoolean;

class WorkflowAction_CancelExtension extends DataExtension
{
    private static $db = array(
        'AllowCancel' => DBBoolean::class,
    );

    public function updateCMSFields(FieldList $fields)
    {
        $fields->addFieldToTab('Root.Main', new CheckboxField('AllowCancel', 'Allow users to cancel this workflow?'), 'AllowCommenting');
    }
}
