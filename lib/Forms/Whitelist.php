<?php
/**
 * Form Class for Whitelist Management.
 *
 * Copyright 2003-2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * @author Max Kalika <max@horde.org>
 * @package Sam
 */
class WhitelistForm extends Horde_Form {

    function WhitelistForm(&$vars)
    {
        global $sam_driver;

        parent::Horde_Form($vars, _("Whitelist Manager"));

        $this->setButtons(_("Save"), true);

        $attributes = array(
            'whitelist_from' => _("Whitelist From"),
            'whitelist_to' => _("Whitelist To"),
        );

        foreach ($attributes as $key => $attribute) {
            if ($sam_driver->hasCapability($key)) {
                $var = &$this->addVariable($attribute, $key, 'longtext',
                                           false, false, null,
                                           array('5', '40'));
                $var->setHelp($key);

                if (!$vars->exists($key)) {
                    $vars->set($key, $sam_driver->getListOption($key));
                }
            }
        }

        if ($sam_driver->hasCapability('global_defaults') && Horde_Auth::isAdmin()) {
            $this->addVariable('', '', 'spacer', false);
            $var = &$this->addVariable(_("Make Settings Global"),
                                       'global_defaults', 'boolean', false);
            $var->setHelp('global_defaults');
        }
    }

}
