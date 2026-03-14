<?php

/**
 * Sam base tables.
 *
 * Copyright 2011-2026 Horde LLC (http://www.horde.org/)
 *
 * See the enclosed file LICENSE for license information (GPL). If you
 * did not receive this file, see http://www.horde.org/licenses/gpl.
 *
 * @author   Jan Schneider <jan@horde.org>
 * @category Horde
 * @license  http://www.horde.org/licenses/gpl GPL
 * @package  Sam
 */
class SamBaseTables extends Horde_Db_Migration_Base
{
    /**
     * Upgrade.
     */
    public function up()
    {
        $tableList = $this->tables();

        /* SpamAssassin table. */
        if (!in_array('userpref', $tableList)) {
            $t = $this->createTable('userpref', ['autoincrementKey' => 'prefid']);
            $t->column('username', 'string', ['limit' => 255, 'null' => false]);
            $t->column('preference', 'string', ['limit' => 30, 'null' => false]);
            $t->column('value', 'string', ['limit' => 100, 'null' => false]);
            $t->end();

            $this->addIndex('userpref', ['username']);
        }

        /* Amavisd tables. */
        // local users
        if (!in_array('users', $tableList)) {
            $t = $this->createTable('users', ['autoincrementKey' => 'id']);
            $t->column('policy_id', 'int', ['default' => 1, 'null' => false]);
            $t->column('email', 'string', ['limit' => 255, 'null' => false]);
            $t->end();

            $this->addIndex('users', ['email'], ['unique' => true]);
        }

        // any e-mail address, external or local, used as senders in wblist
        if (!in_array('mailaddr', $tableList)) {
            $t = $this->createTable('mailaddr', ['autoincrementKey' => 'id']);
            $t->column('email', 'string', ['limit' => 255, 'null' => false]);
            $t->end();

            $this->addIndex('mailaddr', ['email'], ['unique' => true]);
        }

        // per-recipient whitelist and/or blacklist,
        // puts sender and recipient in relation wb (white or blacklisted
        // sender)
        if (!in_array('wblist', $tableList)) {
            $t = $this->createTable('wblist', ['autoincrementKey' => false]);
            // recipient: users.id
            $t->column('rid', 'int', ['null' => false]);
            // sender: mailaddr.id
            $t->column('sid', 'int', ['null' => false]);
            // W or Y / B or N
            $t->column('wb', 'string', ['limit' => 1, 'null' => false]);
            $t->primaryKey(['rid', 'sid']);
            $t->end();
        }

        if (!in_array('policy', $tableList)) {
            $t = $this->createTable('policy', ['autoincrementKey' => 'id']);
            // not used by amavisd-new
            $t->column('policy_name', 'string', ['limit' => 255]);

            // Y/N
            $t->column('virus_lover', 'string', ['limit' => 1]);
            // Y/N (optional field)
            $t->column('spam_lover', 'string', ['limit' => 1]);
            // Y/N (optional field)
            $t->column('banned_files_lover', 'string', ['limit' => 1]);
            // Y/N (optional field)
            $t->column('bad_header_lover', 'string', ['limit' => 1]);

            // Y/N
            $t->column('bypass_virus_checks', 'string', ['limit' => 1]);
            // Y/N
            $t->column('bypass_spam_checks', 'string', ['limit' => 1]);
            // Y/N (optional field)
            $t->column('bypass_banned_checks', 'string', ['limit' => 1]);
            // Y/N (optional field)
            $t->column('bypass_header_checks', 'string', ['limit' => 1]);

            // Y/N (optional field)
            $t->column('spam_modifies_subj', 'string', ['limit' => 1]);
            // (optional field)
            $t->column('spam_quarantine_to', 'string', ['limit' => 64, 'default' => null]);

            // higher score inserts spam info headers
            $t->column('spam_tag_level', 'numeric');
            // higher score inserts 'declared spam' info header fields
            $t->column('spam_tag2_level', 'numeric', ['null' => false]);
            // higher score activates evasive actions, e.g. reject/drop,
            // quarantine, ... (subject to final_spam_destiny setting)
            $t->column('spam_kill_level', 'numeric');

            // extension to add to the localpart of an address for detected
            // spam
            $t->column('addr_extension_spam', 'string', ['limit' => 32]);
            // extension to add to the localpart of an address for detected
            // viruses
            $t->column('addr_extension_virus', 'string', ['limit' => 32]);
            // extension to add to the localpart of an address for detected
            // banned files
            $t->column('addr_extension_banned', 'string', ['limit' => 32]);
            $t->end();

            $this->addIndex('policy', ['policy_name'], ['unique' => true]);
        }
    }

    /**
     * Downgrade.
     */
    public function down()
    {
        $this->dropTable('policy');
        $this->dropTable('wblist');
        $this->dropTable('mailaddr');
        $this->dropTable('users');

        $this->dropTable('userpref');
    }
}
