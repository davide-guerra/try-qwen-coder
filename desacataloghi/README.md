# Desa Cataloghi - Modulo PrestaShop 8.2

**Autore:** Team Desantis  
**Versione:** 1.0.0  
**Compatibilità:** PrestaShop 8.2+

## Descrizione

Modulo per la gestione di cataloghi PDF nel backoffice di PrestaShop. Consente di caricare, organizzare e visualizzare cataloghi con anteprime immagini, descrizioni ricche e file PDF scaricabili.

## Caratteristiche Principali

- ✅ **Gestione completa cataloghi**: Carica file PDF con titolo, descrizione e immagine anteprima
- ✅ **Editor HTML integrato**: Per descrizioni ricche e formattate
- ✅ **Ordinamento Drag & Drop**: Riordina i cataloghi trascinandoli nella lista
- ✅ **Hook personalizzato**: `displayDesaCataloghi` per integrare i cataloghi nel tuo tema
- ✅ **Stampa ottimizzata**: CSS dedicato per la stampa della lista cataloghi
- ✅ **Gestione file**: Upload e eliminazione automatica di immagini e PDF

## Installazione

1. Scarica il modulo e comprimilo in un file ZIP
2. Nel backoffice di PrestaShop, vai su **Moduli > Gestione moduli**
3. Clicca su **"Carica un modulo"** e seleziona il file ZIP
4. Attendi il completamento dell'installazione
5. Il modulo creerà automaticamente:
   - La tabella database `ps_desa_catalogo`
   - La voce di menu "Gestione cataloghi" sotto la sezione "Migliora"

## Utilizzo

### Backoffice

1. Accedi al backoffice di PrestaShop
2. Vai su **Migliora > Gestione cataloghi**
3. Clicca su **"Aggiungi nuovo catalogo"**
4. Compila i campi:
   - **Titolo**: Nome del catalogo (obbligatorio)
   - **Descrizione**: Descrizione dettagliata con editor HTML
   - **Immagine anteprima**: Immagine di preview (JPG, PNG, GIF)
   - **File PDF**: Il catalogo in formato PDF (obbligatorio)
   - **Attivo**: Stato di visibilità nel front-office
5. Salva il catalogo

### Ordinamento Drag & Drop

- Nella lista dei cataloghi, usa l'icona di spostamento (↔️) a sinistra di ogni riga
- Trascina le righe per riordinarle
- L'ordinamento viene salvato automaticamente via AJAX

### Stampa Lista

- Clicca sul pulsante **"Stampa lista"** in alto a destra
- La vista di stampa è ottimizzata per una corretta impaginazione

## Hook Disponibili

### displayDesaCataloghi

Hook personalizzato per visualizzare i cataloghi nel front-office.

#### Utilizzo nel tema

Inserisci questo codice nel file `.tpl` dove vuoi visualizzare i cataloghi:

```smarty
{hook h='displayDesaCataloghi'}
```

Oppure nel PHP del tuo controller/theme:

```php
$this->context->smarty->assign('catalogs', DesaCatalogo::getCatalogs(true));
return $this->fetch('module:desacataloghi/views/templates/hook/displayDesaCataloghi.tpl');
```

## Struttura File

```
desacataloghi/
├── desacataloghi.php              # File principale del modulo
├── classes/
│   └── DesaCatalogo.php           # Modello dati
├── controllers/
│   └── admin/
│       └── AdminDesaCataloghiController.php  # Controller backoffice
├── views/
│   ├── css/
│   │   └── desacataloghi.css      # Stili front-office
│   ├── js/
│   │   └── desacataloghi-admin.js # JavaScript per drag & drop
│   ├── images/                     # Cartella per le anteprime
│   ├── pdf/                        # Cartella per i file PDF
│   └── templates/
│       ├── admin/
│       │   └── view.tpl           # Template vista admin
│       └── hook/
│           └── displayDesaCataloghi.tpl  # Template hook front-office
└── sql/                           # Eventuali script SQL
```

## Database

Il modulo crea la tabella `ps_desa_catalogo` con i seguenti campi:

| Campo | Tipo | Descrizione |
|-------|------|-------------|
| id_desa_catalogo | INT | Chiave primaria |
| title | VARCHAR(255) | Titolo del catalogo |
| description | TEXT | Descrizione HTML |
| preview_image | VARCHAR(255) | Nome file immagine anteprima |
| pdf_file | VARCHAR(255) | Nome file PDF |
| position | INT | Posizione ordinamento |
| active | TINYINT | Stato attivo/disattivo |
| date_add | DATETIME | Data creazione |
| date_upd | DATETIME | Data ultima modifica |

## Disinstallazione

1. Vai su **Moduli > Gestione moduli**
2. Trova **"Desa Cataloghi"** nella lista
3. Clicca su **"Disinstalla"**
4. Conferma l'operazione

⚠️ **Attenzione**: La disinstallazione eliminerà tutti i cataloghi caricati e la tabella database.

## Supporto

Per assistenza o segnalazione di bug, contatta:
- **Email**: info@teamdesantis.com
- **Sito web**: www.teamdesantis.com

## Licenza

Questo modulo è rilasciato sotto licenza Academic Free License (AFL 3.0).
Per maggiori informazioni visita: http://opensource.org/licenses/afl-3.0.php

---

© 2024 Team Desantis - Tutti i diritti riservati.
