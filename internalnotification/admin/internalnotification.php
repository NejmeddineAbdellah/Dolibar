<?php

/* Copyright (C) 2015 Gilles Lengy / Artaban Communication <gilles.lengy@artaban.fr>
 * Copyright (C) 2015 Gilles Dumont / Artaban Communication <gilles@artaban.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.

  /**
 * 	\file      htdocs/internalnotifications/admin/setuppage.php
 * 	\ingroup    internalnotification
 * 	\brief      Internal notification set up page
 */
// **** INIT ****
$res = @include("../../main.inc.php"); // From htdocs directory
if (!$res) {
    $res = @include("../../../main.inc.php"); // From "custom" directory
}


require_once(DOL_DOCUMENT_ROOT . '/core/lib/admin.lib.php');
require_once(DOL_DOCUMENT_ROOT . '/core/class/html.formadmin.class.php');
require_once(DOL_DOCUMENT_ROOT . '/core/class/doleditor.class.php');
require_once(DOL_DOCUMENT_ROOT . '/core/lib/functions.lib.php');
dol_include_once('/internalnotification/class/internalnotification.class.php');
dol_include_once('/internalnotification/lib/internalnotification.lib.php');

$langs->load("admin");
$langs->load("internalnotification@internalnotification");
$langs->load("companies");
$langs->load("mails");
$langs->load("agenda");

$tab = GETPOST('tab', 'alpha');
if (empty($tab)) {
    if (empty($conf->societe->enabled) && $conf->agenda->enabled) {
        $tab = 'events';
    } else {
        $tab = 'thirdparties';
    }
}

$action = GETPOST('action', 'alpha');
$delete_notification = GETPOST('delete_notification', 'alpha');
$confirm = GETPOST('confirm', 'alpha');

$trigger_action = GETPOST('trigger_action', 'alpha');
$receiver_email = str_replace(" ", "", GETPOST('receiver_email', 'alpha'));
$subject = GETPOST('subject', 'alpha');
$body = GETPOST('body', 'alpha');
$checkbox_1 = GETPOST('checkbox_1');


if (!$user->admin)
    accessforbidden();

/*
 *      Checkings
 */
$errors = 0;
if ($action == 'modify_template' && empty($delete_notification) && ( empty($receiver_email) || empty($subject) || empty($body))) {
    setEventMessages('AllFieldsAreMandatory', array(), 'errors');
    $errors++;
}
if ($action == 'modify_template' && empty($delete_notification) && !empty($receiver_email)) {

    $arr_receiver_email = explode(",", $receiver_email);
    $errors_mail = 0;
    foreach ($arr_receiver_email as &$mail) {
        if (!isValidEmail($mail)) {
            $errors_mail++;
        }
    }
    if ($errors_mail > 0) {
        setEventMessages('InvalidEmail', array(), 'errors');
        $errors++;
    }
}

/*
 *      Actions
 */
if ($action === 'modify_template' && empty($delete_notification) && $errors === 0) {
    $object_notification = new Internalnotification($db);
    $object_notification->fetch($trigger_action, '', true);
    $object_notification->action = $trigger_action;
    $object_notification->receiver_email = $receiver_email;
    $object_notification->subject = $subject;
    $object_notification->body = $body;
    $object_notification->checkbox_1 = $checkbox_1;
    if ($object_notification->id > 0) {
        $result_object_notification_setup = $object_notification->update($user);
    } else {
        $result_object_notification_setup = $object_notification->create($user);
    }
    if ($result_object_notification_setup > 0) {
        setEventMessages('SetupNotificationOK', array(), 'mesgs');
    } else {
        setEventMessages('SetupNotificationKO', array(), 'errors');
    }
}

if ($action == 'delete_notification_confirmed') {

    $object_notification = new Internalnotification($db);
    $object_notification->fetch($trigger_action, '', true);
    $result_template_deletion = $object_notification->delete($user);
    if ($result_template_deletion > 0) {
        // Delete OK
        setEventMessage('NotificationDeletedOK', 'mesgs');
    } else {
        // Delete KO
        setEventMessage('NotificationDeletedKO', 'errors');
        print $mesg = $object->error;
    }
}

/*
 * 	View
 */

// Necessary headers
$html = new Form($db);

llxHeader('', $langs->trans("InternalNotificationSetupTitle"));

if (!empty($delete_notification)) {
    print $html->formconfirm($_SERVER['PHP_SELF'] . "?trigger_action=" . urlencode($trigger_action) . "&tab=" . $tab, $langs->trans("DeleteNotification"), $langs->trans("DeleteNotificationConfirmation"), "delete_notification_confirmed", '', '', 1);

    $trigger_action = ''; // To avoid to interact with the forms generation
}

$linkback = '<a href="' . DOL_URL_ROOT . '/admin/modules.php">' . $langs->trans("BackToModuleList") . '</a>';

// print title
print_fiche_titre($langs->trans("InternalNotificationSetupTitle"), $linkback, 'setup');

// print long description (to help first time users)
dol_fiche_head();
print $langs->trans("InternalNotificationSetupDescription");
dol_fiche_end();

if (empty($conf->societe->enabled)) {
    dol_fiche_head();
    print $langs->trans("InternalNotificationSetupThirdPartiesInactives");
    dol_fiche_end();
}

if (empty($conf->agenda->enabled)) {
    dol_fiche_head();
    print $langs->trans("InternalNotificationSetupAgendaInactives");
    dol_fiche_end();
}




$head = internalnotification_prepare_head();


// TABS
dol_fiche_head($head, $tab, $langs->trans("InternalNotifications"), 0, '');

$tab_elements = elements_for_internal_notification($tab);

foreach ($tab_elements as $key => $value) {
    if ($key === 'ErrorNoSetOfForms') {
        print $value;
    } else {
        if ($value === $trigger_action) {
            form_for_internal_notification($value, $errors, $receiver_email, $subject, $body);
        } else {
            form_for_internal_notification($value);
        }
    }
}

dol_fiche_end();
// END TABS



llxFooter();
$db->close();

