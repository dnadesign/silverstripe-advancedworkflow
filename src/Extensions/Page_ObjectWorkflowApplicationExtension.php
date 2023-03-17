<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\Control\Controller;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\FormAction;
use SilverStripe\ORM\DataExtension;
use SilverStripe\View\Requirements;
use Symbiote\AdvancedWorkflow\Extensions\AdvancedWorkflowExtension;
use Symbiote\AdvancedWorkflow\Extensions\WorkflowApplicable;

/**
 * This extension pushes the "Cancel Workflow" CMS action
 */
class Page_CancelActiveWorkflowExtension extends DataExtension
{
    public function canCancelWorkflow()
    {
        $active = $this->owner->getWorkflowInstance();
        if ($active) {
            return $active->canCancel();
        }
        return false;
    }

    public function updateCMSActions(FieldList $actions)
    {
        $service = $this->owner->getExtensionInstance(WorkflowApplicable::class)->getWorkflowService();

        if (!$service) {
            return false;
        }

        $active = $service->getWorkflowFor($this->owner);

        if (!Controller::curr() || !Controller::curr()->hasExtension(AdvancedWorkflowExtension::class) || !$active) {
            return false;
        }

        if ($this->owner->canEditWorkflow() || !$this->owner->canCancelWorkflow()) {
            return false;
        }

        Requirements::javascript('symbiote/silverstripe-advancedworkflow:client/dist/js/CancelButton.js');
        $action = FormAction::create('cancelworkflow', 'Cancel Workflow')
            ->setUseButtonTag(true)->setAttribute('data-icon', 'navigation');

        if ($actions->fieldByName('MajorActions')) {
            $actions->fieldByName('MajorActions')->push($action);
        } elseif ($actions->fieldByName('preview')) {
            $actions->insertBefore('preview', $action);
        } else {
            $actions->push($action);
        }
    }
}
