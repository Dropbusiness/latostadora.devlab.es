/**
 * Module for displaying "Waiting for..." dialog using Bootstrap
 *
 * @author Eugene Maslovich <ehpc@em42.ru>
 */

(function (root, factory) {
    'use strict';

    if (typeof define === 'function' && define.amd) {
        define(['jquery'], function ($) {
            return (root.waitingDialog = factory($));
        });
    }
    else {
        root.waitingDialog = root.waitingDialog || factory(root.jQuery);
    }

}(this, function ($) {
    'use strict';

    /**
     * Dialog DOM constructor
     */
    function constructDialog($dialog, settings) {
        // Deleting previous incarnation of the dialog
        if ($dialog) {
            $('.modal').remove();
            $('.modal-backdrop').remove();
            $('body').removeClass("modal-open");
            $("body").removeAttr("style");
            $dialog.remove();
        }
        $('#bootstrapWaitingforModal').remove();
        var elem = $.parseHTML(
                '<div id="bootstrapWaitingforModal" class="modal" ' +
                'data-backdrop="static"  tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
                    '<div class="modal-dialog modal-m">' +
                        '<div class="modal-content">' +
                            '<div class="modal-header"><h5 class="modal-title"></h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>' +
                            '<div class="modal-body">' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
        $('body').append(elem);
        return $('#bootstrapWaitingforModal');
    }

    var $dialog, // Dialog object
        settings, // Dialog settings
        modalEl, // Modal DOM element
        modal; // Bootstrap modal object

    return {
        show: function (message='',title='', options) {
            if (typeof options === 'undefined') {
                options = {};
            }
            settings = $.extend({
                headerText: '',
                onHide: null, // This callback runs after the dialog was hidden
                onShow: null, // This callback runs after the dialog was shown
            }, options);

            $dialog = constructDialog($dialog, settings);
           if (message !='')
                $dialog.find('.modal-body').html(message);
           if (title !='')
                $dialog.find('.modal-title').html(title);
            // Adding callbacks
            if (typeof settings.onHide === 'function') {
                $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function () {
                    settings.onHide.call($dialog);
                });
            }
            if (typeof settings.onShow === 'function') {
                $dialog.off('shown.bs.modal').on('shown.bs.modal', function () {
                    settings.onShow.call($dialog);
                });
            }

            // Opening dialog
            $dialog.modal();

            modalEl = document.getElementById('bootstrapWaitingforModal');
            if (window.bootstrap && window.bootstrap.Modal && window.bootstrap.Modal.getOrCreateInstance) {
                modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);
                modal.show();
            }

            if (modal) {
                modalEl.addEventListener('shown.bs.modal', function () {
                    $dialog.data('shown', true);
                });
            }
            // Trace if dialog is shown
            $dialog.on('shown.bs.modal', function () {
                $dialog.data('shown', true);
            });
        },
        /**
         * Closes dialog
         * @param cb Callback after hide
         */
        hide: function (cb) {
            if (typeof $dialog !== 'undefined') {
                if ($dialog.data('shown') === true) {
                    if (modal) {
                        modal.hide();
                    }
                    else {
                        $dialog.modal('hide');
                    }
                    if (cb) {
                        cb($dialog);
                    }
                }
                else {
                    if (modal) {
                        modalEl.addEventListener('shown.bs.modal', function () {
                            modal.hide();
                            if (cb) {
                                cb($dialog);
                            }
                        });
                    } else {
                        $dialog.on('shown.bs.modal', function () {
                            $dialog.modal('hide');
                            if (cb) {
                                cb($dialog);
                            }
                        });
                    }
                }
                /** */
                if ($dialog) {
                    $('.modal').remove();
                    $('.modal-backdrop').remove();
                    $('body').removeClass("modal-open");
                    $("body").removeAttr("style");
                    $dialog.remove();
                }
            }
        }
        
    };

}));
