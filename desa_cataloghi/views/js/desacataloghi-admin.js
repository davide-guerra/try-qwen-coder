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

$(document).ready(function() {
    // Inizializza sortable sulla tabella dei cataloghi
    var $tableBody = $('table.table tbody');
    
    if ($tableBody.length > 0 && $tableBody.find('tr').length > 0) {
        $tableBody.sortable({
            handle: '.drag-handle',
            cursor: 'move',
            tolerance: 'pointer',
            containment: 'parent',
            placeholder: 'ui-sortable-placeholder',
            update: function(event, ui) {
                var positions = {};
                
                $tableBody.find('tr').each(function(index) {
                    var id = $(this).attr('id');
                    if (id) {
                        var idNum = id.replace('desa_catalogo_', '');
                        positions[index] = parseInt(idNum);
                    }
                });
                
                $.ajax({
                    url: ajax_admin_url,
                    type: 'POST',
                    data: {
                        ajax: true,
                        action: 'updatePositions',
                        controller: 'AdminDesaCataloghi',
                        positions: positions,
                        token: admin_token
                    },
                    success: function(response) {
                        try {
                            var result = JSON.parse(response);
                            if (result.success) {
                                showSuccessMessage('Posizioni aggiornate con successo!');
                            } else {
                                showErrorMessage('Errore durante l\'aggiornamento delle posizioni.');
                            }
                        } catch(e) {
                            showErrorMessage('Errore nella risposta del server.');
                        }
                    },
                    error: function(xhr, status, error) {
                        showErrorMessage('Errore di comunicazione con il server: ' + error);
                    }
                });
            }
        });
        
        // Aggiungi icona drag handle alla prima colonna di ogni riga
        $tableBody.find('tr').each(function() {
            var $firstTd = $(this).find('td:first');
            if ($firstTd.length > 0 && !$firstTd.find('.drag-handle').length) {
                $firstTd.prepend('<span class="drag-handle" style="cursor: move; margin-right: 10px; color: #999;"><i class="icon-move"></i></span>');
            }
        });
        
        // Aggiungi classe per styling
        $tableBody.addClass('sortable-catalogs-list');
    }
});
