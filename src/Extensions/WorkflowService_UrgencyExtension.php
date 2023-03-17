<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\Core\Extension;

/**
 * Changes the sort order of the workflow tasks in the workflow tab so that urgent tasks are at the top
 */
class WorkflowService_UrgencyExtension extends Extension
{
    public function updateUserPendingItems(&$instances)
    {
        $instances = $instances->sort('IsUrgent DESC');
    }

    public function updateUserSubmittedItems(&$instances)
    {
        $instances = $instances->sort('IsUrgent DESC');
    }
}
