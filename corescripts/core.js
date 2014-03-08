/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2014
 * http://www.bertbeentjes.nl, http://www.bpadcms.nl
 * 
 * This file is part of the bPAD content management system.
 * 
 * bPAD is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * bPAD is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with bPAD.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * The frontend core javascript for html sites
 *
 * @version 0.4.0
 */

var sessionidentifier = 0; // the session for this page, used to process commands from this session in the right order
var lastcommandid = 0; // the id of the last command processed server side (any changes after this aren't present in the page), has to be  updated by the serverevent handler
var commandnumber = 0; // the number of the command
var checkcommandnumber = 0; // the number of the last command to check against
var showadminbuttons = false; // show the admin buttons or not, default not
var settings = "#settings#"; // insert the relevant site settings here, "#settings#" is replaced server side by a json string, the quotes are there to prevent an annoying syntax error
var processing = "#processing#"; // processing message, used in a modal dialog when processing something
var refreshinghash = false; // set to true when refreshing the hash from the code

var resulttohtml = function(container, replace, checkcommandnr, commandnr) {
    return function(result) {
        // if the message modal is visible, hide it
        if (container != 'modalmessage') {
            $('#modalcontainer').modal('hide');
        }
        resultToHTML(container, replace, checkcommandnr, commandnr, result);
    }
}
var docommand = function(thiscommand, checkcommandnr, thisvalue) {
    return function(result) {
        if (!showError(result)) {
            doCommand(thiscommand, checkcommandnr, thisvalue);
        }
    }
}
var docommandandresulttohtml = function(thiscommand, checkcommandnr, thisvalue, container, replace, checkcommandnr, commandnr) {
    return function(result) {
        resultToHTML(container, replace, checkcommandnr, commandnr, result);
        doCommand(thiscommand, checkcommandnr, thisvalue);
    }
}
var showerror = function() {
    return function(result) {
        showError(result);
    }
}

function showError(result) {
    if (result > '') {
        // if the message modal is visible, hide it, this is a final change and waiting on the result is not necessary
        $('#modalcontainer').modal('hide');
        // show the error message
        $('#errormessage').html(result);
        $('#errorcontainer').show();
        return true;
    }
    return false;
}

function resultToHTML(container, replace, checkcommandnr, commandnr, result) {
    // store the current location of the container on the page
    var contloc = 0;
    if ($('#' + container).length) {
        contloc = $('#' + container).offset().top - $('body').scrollTop();
    }
    // show the result
    if (replace) {
        // check whether the container to replace is coupled to this content fetch (it may have changed client-side during the roundtrip)
        if ($('#' + container).attr('data-bpad-command-number') + '-' + commandnr) {
            $('#' + container).replaceWith(result);
            // now add the events to the new html
            addEvents(container);
        }
    } else {
        // check the command number for new commands. If a new command is given, don't
        // process the result of an earlier command. Used for fetching an instance, to 
        // prevent the content from flickering when a search word is being typed, and 
        // to prevent an earlier result to overwrite a later result (the later result may
        // come faster because the result gets much smaller when a search word gets longer)
        if (!checkcommandnr || commandnr == checkcommandnumber) {
            $('#' + container).html(result);
            // now add the events to the new html
            addEvents(container);
            // now change the url to match the content
            refreshHash();
        }
    }
    // correct the position of the page after loading new content, only correct
    // if the page moved more than 5 pixels
    var newcontloc = $('#' + container).offset().top - $('body').scrollTop();
    if (Math.abs(contloc - newcontloc) > 5) {
        $('body').scrollTop($('#' + container).offset().top - contloc);
    }
}

/**
 * refresh the hash after loading content
 */
function refreshHash() {
    var newhash = '';
    $('[data-bpad-url-name!=""][data-bpad-url-name]').each(function() {
        newhash = newhash + '/';
        newhash = newhash + this.getAttribute('data-bpad-url-name');
    });
    if (newhash > '') {
        newhash = newhash + '.html';
    }
    if (window.location.hash != '#' + newhash) {
        refreshinghash = true;
        window.location.hash = newhash;
    }
}

function fetchContent() {    
    var hash = '';
    if (window.location.hash.length > 6) {
        hash = window.location.hash.substring(2, window.location.hash.length - 5);;
    }
    doCommand('object,' + hash + ',content.fetch', false, '');
}

/**
 * Initialize the page after the first load
 */
function doBootStrapping() {
    // initialize the session
    sessionidentifier = $("#bpad_content_root").attr("data-bpad-session-id");
    lastcommandid = $("#bpad_content_root").attr("data-bpad-command-id");
    // add events to the html where requested
    addEvents();
    // initialize the message dialog
    this.$('#modalcontainer').modal({
        backdrop: 'static',
        show: false
    });
    // initialize the error message box
    this.$('#errorcontainer').hide();
    // add lazy load events to the window
    $(window).on("resize", function() {
        lazyEvent();
    });
    $(window).on("scroll", function() {
        lazyEvent();
    });
    if (window.location.hash != '') {
        // fetch new content 
        fetchContent();
    }
    // monitor the hash
    $(window).on("hashchange", function () {
        if (refreshinghash) {
            // hash is changed from the code, ignore
            refreshinghash = false;
        } else {
            // get new content
            fetchContent();
        }
    });
}

/**
 * show or hide the admin buttons, depending on the global showadminbuttons settings
 */
function showAdminButtons() {
    if (showadminbuttons) {
        $('.btn-toggle').show();
    } else {
        $('.btn-toggle').hide();
    }
}

/**
 * Add events
 */
function addEvents(divid) {
    var selector = '';
    if (divid) {
        selector = '#' + divid + ' ';
    }
    // clean up open admin menus
    $('.btn-group').removeClass('open');
    // autosize textareas
    $(selector + 'textarea').autosize();
    // show or hide the admin buttons
    showAdminButtons();
    // add on click events to nodes that request it
    $(selector + '[data-bpad-onclick]').each(function() {
        // get the command
        var command = $(this).attr('data-bpad-onclick');
        switch (command) {
            case 'buttontoggle':
                // initialize the admin buttons
                showAdminButtons();
                // and attach an update function to the toggle button
                $(this).on("click", function() {
                    showadminbuttons = !showadminbuttons;
                    showAdminButtons();
                });
                break;
            case 'clearerror':
                $(this).on("click", function() {
                    $('#errorcontainer').hide();
                    $('#errormessage').html('');
                });
                break;
            default:
                // attach the event and pass the command info
                $(this).on('click', {
                    cmd: command
                }, function(event) {
                    doCommand(event.data.cmd, false);
                    // prevent a href from firing
                    return false;
                }
                );
        }
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onclick');
    });
    // add on click events to nodes that request it
    $(selector + '[data-bpad-onkeyup]').each(function() {
        // get the command
        var command = $(this).attr('data-bpad-onkeyup');
        // attach the event and pass the command info
        $(this).on('keyup', {
            cmd: command
        }, function(event) {
            // execute the command
            doCommand(event.data.cmd, true, this.value);
        }
        );
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onkeyup');
    });
    // add on change events to nodes that request it
    $(selector + '[data-bpad-onchange]').each(function() {
        // get the command
        var command = $(this).attr('data-bpad-onchange');
        // attach the event and pass the command info
        $(this).on('change', {
            cmd: command
        }, function(event) {
            // execute the command
            doCommand(event.data.cmd, true, this.value);
        }
        );
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onchange');
    });
    // add on change -> update the value to another field, used for combo boxes
    $(selector + '[data-bpad-onchange-update]').each(function() {
        // get the command
        var id = $(this).attr('data-bpad-onchange-update');
        // attach the event and pass the command info
        $(this).on('change', {
            id: id
        }, function(event) {
            // change something
            $('#' + id).val(this.value);
        }
        );
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onchange-update');
    });
    // add on change -> submit a form (used for auto file upload)
    $(selector + '[data-bpad-onchange-submit]').each(function() {
        // get the command
        var id = $(this).attr('data-bpad-onchange-submit');
        // attach the event and pass the command info
        $(this).on('change', {
            id: id
        }, function(event) {
            // submit
            $('#' + id).submit();
            // prevent the default event
            event.preventDefault();
        }
        );
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onchange-submit');
    });
    // start a lazy load sequence
    lazyEvent();
    // show markup for menu-items that are active
    $('[data-bpad-activate]').each(function(i) {
        // the activate condition
        var bpadactivate = this.getAttribute('data-bpad-activate');
        // the attribute to set
        var bpadattribute = this.getAttribute('data-bpad-attribute');
        // the value for the attribute
        var bpadvalue = this.getAttribute('data-bpad-value');
        // get the id of the object
        var bpaditemid = this.getAttribute('data-bpad-itemid');
        // if so, set the active value
        if (bpadactivate == 'object' && $('[data-bpad-objectid="' + bpaditemid + '"]').length) {
            if (this.getAttribute(bpadattribute) != bpadvalue + '_active' && this.getAttribute(bpadattribute) != bpadvalue + '_active.png') {
                if (bpadattribute == 'class') {
                    this.className = bpadvalue + ' active';
                } else {
                    if (bpadattribute == 'src') {
                        this.src = bpadvalue + '_active.png';
                    } else {
                        this.setAttribute(bpadattribute, bpadvalue + '_active');
                    }
                }
            }
        } else {
            // if not, set the default value
            if (this.getAttribute(bpadattribute) != bpadvalue && this.getAttribute(bpadattribute) != bpadvalue + '.png') {
                if (bpadattribute == 'class') {
                    this.className = bpadvalue;
                } else {
                    if (bpadattribute == 'src') {
                        this.src = bpadvalue + '.png';
                    } else {
                        this.setAttribute(bpadattribute, bpadvalue);
                    }
                }
            }
        }
    });
}

/**
 * Parse a command, the command consists of several parts
 */
function parseCommand(thiscommand) {
    // the three main command parts are separated by comma's: item, item address, command
    // the fourth (command number) is added later by doCommand, it's a session specific part
    // of the command
    var commandparts = thiscommand.split(',');
    this.item = commandparts[0];
    this.itemaddress = commandparts[1];
    this.command = commandparts[2];
    this.itemaddressparts = this.itemaddress.split('/');
    var commandcommandparts = this.command.split('.');
    this.commandgroup = commandcommandparts[0];
    this.commandmember = commandcommandparts[1];
}

/**
 * check whether the admin container exists, otherwise use the modal dialog,
 * otherwise use the site root
 * 
 * @param string admincontainer
 * @returns string
 */
function checkAdminContainer(admincontainer) {
    if ($('#' + admincontainer).length) {
        return admincontainer;
    } else {
        if ($('#modalmessage').length && $('#modalcontainer').length) {
            $('#modalcontainer').modal('show');
            return ('modalmessage');
        } else {
            return 'bpad_content_root';
        }
    }
}

/**
 * empty or hide the admin container
 * 
 * @param string admincontainer
 */
function hideAdminContainer(admincontainer) {
    if ($('#' + admincontainer).length) {
        $('#' + admincontainer).html('');
    } else {
        if ($('#modalmessage').length && $('#modalcontainer').length) {
            $('#modalcontainer').modal('hide');
            $('#modalmessage').html(processing);
        }
    }
}

/**
 * Check a command, and do some preliminary work to be able to execute the command,
 * for example find the container to put something in, read the value of a user input,
 * or check what is already loaded
 * 
 * @param string thiscommand the command
 * @param string thisvalue the value (optional)
 */
function checkCommand(thiscommand, thisvalue) {
    // separate a command sequence
    this.successcommand = '';
    var sep = thiscommand.indexOf('|');
    if (sep > 0) {
        this.successcommand = thiscommand.slice(sep + 1);
        thiscommand = thiscommand.slice(0, sep);
    }
    // parse the command
    this.parsedcommand = new parseCommand(thiscommand);
    this.validcommand = true;
    this.value = '';
    this.container = 'bpad_content_root';
    this.newitemaddress = '';
    this.newcommand = thiscommand;
    this.replace = false;
    // if it's a content.get command, check what content to get
    if (this.parsedcommand.commandgroup == 'content' && this.parsedcommand.commandmember == 'get') {
        var length = this.parsedcommand.itemaddressparts.length;
        var part;
        var partparts;
        this.value = settings.SITE_ROOT_OBJECT;
        for (var i = 0; i < length; i++) {
            part = this.parsedcommand.itemaddressparts[i];
            partparts = part.split(".");
            // if the requested container object exists
            if ($('#P' + partparts[0]).length) {
                // the starting point can be the child object
                this.value = partparts[1];
                // the container can be the container
                this.container = 'P' + partparts[0];
                // maybe this container has the wrong content
                this.newitemaddress = part;
            } else {
                // there is no container yet, so add this content to the container to be loaded
                if (this.newitemaddress.length) {
                    this.newitemaddress = this.newitemaddress + '/' + part;
                } else {
                    this.newitemaddress = part;
                }
            }
        }
        if (this.newitemaddress.length) {
            // rebuild the command
            this.newcommand = this.parsedcommand.item + ',' + this.newitemaddress + ',' + this.parsedcommand.command;
        } else {
            // the content is already loaded, do nothing
            this.validcommand = false;
        }
    }
    // if it's a content.load command, check what to load
    if (this.parsedcommand.commandgroup == 'content' && this.parsedcommand.commandmember == 'load') {
        var partparts = this.parsedcommand.itemaddress.split('.');
        // the starting point can be the child object
        this.value = partparts[1];
        // the container can be the container
        this.container = 'I' + partparts[0];
        // replace the container
        this.replace = true;
    }
    // if it's a content.instance command, check where to load the instance content
    if (this.parsedcommand.commandgroup == 'content' && this.parsedcommand.commandmember == 'instance') {
        var partparts = this.parsedcommand.itemaddress.split('.');
        // the container can be the container
        this.container = 'P' + partparts[0];
        this.value = thisvalue;
    }
    // if it's a admin.edit command, check where to load the edit content
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'edit') {
        var partparts = this.parsedcommand.itemaddress.split('.');
        // the container can be the container
        this.container = checkAdminContainer('EP' + partparts[0]);
        this.value = partparts[1];
    }
    // if it's a admin.add command, check where to load the add content
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'add') {
        var partparts = this.parsedcommand.itemaddress.split('.');
        // the container can be the container
        this.container = checkAdminContainer('AP' + partparts[0]);
        this.value = partparts[1];
    }
    // if it's a admin.move command, check where to load the add content
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'move') {
        var partparts = this.parsedcommand.itemaddress.split('.');
        // the container can be the container
        this.container = checkAdminContainer('MP' + partparts[0]);
        this.value = partparts[1];
    }
    // if it's a admin.config command, check where to load the config content
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'config') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
    }
    // if it's a admin.config... command, check where to load the config content
    if (this.parsedcommand.commandgroup == 'admin' && (this.parsedcommand.commandmember == 'configlayouts' || this.parsedcommand.commandmember == 'configstructures' || this.parsedcommand.commandmember == 'configstyles' || this.parsedcommand.commandmember == 'configstyleparams' || this.parsedcommand.commandmember == 'configsets' || this.parsedcommand.commandmember == 'configtemplates')) {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configlayout') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_layoutlist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configstructure') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_structurelist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configstyle') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_stylelist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configstyleparam') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_styleparamlist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configset') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_setlist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configtemplate') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_templatelist').val();
    }
    // if it's a admin.edit command, check where to load the edit content
    if (this.parsedcommand.commandgroup == 'change') {
        this.value = thisvalue;
    }
    // if it's a change admin.publishobject or admin.cancelobject command, close the edit panel
    if (this.parsedcommand.commandgroup == 'change' && (this.parsedcommand.commandmember == 'publishobject' || this.parsedcommand.commandmember == 'cancelobject')) {
        hideAdminContainer('EP' + this.parsedcommand.itemaddress);
    }
    // if it's a change admin.cancelobject, close the edit panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'keepobject') {
        hideAdminContainer('EP' + this.parsedcommand.itemaddress);
        this.value = this.parsedcommand.itemaddress;
    }
    // if it's a move cancel command, close the move panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'cancelmove') {
        hideAdminContainer('MP' + this.parsedcommand.itemaddress);
        this.value = this.parsedcommand.itemaddress;
    }
    // if it's an add cancel command, close the add panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'canceladd') {
        hideAdminContainer('AP' + this.parsedcommand.itemaddress);
        this.value = this.parsedcommand.itemaddress;
    }
    // if it's a cancel config command, close the config panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'cancelconfig') {
        hideAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = this.parsedcommand.itemaddress;
    }
    // if it's an add command, close the admin panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'add') {
        var parts = this.parsedcommand.itemaddress.split('.');
        hideAdminContainer('AP' + parts[1]);
        $('#AP' + parts[1]).html('');
    }
    // if it's a move command, set the value and item address, close the move panel
    if (this.parsedcommand.commandgroup == 'change' && this.parsedcommand.commandmember == 'moveobject') {
        var parts = this.parsedcommand.itemaddress.split('.');
        hideAdminContainer('MP' + parts[0]);
        // rebuild the command
        this.newcommand = this.parsedcommand.item + ',' + parts[0] + ',' + this.parsedcommand.command;
        this.value = parts[1];
    }
}

/**
 * Execute a command
 * 
 * @param string the command
 * @param boolean check the command number
 * @param string value (optional)
 */
function doCommand(thiscommand, checkcommandnr, thisvalue) {
    // check the command, some commands need additional actions
    var info = new checkCommand(thiscommand, thisvalue);
    if (info.validcommand) {
        // analyse it and modify it so that the right object is requested
        // the value is the start object
        commandnumber = commandnumber + 1;
        if (checkcommandnr) {
            checkcommandnumber = commandnumber;
        }
        // is this necessary? Idea is to prevent a pass by reference to the global
        var commandnr = commandnumber;
        var params = {
            command: info.newcommand + ',' + sessionidentifier + '.' + lastcommandid + '.' + commandnumber,
            value: info.value
        };
        var param = $.param(params);
        // first check for command chaining. 
        // by default:
        // a change command does nothing on return
        // other commands load something into a container
        if (info.successcommand > '') {
            // chained commands should be executed in sequence, so a modal dialog is shown while processing
            $('#modalcontainer').modal('show');
            // now do something with the chained command
            if (info.parsedcommand.commandgroup == 'change') {
                $.ajax({
                    type: 'POST',
                    url: settings.SETTING_SITE_ROOTFOLDER,
                    data: param,
                    success: docommand(info.successcommand, true, '')
                });
            } else {
                $.ajax({
                    type: 'POST',
                    url: settings.SETTING_SITE_ROOTFOLDER,
                    data: param,
                    success: docommandandresulttohtml(info.successcommand, true, '', info.container, info.replace, checkcommandnr, commandnr)
                });
            }
        } else if (info.parsedcommand.commandgroup == 'change') {
            // if the message modal is visible, hide it, this is a final change and waiting on the result is not necessary
            if ($('#modalmessage').html() == processing) {
                $('#modalcontainer').modal('hide');
            }
            // do the change
            $.ajax({
                type: 'POST',
                url: settings.SETTING_SITE_ROOTFOLDER,
                data: param,
                success: showerror()
            });
        } else
        {
            $.ajax({
                type: 'POST',
                url: settings.SETTING_SITE_ROOTFOLDER,
                data: param,
                success: resulttohtml(info.container, info.replace, checkcommandnr, commandnr)
            });
        }
    }
}

/**
 * Check whether the target is in range of the viewer
 * 
 * @param domelement
 * @returns boolean true if in range
 */
function inRange(target) {
    return $(target).offset().top - $('body').scrollTop() - (2 * $(window).height()) < 0;
}

/**
 * Load an item 
 * 
 * @param domelement target
 */
function lazyLoad(target) {
    // get the command
    var command = $(target).attr('data-bpad-lazy-load');
    // add the command number for this load to the item, to check consistency later on
    $(target).attr('data-bpad-command-number', commandnumber + 1);
    // remove the lazy load signal attribute, so the load doesn't fire a second time
    $(target).removeAttr('data-bpad-lazy-load');
    // execute the load
    doCommand(command, false);
}

/**
 * The event that triggers a lazy load, find the first item that wants to be loaded 
 * and check whether it is in range (closer than twice the viewport height form the top)
 * 
 * @param domelement target
 * @param string command
 */
function lazyEvent() {
    $('[data-bpad-lazy-load]').first().each(function() {
        if (inRange(this)) {
            // load this item now
            lazyLoad(this);
        }
    });
}

/**
 * On the ready, do the boot strapping thing
 */
$(document).ready(function() {
    doBootStrapping();
});
