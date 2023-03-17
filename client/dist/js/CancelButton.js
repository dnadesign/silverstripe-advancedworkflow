/**
 * File: CancelButton.js
 */

(function ($) {
    $.entwine('ss', function ($) {

        $('.CMSMain.cms-edit-form #Form_EditForm_action_cancelworkflow:submit[name=action_cancelworkflow]').entwine({
            onclick: function (e) {
                if (!confirm('Are you sure you want to cancel this workflow?')) {
                    return false;
                } else {
                    this._super(e);
                }
            }
        });

        $('.cms-edit-form #action_cancelworkflow:submit[name=action_cancelworkflow]').entwine({
            onclick: function (e) {
                if (!confirm('Are you sure you want to cancel this workflow?')) {
                    return false;
                } else {
                    this._super(e);
                }
            }
        });

    });
}(jQuery));
