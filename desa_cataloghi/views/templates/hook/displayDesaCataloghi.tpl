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

<div class="desa-cataloghi-list">
    {if $catalogs && $catalogs|count > 0}
        <div class="row">
            {foreach from=$catalogs item=catalog}
                <div class="col-xs-12 col-sm-6 col-md-4 desa-catalog-item" data-id="{$catalog.id_desa_catalogo}">
                    <div class="card desa-catalog-card">
                        {if $catalog.preview_image}
                            <div class="card-img-top desa-catalog-preview">
                                <img src="{$module_dir}views/images/{$catalog.preview_image}" alt="{$catalog.title|escape:'html':'UTF-8'}" class="img-fluid">
                            </div>
                        {/if}
                        <div class="card-body">
                            <h5 class="card-title">{$catalog.title|escape:'html':'UTF-8'}</h5>
                            {if $catalog.description}
                                <div class="card-text">{$catalog.description nofilter}</div>
                            {/if}
                            {if $catalog.pdf_file}
                                <a href="{$module_dir}views/pdf/{$catalog.pdf_file}" target="_blank" class="btn btn-primary desa-catalog-btn">
                                    <i class="material-icons">&#xE873;</i>
                                    {$lang.download|default:'Scarica PDF'}
                                </a>
                            {/if}
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
    {else}
        <p class="alert alert-info">{$lang.noCatalogs|default:'Nessun catalogo disponibile al momento.'}</p>
    {/if}
</div>
