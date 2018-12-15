$(function () {
    'use strict';

    jQuery('[data-action="popup"]').on('click',function(e) {
        e.preventDefault();
        var target = $(e.currentTarget);
        var field  = target.data('field');

        // TODO simiplify by pushing the retrieveSelectedRecords to library
        var popupInstance = Vtiger_Popup_Js.getInstance();
        popupInstance.show(target.data('url'));
        popupInstance.retrieveSelectedRecords(function(data) {
            try {
                data = JSON.parse(data);
            } catch (e) {}

            if (typeof data == 'object') {
                jQuery('[name="'+field+'_display"]').val(data.label);
                data = data.value;
            }
            jQuery('[name="'+field+'"]').val(data);
        });
    });

    jQuery('#clearRole').on('click',function(e){
        jQuery('[name="transfer_record_display"]').val('');
    });


    jQuery('.toolbar').hide();

    jQuery('.toolbar-handle').bind('mouseover', function(e){
        var target = $(e.currentTarget);
        jQuery('.toolbar', target).css({display: 'inline'});
    });
    jQuery('.toolbar-handle').bind('mouseout', function(e){
        var target = $(e.currentTarget);
        jQuery('.toolbar', target).hide();
    });
    jQuery('[rel="tooltip"]').tooltip();

    jQuery('.draggable').draggable({
        containment: '.treeView',
        start : function(event, ui) {
            var container = jQuery(ui.helper);
            var referenceid = container.data('refid');
            var sourceGroup = jQuery('[data-grouprefid="'+referenceid+'"]');
            var sourceRoleId = sourceGroup.data('roleid');
            if(sourceRoleId == 'H5' || sourceRoleId == 'H2') {
                /*var params = {};
                params.title = app.vtranslate('JS_PERMISSION_DENIED');
                params.text = app.vtranslate('JS_NO_PERMISSIONS_TO_MOVE');
                params.type = 'error';
                Settings_Vtiger_Index_Js.showMessage(params);*/
            }
        },
        helper: function(event) {
            var target = $(event.currentTarget);
            var targetGroup = target.closest('li');
            var timestamp = +(new Date());

            var container = $('<div/>');
            container.data('refid', timestamp);
            container.html(targetGroup.clone());

            // For later reference we shall assign the id before we return
            targetGroup.attr('data-grouprefid', timestamp);

            return container;
        }
    });
    jQuery('.droppable').droppable({
        hoverClass: 'btn-primary',
        tolerance: 'pointer',
        drop: function(event, ui) {
            var container = $(ui.helper);
            var referenceid = container.data('refid');
            var sourceGroup = $('[data-grouprefid="'+referenceid+'"]');

            var thisWrapper = $(this).closest('div');

            var targetRole  = thisWrapper.closest('li').data('role');
            var targetRoleId= thisWrapper.closest('li').data('roleid');
            var sourceRole   = sourceGroup.data('role');
            var sourceRoleId = sourceGroup.data('roleid');

            // Attempt to push parent-into-its own child hierarchy?
            if (targetRole.indexOf(sourceRole) == 0) {
                // Sorry
                return;
            }
            //Attempt to move the roles CEO and Sales Person
            if (sourceRoleId == 'H5' || sourceRoleId == 'H2') {
                return;
            }
            sourceGroup.appendTo(thisWrapper.next('ul'));

            applyMoveChanges(sourceRoleId, targetRoleId);
        }
    });

    jQuery('[name="profile_directly_related_to_role"]').on('change',function(e){
        var target = jQuery(e.currentTarget);
        var hanlder = target.data('handler');
        if(hanlder == 'new'){
            Settings_Roles_Js.getProfilePriviliges();return false;
        }
        var container = jQuery('[data-content="'+ hanlder + '"]');
        jQuery('[data-content]').not(container).fadeOut('slow',function(){
            container.fadeIn('slow');
        });
    });

    function applyMoveChanges(roleid, parent_roleid) {
        var params = {
            module: 'Roles',
            action: 'MoveAjax',
            parent: 'Settings',
            record: roleid,
            parent_roleid: parent_roleid
        }

        AppConnector.request(params).then(function(res) {
            if (!res.success) {
                alert(app.vtranslate('JS_FAILED_TO_SAVE'));
                window.location.reload();
            }
        });
    }

    jQuery('#directProfilePriviligesSelect').on('change',function(e) {
        var profileId = jQuery(e.currentTarget).val();
        var params = {
            module : 'Profiles',
            parent: 'Settings',
            view : 'EditAjax',
            record : profileId
        }
        var progressIndicatorElement = jQuery.progressIndicator({
            'position' : 'html',
            'blockInfo' : {
                'enabled' : true
            }
        });

        AppConnector.request(params).then(function(data) {
            jQuery('[data-content="new"]').find('.fieldValue').html(data);
            progressIndicatorElement.progressIndicator({
                'mode' : 'hide'
            });
            app.changeSelectElementView(jQuery('#directProfilePriviligesSelect'), 'select2');
            Settings_Roles_Js.registerExistingProfilesChangeEvent();
        });
    });



});