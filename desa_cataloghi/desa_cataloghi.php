<?php
/**
 * 2007-2024 Team Desantis
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    Team Desantis <info@teamdesantis.com>
 *  @copyright 2007-2024 Team Desantis
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class DesaCataloghi extends Module
{
    public function __construct()
    {
        $this->name = 'desa_cataloghi';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Team Desantis';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = [
            'min' => '8.2.0',
            'max' => _PS_VERSION_,
        ];
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Desa Cataloghi');
        $this->description = $this->l('Gestione cataloghi PDF con anteprime e descrizioni');

        $this->confirmUninstall = $this->l('Sei sicuro di voler disinstallare il modulo? Tutti i cataloghi caricati verranno eliminati.');
    }

    public function install()
    {
        include_once _PS_MODULE_DIR_ . $this->name . '/classes/DesaCatalogo.php';

        return parent::install() &&
            $this->registerHook('displayDesaCataloghi') &&
            $this->createTables() &&
            $this->installTab();
    }

    public function uninstall()
    {
        include_once _PS_MODULE_DIR_ . $this->name . '/classes/DesaCatalogo.php';

        return parent::uninstall() &&
            $this->dropTables() &&
            $this->uninstallTab();
    }

    private function createTables()
    {
        $sql = [];

        $sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'desa_catalogo` (
            `id_desa_catalogo` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
            `title` VARCHAR(255) NOT NULL,
            `description` TEXT,
            `preview_image` VARCHAR(255) DEFAULT NULL,
            `pdf_file` VARCHAR(255) NOT NULL,
            `position` INT(11) UNSIGNED NOT NULL DEFAULT 0,
            `active` TINYINT(1) UNSIGNED NOT NULL DEFAULT 1,
            `date_add` DATETIME NOT NULL,
            `date_upd` DATETIME NOT NULL,
            PRIMARY KEY (`id_desa_catalogo`)
        ) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci';

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute($query)) {
                return false;
            }
        }

        return true;
    }

    private function dropTables()
    {
        return Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'desa_catalogo`');
    }

    private function installTab()
    {
        $tab = new Tab();
        $tab->active = 1;
        $tab->class_name = 'AdminDesaCataloghi';
        $tab->name = [];
        foreach (Language::getLanguages(true) as $lang) {
            $tab->name[$lang['id_lang']] = 'Gestione cataloghi';
        }
        $tab->id_parent = (int) Tab::getIdFromClassName('IMPROVE');
        $tab->module = $this->name;
        
        return $tab->add();
    }

    private function uninstallTab()
    {
        $id_tab = (int) Tab::getIdFromClassName('AdminDesaCataloghi');
        if ($id_tab) {
            $tab = new Tab($id_tab);
            return $tab->delete();
        }
        return true;
    }

    public function getContent()
    {
        Tools::redirectAdmin($this->context->link->getAdminLink('AdminDesaCataloghi', true));
    }

    public function hookDisplayDesaCataloghi($params)
    {
        include_once _PS_MODULE_DIR_ . $this->name . '/classes/DesaCatalogo.php';

        $catalogs = DesaCatalogo::getCatalogs(true);
        
        if (!$catalogs) {
            return '';
        }

        $this->context->smarty->assign([
            'catalogs' => $catalogs,
            'module_dir' => _MODULE_DIR_ . $this->name . '/',
            'lang' => [
                'download' => $this->l('Scarica PDF'),
                'noCatalogs' => $this->l('Nessun catalogo disponibile al momento.'),
            ],
        ]);

        return $this->display(__FILE__, 'views/templates/hook/displayDesaCataloghi.tpl');
    }
}
