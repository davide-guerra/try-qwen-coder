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

class AdminDesaCataloghiController extends ModuleAdminController
{
    public function __construct()
    {
        $this->bootstrap = true;
        $this->table = 'desa_catalogo';
        $this->className = 'DesaCatalogo';
        $this->lang = false;
        $this->identifier = 'id_desa_catalogo';
        $this->title = 'Gestione Cataloghi';

        parent::__construct();

        $this->fields_list = [
            'id_desa_catalogo' => [
                'title' => $this->l('ID'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'title' => [
                'title' => $this->l('Titolo'),
                'filter_key' => 'a!title',
            ],
            'preview_image' => [
                'title' => $this->l('Anteprima'),
                'type' => 'image',
                'image' => 'preview_image',
                'alt' => 'title',
                'orderby' => false,
                'search' => false,
                'class' => 'fixed-width-sm',
            ],
            'pdf_file' => [
                'title' => $this->l('File PDF'),
                'orderby' => false,
                'search' => false,
                'callback' => 'displayPdfLink',
            ],
            'position' => [
                'title' => $this->l('Posizione'),
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'active' => [
                'title' => $this->l('Attivo'),
                'active' => 'status',
                'type' => 'bool',
                'align' => 'center',
                'class' => 'fixed-width-xs',
            ],
            'date_add' => [
                'title' => $this->l('Data creazione'),
                'type' => 'date',
                'align' => 'right',
            ],
        ];

        $this->bulk_actions = [
            'delete' => [
                'text' => $this->l('Elimina selezionati'),
                'confirm' => $this->l('Eliminare gli elementi selezionati?'),
            ],
        ];
    }

    public function initContent()
    {
        $this->addJqueryPlugin(['sortable']);
        $this->addJS(_MODULE_DIR_ . $this->module->name . '/views/js/desacataloghi-admin.js');
        $this->addCSS(_MODULE_DIR_ . $this->module->name . '/views/css/desacataloghi.css');
        
        $this->initToolbar();
        $this->initPageHeaderToolbar();

        $this->content .= $this->renderView();
        $this->content .= $this->renderList();

        $this->context->smarty->assign([
            'currentIndex' => self::$currentIndex,
            'token' => $this->token,
            'table' => $this->table,
            'identifier' => $this->identifier,
            'name_controller' => $this->controller_name,
            'lang' => [
                'howToUse' => $this->l('Come utilizzare questa sezione'),
                'addCatalog' => $this->l('Clicca su "Aggiungi nuovo catalogo" per creare un nuovo catalogo'),
                'fillFields' => $this->l('Compila tutti i campi richiesti: Titolo, Descrizione, Immagine anteprima e File PDF'),
                'dragDrop' => $this->l('Trascina le righe della tabella per riordinare i cataloghi (drag & drop)'),
                'hookUsage' => $this->l('Utilizza l\'hook displayDesaCataloghi nel tuo tema per visualizzare i cataloghi nel front-office'),
                'dragHint' => $this->l('Usa l\'icona di spostamento a sinistra di ogni riga per riordinare i cataloghi.'),
                'printList' => $this->l('Stampa lista'),
            ],
        ]);

        parent::initContent();
    }

    public function renderView()
    {
        $this->tpl_view_vars = [
            'module_dir' => _MODULE_DIR_ . $this->module->name . '/',
        ];

        return parent::renderView();
    }

    public function initToolbar()
    {
        parent::initToolbar();

        if (empty($this->page_header_toolbar_btn['new'])) {
            $this->page_header_toolbar_btn['new'] = [
                'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
                'desc' => $this->l('Aggiungi nuovo catalogo'),
            ];
        }
    }

    public function initPageHeaderToolbar()
    {
        parent::initPageHeaderToolbar();

        if ($this->display != 'edit' && $this->display != 'add') {
            $this->page_header_toolbar_btn['new'] = [
                'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
                'desc' => $this->l('Aggiungi nuovo catalogo'),
            ];
        }
    }

    public function renderForm()
    {
        $this->fields_form = [
            'legend' => [
                'title' => $this->l('Catalogo'),
                'icon' => 'icon-book',
            ],
            'input' => [
                [
                    'type' => 'text',
                    'label' => $this->l('Titolo'),
                    'name' => 'title',
                    'required' => true,
                    'hint' => $this->l('Inserisci il titolo del catalogo'),
                ],
                [
                    'type' => 'textarea',
                    'label' => $this->l('Descrizione'),
                    'name' => 'description',
                    'autoload_rte' => true,
                    'hint' => $this->l('Descrizione del catalogo (HTML consentito)'),
                ],
                [
                    'type' => 'file',
                    'label' => $this->l('Immagine anteprima'),
                    'name' => 'preview_image',
                    'required' => false,
                    'hint' => $this->l('Carica un\'immagine di anteprima (formati consentiti: JPG, PNG, GIF)'),
                    'thumb' => $this->getPreviewThumb(),
                ],
                [
                    'type' => 'file',
                    'label' => $this->l('File PDF'),
                    'name' => 'pdf_file',
                    'required' => true,
                    'hint' => $this->l('Carica il file PDF del catalogo'),
                ],
                [
                    'type' => 'switch',
                    'label' => $this->l('Attivo'),
                    'name' => 'active',
                    'is_bool' => true,
                    'values' => [
                        [
                            'id' => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Sì'),
                        ],
                        [
                            'id' => 'active_off',
                            'value' => 0,
                            'label' => $this->l('No'),
                        ],
                    ],
                ],
            ],
            'submit' => [
                'title' => $this->l('Salva'),
            ],
        ];

        if (($obj = $this->loadObject(true)) && $obj->id) {
            $this->fields_form['input'][] = [
                'type' => 'hidden',
                'name' => 'id_desa_catalogo',
            ];
        }

        return parent::renderForm();
    }

    private function getPreviewThumb()
    {
        if (($obj = $this->loadObject(true)) && $obj->id && $obj->preview_image) {
            $imagePath = _PS_MODULE_DIR_ . $this->module->name . '/views/images/' . $obj->preview_image;
            if (file_exists($imagePath)) {
                return _MODULE_DIR_ . $this->module->name . '/views/images/' . $obj->preview_image;
            }
        }
        return false;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('add' . $this->table) || Tools::isSubmit('save' . $this->table)) {
            $this->processSave();
        } elseif (Tools::isSubmit('delete' . $this->table)) {
            $this->processDelete();
        } elseif (Tools::isSubmit('submitBulkdelete' . $this->table)) {
            $this->processBulkDelete();
        }

        parent::postProcess();
    }

    protected function processSave()
    {
        $obj = $this->loadObject(true);

        // Gestione immagine anteprima
        if (isset($_FILES['preview_image']) && $_FILES['preview_image']['tmp_name']) {
            $imageName = 'preview_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['preview_image']['name']);
            $uploadDir = _PS_MODULE_DIR_ . $this->module->name . '/views/images/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['preview_image']['tmp_name'], $uploadDir . $imageName)) {
                // Elimina vecchia immagine se esiste
                if ($obj->id && $obj->preview_image && file_exists($uploadDir . $obj->preview_image)) {
                    unlink($uploadDir . $obj->preview_image);
                }
                $_POST['preview_image'] = $imageName;
            } else {
                $this->errors[] = $this->l('Errore durante il caricamento dell\'immagine anteprima');
            }
        } elseif ($obj->id && !Tools::getValue('preview_image_remove')) {
            $_POST['preview_image'] = $obj->preview_image;
        } else {
            $_POST['preview_image'] = null;
        }

        // Gestione file PDF
        if (isset($_FILES['pdf_file']) && $_FILES['pdf_file']['tmp_name']) {
            $pdfName = 'catalog_' . time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $_FILES['pdf_file']['name']);
            $uploadDir = _PS_MODULE_DIR_ . $this->module->name . '/views/pdf/';
            
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }

            if (move_uploaded_file($_FILES['pdf_file']['tmp_name'], $uploadDir . $pdfName)) {
                // Elimina vecchio PDF se esiste
                if ($obj->id && $obj->pdf_file && file_exists($uploadDir . $obj->pdf_file)) {
                    unlink($uploadDir . $obj->pdf_file);
                }
                $_POST['pdf_file'] = $pdfName;
            } else {
                $this->errors[] = $this->l('Errore durante il caricamento del file PDF');
            }
        } elseif ($obj->id && !Tools::getValue('pdf_file_remove')) {
            $_POST['pdf_file'] = $obj->pdf_file;
        }

        parent::processSave();
    }

    public function processDelete()
    {
        $obj = $this->loadObject(true);

        if ($obj->id) {
            // Elimina immagine anteprima
            if ($obj->preview_image) {
                $imagePath = _PS_MODULE_DIR_ . $this->module->name . '/views/images/' . $obj->preview_image;
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Elimina file PDF
            if ($obj->pdf_file) {
                $pdfPath = _PS_MODULE_DIR_ . $this->module->name . '/views/pdf/' . $obj->pdf_file;
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
            }
        }

        parent::processDelete();
    }

    public static function displayPdfLink($pdfFile)
    {
        if (!$pdfFile) {
            return '-';
        }

        $module = Module::getInstanceByName('desa_cataloghi');
        $pdfUrl = _MODULE_DIR_ . 'desa_cataloghi/views/pdf/' . $pdfFile;
        
        return '<a href="' . Tools::safeOutput($pdfUrl) . '" target="_blank" class="btn btn-default">
            <i class="icon-file-pdf"></i> ' . basename($pdfFile) . '
        </a>';
    }

    public function ajaxProcessupdatePositions()
    {
        $positions = Tools::getValue('positions');
        
        if (is_array($positions)) {
            DesaCatalogo::updatePositions($positions);
            $this->ajaxDie(json_encode(['success' => true]));
        }
        
        $this->ajaxDie(json_encode(['success' => false]));
    }
}
