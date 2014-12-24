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
var initialhash = 'default'; // initial hash value
var commandqueue = ''; // create an empty command queue
var checkcommandnrqueue = ''; // create an empty command queue
var commandvaluequeue = ''; // create an empty command queue
var queuenumber = 0; // a number for the queue
var analyticsurl = ''; // store the last analyticsurl

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
        if ($('#modalcontainer').is(':visible')) {
            // show the error message in the edit container
            // $('#modalcontainer').modal('hide');
            $('#adminerrormessage').html(result);
            $('#modalmessage').addClass('alert-danger');
            $('#adminerrorcontainer').removeClass('hidden');
        } else {
            // show the error message in the global error modal
            $('#errormessage').html(result);
            $('#errorcontainer').show();
        }
        return true;
    } else {
        if ($('#adminerrorcontainer').is(':visible')) {
            $('#adminerrormessage').html('');
            $('#modalmessage').removeClass('alert-danger');
            $('#adminerrorcontainer').addClass('hidden');
        }
    }
    return false;
}

function resultToHTML(container, replace, checkcommandnr, commandnr, result) {
    // show the result
    if (replace) {
        // check whether the container to replace is coupled to this content fetch (it may have changed client-side during the roundtrip)
        if ($('#' + container).attr('data-bpad-command-number') + '-' + commandnr) {
            $('#' + container).html(result);
            var origdiv = $('#' + container)[0];
            var replacer = origdiv.firstChild;
            // now add the events to the new html
            addEvents(container);
            // now remove the placeholder
            origdiv.parentNode.replaceChild(replacer, origdiv);
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
            // correct the position of the page after loading new content, only correct
            // if the page moved more than 5 pixels
            var margin = 200;
            if (window.innerHeight < 600) {
                margin = 120;
            }
            $('body').scrollTop($('#' + container).offset().top - margin);
        }
    }
}

/**
 * Fetch the new hash, based upon the content
 * 
 * @returns string
 */
function newHash() {
    var newhash = '';
    $('[data-bpad-url-name!=""][data-bpad-url-name]').each(function() {
        newhash = newhash + '/';
        newhash = newhash + this.getAttribute('data-bpad-url-name');
    });
    if (newhash > '') {
        newhash = newhash + '.html';
    }
    return newhash;
}

/**
 * refresh the hash after loading content
 */
function refreshHash() {
    var newhash = newHash();
    if (window.location.hash != '#' + newhash) {
        refreshinghash = true;
        window.location.hash = newhash;
    }
    // check whether analytics should be updated
    checkAnalytics();
}

function fetchContent() {
    var hash = initialhash;
    if (window.location.hash.length > 6) {
        hash = window.location.hash.substring(2, window.location.hash.length - 5);
    }
    doCommand('object,' + hash + ',content.fetch', false, '');
}

/**
 * Initialize the page after the first load
 */
function doBootStrapping() {
    // optionally, load Google Analytics
    if (settings.hasOwnProperty('GOOGLE_ANALYTICSCODE')) {
        if (settings.GOOGLE_ANALYTICSCODE.length > 6) {
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
            m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', settings.GOOGLE_ANALYTICSCODE, 'auto');
        }
    }
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
    var newhash = newHash();
    // if the hash isn't empty and isn't the current page, load the content requested by the hash
    if (window.location.hash != '' && window.location.hash != '#' + newhash) {
        // fetch new content 
        fetchContent();
    }
    refreshHash();
    if (window.location.hash.length > 6) {
        initialhash = window.location.hash.substring(2, window.location.hash.length - 5);
    }
    // monitor the hash
    $(window).on("hashchange", function() {
        if (refreshinghash) {
            // hash is changed from the code, ignore
            refreshinghash = false;
        } else {
            // get new content
            fetchContent();
        }
    });
    checkAnalytics();
}

/** 
 * checks whether something should be sent to Google Analytics 
 */
function checkAnalytics() {
    if (settings.hasOwnProperty('GOOGLE_ANALYTICSCODE')) {
        if (settings.GOOGLE_ANALYTICSCODE.length > 6) {            
            var thisurl = window.location.hash;
            if (thisurl.substring(0, 1) == '#') {
                thisurl = thisurl.substring(1);
            }
            if (thisurl != analyticsurl) {
                ga('send', 'pageview', thisurl);
                analyticsurl = thisurl;
            } else {
            }
        }
    }
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
            case 'showhide-add':
                $(this).on("click", function() {
                    $('[data-bpad-hide-add]').toggle();
                    this.blur();
                });
                break;
            case 'showhide-lss':
                $(this).on("click", function() {
                    $('[data-bpad-hide-lss]').toggle();
                    this.blur();
                });
                break;
            case 'open-url':
                if ($(this).attr('data-bpad-open-url').length) {
                    $(this).on("click", function() {
                        var value = $(this).attr('data-bpad-open-url');
                        // check for relative or absolute link, open link
                        if (value.substring(0,1) == '/') {
                            doCommand('object,' + value.substring(1, value.length - 5) + ',content.fetch', false, '');
                            return false;
                        } else {
                            window.location = url; // redirect
                            return false;
                        }
                    });
                }
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
    // close the bootstrap menu after a command
    $('.nav li a').on('click',function(){
        $('.navbar-collapse.in').collapse('hide');
    })
    // hide add buttons in the edit interface
    $(selector + '[data-bpad-hide-add]').each(function() {
        // get the value
        var value = $(this).attr('data-bpad-hide-add');
        if (value == 'hide') {
            $(this).hide();
        }
    });
    // hide lss buttons in the edit interface
    $(selector + '[data-bpad-hide-lss]').each(function() {
        // get the value
        var value = $(this).attr('data-bpad-hide-lss');
        if (value == 'hide') {
            $(this).hide();
        }
    });
    // add on click events to nodes that request it
    $(selector + '[data-bpad-onkeyup]').each(function() {
        // get the command
        var command = $(this).attr('data-bpad-onkeyup');
        // attach the event and pass the command info
        $(this).on('keyup', {
            cmd: command
        }, function(event) {
            if ($(this).attr('data-bpad-onscrollkey') == 'blur') {
                // blur when the user tries to scroll
                var code = (event.keyCode ? event.keyCode : event.which);
                if (code == 33 || code == 34 || code == 38 || code == 40) {
                    $(this).blur();
                } else {
                    // if the key stroke has changed the value
                    if (this.value != $(this).attr('data-bpad-last-value')) {
                        // update the last value
                        $(this).attr('data-bpad-last-value', this.value);
                        // execute the command
                        queueCommand(event.data.cmd, true, this.value);
                    }
                }
            } else {
                // if the key stroke has changed the value
                if (this.value != $(this).attr('data-bpad-last-value')) {
                    // update the last value
                    $(this).attr('data-bpad-last-value', this.value);
                    // execute the command
                    queueCommand(event.data.cmd, true, this.value);
                }
            }
        }
        );
        // remove the attribute, so the event isn't attached again
        $(this).removeAttr('data-bpad-onkeyup');
        $(this).attr('data-bpad-last-value', this.value);
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
    // clear another div when this one is filled, or clear this one when the
    // other gets filled
    $('[data-bpad-clear-content]').each(function() {
        // get the linked item
        var clearcontent = $(this).attr('data-bpad-clear-content');
        var divid = $(this).attr('id');
        // if the content has been cleared
        if ($('#' + divid + '[data-bpad-content-cleared]').length) {
            // check if the cleared div has been filled
            if (!$('#' + clearcontent).is(':empty')) {
                // clear this one
                $(this).html('');
                $(this).removeAttr('data-bpad-content-cleared');
            }
        } else {
            // otherwise clear the other div and remember that it has been cleared
            if (!$('#' + clearcontent).is(':empty') && !$(this).is(':empty')) {
                $('#' + clearcontent).html('');
                $(this).attr('data-bpad-content-cleared', clearcontent);
            }
        }
    });
    // move an item to a new location, clear the location first
    $(selector + '[data-bpad-clear-move]').each(function() {
        var clearmove = $(this).attr('data-bpad-clear-move');
        if ($('#' + clearmove).length) {
            $('#' + clearmove).html('');
            $('#' + clearmove).append(this);
            $(this).removeAttr('data-bpad-clear-move');
        }
    });
    // move an item to a new location
    $(selector + '[data-bpad-move]').each(function() {
        var moveto = $(this).attr('data-bpad-move');
        if ($('#' + moveto).length) {
            $('#' + moveto).append(this);
            $(this).removeAttr('data-bpad-move');
        }
    });
    // move an item and replace the target
    $(selector + '[data-bpad-replace]').each(function() {
        var replace = $(this).attr('data-bpad-replace');
        if ($('#' + replace).length) {
            $('#' + replace).replaceWith(this);
            $(this).removeAttr('data-bpad-replace');
        }
    });
    // after move/replace, add an index number to numbered items (e.g. used in carousel)
    $(selector + '[data-bpad-index]').each(function() {
        var attrib = $(this).attr('data-bpad-index');
        var value = $(this).parent().children().index(this);
        $(this).attr(attrib, value);
    });
    // after indexing, autostart the carousels (use this, instead of the autostart, because of ajax loading)
    $(selector + '[data-bpad-carousel-autostart]').each(function() {
        var options = $(this).attr('data-bpad-carousel-autostart');
        if (options.length > 0) {
            options = jQuery.parseJSON(options);
            $(this).carousel(options);
        } else {
            $(this).carousel();
        }
    });
    // show markup for menu-items that are active, but only when there are no deeplinks
    var countdeeplinks = countDeepLinks();
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
        if (bpadactivate == 'object' && $('[data-bpad-objectid="' + bpaditemid + '"]').length && countdeeplinks == 0) {
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
    // start a lazy load sequence
    lazyEvent();
}

/**
 * count the number of deep links active
 */
function countDeepLinks(selector) {
    selector = selector || "";
    // count the number of deeplinks active
    var deeplinks = 0;
    $(selector + ' [data-bpad-deep-link]').each(function(i) {
        deeplinks++;
    });
    return deeplinks;
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
    if (this.parsedcommand.commandgroup == 'content' && (this.parsedcommand.commandmember == 'get' || this.parsedcommand.commandmember == 'refresh')) {
        var length = this.parsedcommand.itemaddressparts.length;
        var part;
        var partparts;
        var deeplinks;
        this.value = settings.SITE_ROOT_OBJECT;
        for (var i = 0; i < length; i++) {
            part = this.parsedcommand.itemaddressparts[i];
            partparts = part.split(".");
            // if the requested container object exists
            if ($('#P' + partparts[0]).length) {
                deeplinks = countDeepLinks('#P' + partparts[0]);
                if (deeplinks == 0 || i == 0) {
                    // the starting point can be the child object
                    this.value = partparts[1];
                    // the container can be the container
                    this.container = 'P' + partparts[0];
                    // check whether the container contains the right content, if so, and the content
                    // isn't deep linked (incomplete) and the request is not a refresh
                    if ($('#P' + partparts[2]).length && deeplinks == 0 && !this.parsedcommand.commandmember == 'refresh') {
                        // do nothing here, the content is already loaded
                    } else {
                        this.newitemaddress = part;
                    }
                } else {
                    // the starting point can be the child object
                    this.value = partparts[1];
                    // the container can be the container
                    this.container = 'P' + partparts[0];
                    // load this item
                    this.newitemaddress = part;
                }
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
    if (this.parsedcommand.commandgroup == 'admin' && (this.parsedcommand.commandmember == 'configlayouts' || this.parsedcommand.commandmember == 'configstructures' || this.parsedcommand.commandmember == 'configstyles' || this.parsedcommand.commandmember == 'configstyleparams' || this.parsedcommand.commandmember == 'configsets' || this.parsedcommand.commandmember == 'configusers' ||  this.parsedcommand.commandmember == 'configusergroups' || this.parsedcommand.commandmember == 'configroles' || this.parsedcommand.commandmember == 'configsettings' || this.parsedcommand.commandmember == 'configincludefiles' || this.parsedcommand.commandmember == 'configsnippets' || this.parsedcommand.commandmember == 'configtemplates')) {
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
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configuser') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_userlist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configusergroup') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_usergrouplist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configrole') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_rolelist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configsetting') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_settinglist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configincludefile') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_includefilelist').val();
    }
    if (this.parsedcommand.commandgroup == 'admin' && this.parsedcommand.commandmember == 'configsnippet') {
        this.container = checkAdminContainer('CP' + this.parsedcommand.itemaddress);
        this.value = $('#' + 'CP' + this.parsedcommand.itemaddress + '_snippetlist').val();
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
    // if it's a change admin.keepobject, close the edit panel
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

function queueCommand(thiscommand, checkcommandnr, thisvalue) {
    // check whether the command queue is empty or not
    if (commandqueue == '') {
        commandqueue = thiscommand;
    } else {
        // check whether this command is already in the queue
        if (commandqueue != thiscommand) {
            doCommand(commandqueue, checkcommandnrqueue, commandvaluequeue);
            commandqueue = thiscommand;
        }
    }
    checkcommandnrqueue = checkcommandnr;
    commandvaluequeue = thisvalue;
    queuenumber = queuenumber + 1;
    var curnum = queuenumber;
    setTimeout(function() {
        checkCommandQueue(curnum)
    }, 500);
}

function checkCommandQueue(number) {
    if (number == queuenumber) {
        doCommand(commandqueue, checkcommandnrqueue, commandvaluequeue);
        commandqueue = '';
        checkcommandnrqueue = '';
        commandvaluequeue = '';
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
    var myscrolltop = $("body").scrollTop();
    if ($("html").scrollTop() > myscrolltop) {
        myscrolltop = $("html").scrollTop();
    }
    return $(target).offset().top - myscrolltop - (2 * $(window).height()) < 0;
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
