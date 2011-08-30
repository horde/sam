<?php

/** Backend-specific 'false' value. */
define('_SAM_OPTION_OFF', '0');

/** Backend-specific 'true' value. */
define('_SAM_OPTION_ON',  '1');

/**
 * Base class for all SpamAssassin drivers.
 *
 * Copyright 2003-2011 The Horde Project (http://www.horde.org/)
 *
 * See the enclosed file COPYING for license information (GPL). If you
 * did not receive this file, see http://www.fsf.org/copyleft/gpl.html.
 *
 * @author  Chris Bowlby <cbowlby@tenthpowertech.com>
 * @author  Max Kalika <max@horde.org>
 * @author  Jan Schneider <jan@horde.org>
 * @package Sam
 */
class Sam_Driver_Spamd_Base extends Sam_Driver
{
    /**
     * List of the capabilities supported by this driver.
     *
     * @var array
     */
    protected $_capabilities = array('hit_level',
                               'score_level',
                               'report_safe',
                               'rewrite_sub',
                               'subject_tag',
                               'skip_rbl',
                               'whitelist_to',
                               'whitelist_from',
                               'blacklist_to',
                               'blacklist_from',
                               'rewrite_header_sub',
                               'rewrite_header_to',
                               'rewrite_header_from');

    /**
     * List of Sam to SpamAssassin options mappings.
     *
     * @var array
     */
    protected $_option_map = array('hit_level' => 'required_hits',
                             'rewrite_sub' => 'rewrite_subject',
                             'skip_rbl' => 'skip_rbl_checks',
                             'score_level' => 'required_score');

    /**
     * Converts a Sam attribute to an option that SpamAssassin will use.
     *
     * @access protected
     *
     * @param string $attribute  The Sam attribute to convert.
     *
     * @return string  The converted SpamAssassin option or the original
     *                 attribute if no match is found.
     */
    protected function _mapAttributeToOption($attribute)
    {
        return isset($this->_option_map[$attribute])
               ? $this->_option_map[$attribute] : $attribute;
    }

    /**
     * Converts a SpamAssassin option to a Sam attribute.
     *
     * @access protected
     *
     * @param string $option  The SpamAssassin option to convert.
     *
     * @return string  The converted Sam attribute or the original option if
     *                 no match is found.
     */
    protected function _mapOptionToAttribute($option)
    {
        $attribute_map = array_flip($this->_option_map);
        return isset($attribute_map[$option])
               ? $attribute_map[$option] : $option;
    }
}