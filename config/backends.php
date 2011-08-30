<?php
/**
 * This file is where you specify what backends people using your
 * installation of Sam can store settings on. There are a number of
 * properties that you can set for each backend:
 *
 * driver:       The backend storage driver to use for user options. Valid
 *               options are 'spamd_sql' for use with a SpamAssassin SQL
 *               backend, 'spamd_ldap' for use with a SpamAssassin LDAP
 *               backend, 'spamd_ftp' for use with a SpamAssasin FTP backend,
 *               and 'amavisd_sql' for use with an Amavisd-new SQL backend.
 *
 * preferred:    This is the field that is used to choose which server is
 *               used. The value for this field may be a single string or an
 *               array of strings containing the hostnames to use with this
 *               server.
 *
 * hordeauth:    Sam uses the current logged in username. If you want the full
 *               username@realm to be used for storage ownership set this to
 *               'full' otherwise set this to true and just the username will
 *               be used by the driver.
 *
 * params:       An array containing any additional information that the
 *               Sam_Driver class needs.
 */

/* SpamAssassin SQL storage example. */
$backends['spamd_sql'] = array(
    'preferred' => '',
    'hordeauth' => true,
    'driver' => 'spamd_sql',
    'params' => array(
        // The following parameters are only necessary if they differ from
        // the default Horde SQL settings.
        // 'phptype' => 'mysql',
        // 'hostspec' => 'localhost',
        // 'database' => 'horde',
        // 'username' => 'horde',
        // 'password' => 'horde',
        'table' => 'userpref',

        // This parameter configures the name of the user
        // that owns the site-wide global options.
        'global_user' => '@GLOBAL',
    ),
);

/* SpamAssassin LDAP storage example. */
$backends['spamd_ldap'] = array(
    'preferred' => '',
    'hordeauth' => true,
    'driver' => 'spamd_ldap',
    'params' => array(
        'ldapserver' => 'localhost',
        'basedn' => 'ou=users,dc=example,dc=com',
        'attribute' => 'spamassassinConfig',
        'uid' => 'uid',
        'defaults' => array(
            'hit_level' => '5',
            'subject_tag' => '***SPAM***',
            'rewrite_sub' => 1,
            'report_safe' => 1,
            'skip_rbl' => 1,
        ),
    ),
);

/* SpamAssassin FTP storage example. */
$backends['spamd_ftp'] = array(
    'preferred' => '',
    'hordeauth' => true,
    'driver' => 'spamd_ftp',
    'params' => array(
        'hostspec' => 'localhost',
        // Location of system-wide config file. Used for any undefined
        // user prefs.
        'system_prefs' => '/etc/mail/spamassassin/local.cf',
        // Location of per-user config file.
        'user_prefs' => '.spamassassin/user_prefs',
        'port' => 21,
    )
);

/* Amavisd-new SQL storage example. */
$backends['amavisd_sql'] = array(
    'preferred' => '',
    'hordeauth' => true,
    'driver' => 'amavisd_sql',
    'params' => array(
        // The following parameters are only necessary if they differ from
        // the default Horde SQL settings.
        // 'phptype' => 'mysql',
        // 'hostspec' => 'localhost',
        // 'database' => 'horde',
        // 'username' => 'horde',
        // 'password' => 'horde',

        // This parameter maps the SAM-specific table and
        // attribute names to those that Amavisd-new will
        // understand. If a table or option isn't specified
        // here, it will be used as-is.
        'table_map' => array(
            'recipients' => array(
                'name' => 'users',
                'field_map' => array(
                    'id' => 'id',
                    'email' => 'email',
                    'policy_id' => 'policy_id',
                ),
            ),
            'senders' => array(
                'name' => 'mailaddr',
                'field_map' => array(
                    'id' => 'id',
                    'email' => 'email',
                ),
            ),
            'wblists' => array(
                'name' => 'wblist',
                'field_map' => array(
                    'recipient' => 'rid',
                    'sender' => 'sid',
                    'type' => 'wb',
                ),
            ),
            'policies' => array(
                'name' => 'policy',
                'field_map' => array(
                    'id' => 'id',
                    'name' => 'policy_name',
                    'tag_level' => 'spam_tag_level',
                    'hit_level' => 'spam_tag2_level',
                    'kill_level' => 'spam_kill_level',
                    'rewrite_sub' => 'spam_modifies_subj',
                    'spam_quarantine' => 'spam_quarantine_to',
                    'allow_virus' => 'virus_lover',
                    'allow_spam' => 'spam_lover',
                    'allow_banned' => 'banned_files_lover',
                    'allow_header' => 'bad_header_lover',
                    'skip_virus' => 'bypass_virus_checks',
                    'skip_spam' => 'bypass_spam_checks',
                    'skip_banned' => 'bypass_banned_checks',
                    'skip_header' => 'bypass_header_checks',
                    'spam_extension' => 'addr_extension_spam',
                    'virus_extension' => 'addr_extension_virus',
                    'banned_extension' => 'addr_extension_banned',
                ),
            ),
        ),
    ),
);