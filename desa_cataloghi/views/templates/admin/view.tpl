{**
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
 *}

<div class="panel">
    <div class="panel-heading">
        <i class="icon-book"></i> {$title|escape:'html':'UTF-8'}
    </div>
    
    <div class="alert alert-info">
        <p><strong>{$lang.howToUse|default:'Come utilizzare questa sezione'}</strong></p>
        <ul>
            <li>{$lang.addCatalog|default:'Clicca su \"Aggiungi nuovo catalogo\" per creare un nuovo catalogo'}</li>
            <li>{$lang.fillFields|default:'Compila tutti i campi richiesti: Titolo, Descrizione, Immagine anteprima e File PDF'}</li>
            <li>{$lang.dragDrop|default:'Trascina le righe della tabella per riordinare i cataloghi (drag & drop)'}</li>
            <li>{$lang.hookUsage|default:'Utilizza l\'hook {displayDesaCataloghi} nel tuo tema per visualizzare i cataloghi nel front-office'}</li>
        </ul>
    </div>
    
    <div class="row">
        <div class="col-lg-6">
            <p class="help-block">
                {$lang.dragHint|default:'Usa l\'icona di spostamento a sinistra di ogni riga per riordinare i cataloghi.'}
            </p>
        </div>
        <div class="col-lg-6 text-right">
            <button type="button" class="btn btn-default" onclick="window.print();">
                <i class="icon-print"></i> {$lang.printList|default:'Stampa lista'}
            </button>
        </div>
    </div>
</div>

<script type="text/javascript">
    var ajax_admin_url = '{$currentIndex|escape:'javascript':'UTF-8'}&ajax=1';
    var admin_token = '{$token|escape:'javascript':'UTF-8'}';
</script>
