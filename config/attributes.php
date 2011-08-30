<?php
/**
 * SAM Attributes File
 *
 * This file contains the attributes that SAM understands, and their types. It
 * may be safely edited by hand. Use attributes.php.dist as a reference. Only
 * a backend-supported subset of these values will be presented to the user so
 * it is safe to add more attributes.
 *
 * To disable an attribute (and prevent it from showing up to the user) simply
 * comment it out or remove it.
 *
 * General configuration is in 'conf.php'.
 * Backend storage is defined in 'backends.php'.
 *
 * The syntax of this array is as follows:
 *      label    - the text that the user will see attached to this field
 *      type     - one of the following:
 *                      - spacer            - header
 *                      - description       - html
 *                      - number            - int
 *                      - intlist           - text
 *                      - longtext          - countedtext
 *                      - address           - file
 *                      - boolean           - link
 *                      - email             - emailconfirm
 *                      - password          - passwordconfirm
 *                      - enum              - multienum
 *                      - radio             - set
 *                      - date              - time
 *                      - monthyear         - monthdayyear
 *                      - colorpicker       - sorter
 *                      - creditcard        - invalid
 *                      - stringlist
 *      required - boolean, true or false whether this field is mandatory
 *      readonly - boolean, true or false whether this editable
 *      desc     - any help text attached to the field
 *      params   - any other parameters that need to be passed to the field
 *                 such as a list to be used in the enum field, the row and
 *                 column sizing for a longtext field.
 *      default  - a default value for the field
 *
 * SpamAssassin 3+ options only:
 *
 *      basepref - for multiple data preferences like SA3's rewrite_header,
 *                 which takes two data elements, header and rewrite text
 *                 (e.g. rewrite_header => "Subject ***SPAM***"),
 *                 the attribute name is a combination of the base SA
 *                 preference and the subtype (e.g. header) being
 *                 modified, so that the attribute name is unique.
 *                 This field contains the name of the base pref, linking
 *                 all attributes for this pref together.
 *      subtype  - text field with the subtype name (e.g. "Subject"). The
 *                 text in this field will be added directly to the pref
 *                 value in the backend.
 */

$_attributes['tag_level'] = array(
    'label' => _("Tag Level"),
    'type' => 'number',
    'required' => false,
);

/**
 * SpamAssassin 2.x uses the required_hits preference to determine at what 
 * point to tag mail as spam. SAM maps this to the hit_level attribute below.
 *
 * SpamAssassin 3 has changed this pref to required_score.  SAM maps this 
 * to the score_level attribute, under hit_level below.
 *
 * While required_hits is still supported in SA3, it is depreciated, meaning 
 * it will be going away at some point.
 *
 * If you're upgrading from SpamAssassin 2 to 3, then you can continue to use
 * hit_level.  It is suggested you schedule some time to change over to 
 * score_level, which will entail you commenting out the hit_level attrib and
 * uncommenting score_level.  Once done, you'll need to change all occurances
 * of 'required_hits' in your backend's preferences column to 'required_score'
 * so SAM will continue to pick up each user's customized setting.
 *
 * If you're setting up a new install of SA3, just comment out the hit_level 
 * attribute and uncomment score_level to start with.
 */

// For SpamAssassin 2.x
// Comment or remove the score_level attribute if you use this.

//$_attributes['hit_level'] = array(
//    'label' => _("Hit Level"),
//    'type' => 'number',
//    'default' => 5,
//    'required' => true,
//);

// For SpamAssassin 3+
// Comment or remove the hit_level attribute if you use this.

$_attributes['score_level'] = array(
    'label' => _("Required Score"),
    'type' => 'number',
    'default' => 5,
    'required' => true,
);

// Alternatively levels may be defined as a
// list of values for simplifying user interface.
// For example:

//$_attributes['hit_level'] = array(
//    'label' => _("Required Hits"),
//    'type' => 'enum',
//    'default' => 7,
//    'required' => true,
//    'params' => array(array(
//        '' => _("Select Value:"),
//        '2' => _("2: Really Aggressive"),
//        '5' => _("5: Aggressive"),
//        '7' => _("7: Medium"),
//        '10' => _("10: Loose"),
//        '15' => _("15: Really Loose"),
//    )),
//);

$_attributes['kill_level'] = array(
    'label' => _("Kill Level"),
    'type' => 'number',
    'required' => false,
);

/**
 * SpamAssassin 2.x uses two preferences to add a tag to email subjects:
 * subject_tag and rewrite_subject.  Subject_tag contains the text that will
 * be prepended to the email's subject, and rewrite_subject is a switch to
 * tell SA whether or not to touch the Subject at all.
 *
 * SpamAssassin 3 has gotten rid of these preferences in favour of a more
 * general header rewriting facility, which can change more headers than
 * just the Subject.
 *
 * Uncomment the 'subject_tag' and 'rewrite_sub' attributes for SA2 *OR*
 * the 'rewrite_header_*' attributes for SA3, depending on which you use.
 *
 * Running with both enabled will be very confusing for users and running
 * with neither will disable any header/subject rewriting at all.
 */

// For SpamAssassin 2.x
// Comment or remove the rewrite_header_* attributes if you use these.

//$_attributes['subject_tag'] = array(
//    'label' => _("Subject Tag"),
//    'type' => 'text',
//    'required' => false,
//);
//$_attributes['rewrite_sub'] = array(
//    'label' => _("Rewrite Subject"),
//    'type' => 'boolean',
//    'required' => false,
//);


// For SpamAssassin 3+
// Comment or remove the subject_tag and rewrite_sub attributes if you use
// these.

$_attributes['rewrite_header_sub'] = array(
    'label' => _("Rewrite Email Subject"),
    'type' => 'text',
    'required' => false,
    'basepref' => 'rewrite_header',
    'subtype' => 'Subject'
);

$_attributes['rewrite_header_to'] = array(
    'label' => _("Rewrite To: Address"),
    'type' => 'text',
    'required' => false,
    'basepref' => 'rewrite_header',
    'subtype' => 'To'
);

$_attributes['rewrite_header_from'] = array(
    'label' => _("Rewrite From: Address"),
    'type' => 'text',
    'required' => false,
    'basepref' => 'rewrite_header',
    'subtype' => 'From'
);

// As an alternative UI, all boolean types may be configured as radio buttons.
// For example:

//$_attributes['rewrite_sub'] = array(
//    'label' => _("Rewrite Subject"),
//    'type' => 'radio',
//    'required' => false,
//    'params' => array(array('Y' => 'Yes',
//                            'N' => 'No')));

$_attributes['spam_quarantine'] = array(
    'label' => _("Spam Quarantine Address"),
    'type' => 'text',
    'required' => false,
);
$_attributes['report_safe'] = array(
    'label' => _("Modify messages tagged as spam:"),
    'type' => 'enum',
    'required' => false,
    'params' => array(array(0 => _("0: Only add X-Spam-* headers"),
                            1 => _("1: Attach the original message to the report"),
                            2 => _("2: Attach the original message as plain text to the report")))
);
$_attributes['skip_rbl'] = array(
    'label' => _("Skip RBL Checks"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['skip_virus'] = array(
    'label' => _("Skip Virus Checks"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['skip_spam'] = array(
    'label' => _("Skip Spam Checks"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['skip_banned'] = array(
    'label' => _("Skip Banned File Checks"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['skip_header'] = array(
    'label' => _("Skip Bad Header Checks"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['allow_virus'] = array(
    'label' => _("Receive Viruses"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['allow_spam'] = array(
    'label' => _("Receive Spam"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['allow_banned'] = array(
    'label' => _("Receive Banned Files"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['allow_header'] = array(
    'label' => _("Receive Bad Headers"),
    'type' => 'boolean',
    'required' => false,
);
$_attributes['spam_extension'] = array(
    'label' => _("Spam Folder"),
    'type' => 'text',
    'required' => false,
);
$_attributes['virus_extension'] = array(
    'label' => _("Virus Folder"),
    'type' => 'text',
    'required' => false,
);
$_attributes['banned_extension'] = array(
    'label' => _("Banned Files Folder"),
    'type' => 'text',
    'required' => false,
);

// The above three settings may alternatively be configured
// as either a drop-down list of currently existing folders
// or a hard-coded string chosen by a yes/no radio button.
// For both of these examples to work, your server must
// support address extension (such as plus addressing).
//
// Existing folders example:

//if ($GLOBALS['registry']->hasMethod('mail/folderlist')) {
//    try {
//        $mailboxes = $GLOBALS['registry']->call('mail/folderlist');
//        if ($mailboxes) {
//            $_attributes['spam_extension'] = array(
//                'label' => _("File spam Into Mailbox:"),
//                'type' => 'enum',
//                'required' => false,
//                'params' => array(array())
//            );
//
//            foreach ($mailboxes as $mbox => $info) {
//                if ($mbox != 'INBOX') {
//                    $_attributes['spam_quarantine']['params'][0][$mbox] = $info['label'];
//                }
//            }
//        }
//    } catch (Horde_Exception $e) {
//    }
//}

// Admin-restricted folder example:

//$_attributes['spam_extension'] = array(
//    'label' => _("File Into <strong>spamfilter</strong> Mailbox?"),
//    'type' => 'radio',
//    'required' => false,
//    'params' => array(array(
//        'spamfilter' => 'Yes',
//        '' => 'No'
//    ))
//);
