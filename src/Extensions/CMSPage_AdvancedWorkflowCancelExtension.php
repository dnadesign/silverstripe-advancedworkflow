<?php

namespace Symbiote\AdvancedWorkflow\Extensions;

use SilverStripe\Admin\LeftAndMainExtension;
use SilverStripe\Control\Email\Email;
use SilverStripe\Forms\Form;
use SilverStripe\Security\Security;
use Symbiote\AdvancedWorkflow\DataObjects\WorkflowInstance;
use Symbiote\AdvancedWorkflow\Services\WorkflowService;
use Swift_RfcComplianceException;

class CMSPage_AdvancedWorkflowCancelExtension extends LeftAndMainExtension
{
    public function updateWorkflowEditForm(Form $form)
    {
        $tab = $form->Fields()->fieldByName('Root.WorkflowActions');
        $tab->addExtraClass('ss-tabs-force-active');
        if (!$form->getRecord()->canEditWorkflow()) {
            $action = $form->Actions()->dataFieldByName('action_cancelworkflow');
            if ($action) {
                $action->setReadonly(false);
            }
        }
    }

    public function cancelworkflow($data, Form $form, $request)
    {
        $svc = singleton(WorkflowService::class);
        $p = $form->getRecord();
        $workflow = $svc->getWorkflowFor($p);

        if (!$p || !$p->canCancelWorkflow()) {
            return false;
        }

        $this->sendEmail($workflow);

        $workflow->delete();

        return $this->owner->getResponseNegotiator()->respond($this->owner->getRequest());
    }

    /**
     * Subject: Page approval cancelled, Email content: {$Initiator.FirstName} {$Initiator.Surname} has cancelled the request to approve {$Context.Title}.
     */
    public function sendEmail(WorkflowInstance $workflow)
    {
        $members = $workflow->getAssignedMembers();

        if (!$members || !count($members)) {
            return false;
        }

        $subject = 'Page approval cancelled';
        $data = [
            'Context' => $workflow->getTarget(),
            'Canceller' => Security::getCurrentUser()
        ];

        foreach ($members as $member) {
            if (!$member->Email || $member->Email === '') {
                continue;
            }
            $email = Email::create();
            try {
                $email->setTo($member->Email);
            } catch (Swift_RfcComplianceException $exception) {
                // If the email address isn't valid we should skip it rather than break
                // the rest of the processing
                continue;
            }
            $email->setSubject($subject);
            $email->setHTMLTemplate('email\\CancelEmail');
            $email->setData($data);
            $email->send();
        }

        return true;
    }
}
