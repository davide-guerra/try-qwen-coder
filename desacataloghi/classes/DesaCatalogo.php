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

class DesaCatalogo extends ObjectModel
{
    public $id_desa_catalogo;
    public $title;
    public $description;
    public $preview_image;
    public $pdf_file;
    public $position;
    public $active;
    public $date_add;
    public $date_upd;

    public static $definition = [
        'table' => 'desa_catalogo',
        'primary' => 'id_desa_catalogo',
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255],
            'description' => ['type' => self::TYPE_HTML, 'validate' => 'isCleanHtml'],
            'preview_image' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'size' => 255],
            'pdf_file' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true, 'size' => 255],
            'position' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedInt'],
            'active' => ['type' => self::TYPE_BOOL, 'validate' => 'isBool'],
            'date_add' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat'],
            'date_upd' => ['type' => self::TYPE_DATE, 'validate' => 'isDateFormat'],
        ],
    ];

    public function add($autodate = true, $null_values = false)
    {
        $this->position = $this->getMaxPosition() + 1;
        return parent::add($autodate, $null_values);
    }

    public function getMaxPosition()
    {
        $sql = 'SELECT MAX(`position`) FROM `' . _DB_PREFIX_ . 'desa_catalogo`';
        $result = Db::getInstance()->getValue($sql);
        return (int) $result;
    }

    public static function getCatalogs($active = null)
    {
        $sql = 'SELECT * FROM `' . _DB_PREFIX_ . 'desa_catalogo`';
        
        if ($active !== null) {
            $sql .= ' WHERE `active` = ' . (int) $active;
        }
        
        $sql .= ' ORDER BY `position` ASC';
        
        return Db::getInstance()->executeS($sql);
    }

    public static function updatePositions($positions)
    {
        foreach ($positions as $position => $id) {
            Db::getInstance()->execute(
                'UPDATE `' . _DB_PREFIX_ . 'desa_catalogo` 
                SET `position` = ' . (int) $position . ' 
                WHERE `id_desa_catalogo` = ' . (int) $id
            );
        }
        return true;
    }
}
