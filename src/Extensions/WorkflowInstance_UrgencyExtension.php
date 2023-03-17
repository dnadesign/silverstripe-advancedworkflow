<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DropdownField;
use SilverStripe\ORM\DataExtension;
use SilverStripe\ORM\Filters\ExactMatchFilter;
use SilverStripe\ORM\Search\SearchContext;
use Symbiote\AdvancedWorkflow\DataObjects\WorkflowDefinition;

// DateRangeFilter - missing
// DateRangeSelectorField - missing

/**
 * Adds urgency field to WorkflowInstance
 */
class WorkflowInstance_UrgencyExtension extends DataExtension
{
    private static $db = array(
        'URL' => 'Varchar(255)',
        'IsUrgent' => 'Enum("No,Yes","No")',
        'ChangeLevel' => 'Enum("New page,Minor edit,Major edit,Re-submission","New page")',
    );

    private static $summary_fields = array(
        // 'Definition.Object',
        'Initiator.Name',
    );

    private static $searchable_fields = array(
        'Title',
        // 'Definition.Object',
        'InitiatorID',
        'WorkflowStatus',
        'Created',
        'IsUrgent',
        'ChangeLevel',
    );

    public static $change_levels = array(
        'New page' => 'New page',
        'Minor edit' => 'Minor edit (e.g. change to word, sentence, link, image)',
        'Major edit' => 'Major edit (e.g. change to paragraphs, page layout etc)',
        'Re-submission' => 'Re-submission (have fixed changes requested by Admin)',
    );

    /**
     * Add in URL so PWT can sort by section
     */
    public function onBeforeWrite()
    {
        if ($this->owner->TargetID) {
            $page = SiteTree::get()->ByID($this->owner->TargetID);
            if ($page && $page->exists()) {
                $this->owner->URL = $page->Link();
            }
        }
    }

    public function getCustomSearchContext()
    {
        $fields = $this->owner->scaffoldSearchFields();
        $wd = WorkflowDefinition::get();

        if ($wd->Count() < 1) {
            return false;
        }

        $filters = $this->owner->defaultSearchFilters();
        $filters['InitiatorID'] = new ExactMatchFilter('InitiatorID');

        $dbUrgent = $this->owner->dbObject('IsUrgent');

        if ($dbUrgent) {
            $urgencies = $dbUrgent->enumValues();
            $urgencyField = DropdownField::create('IsUrgent', 'Is urgent', $urgencies)->setEmptyString('(Any)');
            $fields->push($urgencyField);
        }

        $levelField = DropdownField::create('ChangeLevel', 'Change level', self::$change_levels)->setEmptyString('(Any)');
        $fields->push($levelField);

        return new SearchContext(
            get_class($this->owner),
            $fields,
            $filters
        );
    }

    public function CommentsSoFar()
    {
        $string = '';
        $i = 0;
        foreach ($this->owner->Actions() as $action) {
            if ($action->Comment) {
                $i++;
                if ($i > 1) {
                    $string .= '<br>';
                }
                $string .= '<strong>' . $action->Member()->getName() . ':</strong> ' . $action->Comment;
            }
        }
        return $string;
    }

    public function canCancel()
    {
        return $this->owner->CurrentAction()->canCancel();
    }
}
