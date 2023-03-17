<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\Forms\LiteralField;
use SilverStripe\Forms\OptionsetField;
use SilverStripe\ORM\DataExtension;

/**
 * Adds urgency field to WorkflowActionInstance
 */
class WorkflowActionInstance_UrgencyExtension extends DataExtension
{
    private static $db = array(
        'IsUrgent' => 'Enum("No,Yes","No")',
        'ChangeLevel' => 'Enum("New page,Minor edit,Major edit,Re-submission","New page")',
    );

    public function onBeforeWrite()
    {
        if (!$this->owner->IsInDB()) {
            $this->owner->IsUrgent = $this->owner->Workflow()->IsUrgent;
            $this->owner->ChangeLevel = $this->owner->Workflow()->ChangeLevel;
        }
    }

    public function onAfterWrite()
    {
        $this->owner->Workflow()->IsUrgent = $this->owner->IsUrgent;
        $this->owner->Workflow()->ChangeLevel = $this->owner->ChangeLevel;
        $this->owner->Workflow()->write();
    }

    public function updateWorkflowFields($fields)
    {
        $comments = $this->owner->Workflow()->CommentsSoFar();
        if ($comments) {
            $fields->push(new LiteralField('CommentsSoFar', '<div class="field textarea"><h2 class="left">Comments</h2><div class="middleColumn"><p>' . $comments . '</p></div><hr></div>'));
        }

        $fields->push(new OptionsetField('IsUrgent', 'Urgent change?', $this->owner->dbObject('IsUrgent')->enumValues()));
        $fields->push(new OptionsetField('ChangeLevel', 'Level of change', WorkflowInstance_UrgencyExtension::$change_levels));
    }

    public function canCancel($member = null)
    {
        if ($this->owner->BaseAction()->AllowCancel) {
            return $this->owner->Workflow()->getTarget()->canEdit();
        } else {
            return false;
        }
    }
}
