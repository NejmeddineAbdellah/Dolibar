<?php

/* Copyright (C) 2005-2014 Laurent Destailleur	<eldy@users.sourceforge.net>
 * Copyright (C) 2005-2014 Regis Houssin		<regis.houssin@capnetworks.com>
 * Copyright (C) 2014      Marcos García		<marcosgdf@gmail.com>
 * Copyright (C) 2015 Gilles Lengy / Artaban Communication <gilles.lengy@artaban.fr>
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
 */

/**
 *  \file       \htdocs\internalnotification\core\triggers\interface_99_modInternalNotification_Sendnotification.class.php
 *  \ingroup    internalnotification
 *  \brief      Actions on workflow regardinf the internal notifications
 */
require_once DOL_DOCUMENT_ROOT . '/core/triggers/dolibarrtriggers.class.php';
dol_include_once('internalnotification/lib/internalnotification.lib.php');

/**
 *  Class of triggers for demo module
 */
class InterfaceSendnotification extends DolibarrTriggers {

    public $family = 'demo';
    public $picto = 'technic';
    public $description = "Internal Notification Triggers that send email";
    public $version = self::VERSION_DOLIBARR;

    /**
     * Function called when a Dolibarrr business event is done.
     * All functions "runTrigger" are triggered if file is inside directory htdocs/core/triggers or htdocs/module/code/triggers (and declared)
     *
     * @param string		$action		Event action code
     * @param Object		$object     Object
     * @param User		    $user       Object user
     * @param Translate 	$langs      Object langs
     * @param conf		    $conf       Object conf
     * @return int         				<0 if KO, 0 if no triggered ran, >0 if OK
     */
    public function runTrigger($action, $object, User $user, Translate $langs, Conf $conf) {
        // Put here code you want to execute when a Dolibarr business events occurs.
        // Data and type of action are stored into $object and $action

        switch ($action) {
            // Compagny
            case 'COMPANY_CREATE':
            case 'COMPANY_MODIFY':
            case 'COMPANY_DELETE':
            // Contact
            case 'CONTACT_CREATE':
            case 'CONTACT_MODIFY':
            case 'CONTACT_DELETE':
            case 'CONTACT_ENABLEDISABLE':
            // Events
            case 'ACTION_CREATE':
            case 'ACTION_MODIFY':
            case 'ACTION_DELETE':
                if ($object->type_code !== 'AC_OTH_AUTO') {// To avoid to send notification on automatic 'ACTION_xxxx'
                    dol_syslog("Trigger '" . $this->name . "' for action '$action' launched by " . __FILE__ . ". id=" . $object->id . ' object element : ' . $object->element);
                    sendNotification($object, $action, $user);
                }
                break;
            default:
                dol_syslog("Trigger '" . $this->name . "' for action DEFAULT_ACTION ( action = " . $action . " ) launched by " . __FILE__ . ". id=" . $object->id . ' object element : ' . $object->element);
        }





        return 0;
    }

}
