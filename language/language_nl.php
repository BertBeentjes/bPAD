<?php
/**
 * Application: bPAD
 * Author: Bert Beentjes
 * Copyright: Copyright Bert Beentjes 2010-2014
 * http://www.bertbeentjes.nl, http://www.bpadcms.nl
 * 
 * This file is part of the bPAD content management system.
 * 
 * bPAD is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * bPAD is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with bPAD.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Taalstrings in het Nederlands. De genummerde strings komen voor in de code,
 * de strings met een tekstueel id komen voor in de database (meestal in 
 * waardelijsten)
 * 
 */
$lang = array();
$lang[0] = 'De attributen kunnen niet opgehaald worden';
$lang[1] = 'Het bijwerken van het attribuut is mislukt';
$lang[2] = 'Er is geen backup context beschikbaar voor deze context, vraag je beheerder om de instellingen voor backup contexten te controleren';
$lang[3] = 'Dit attribuut is door bPAD vastgesteld en kan niet worden gewijzigd';
$lang[4] = 'Het bijwerken van de wijzigdatum en -gebruiker is mislukt';
$lang[5] = 'Het valideren van een systeemparameter is mislukt';
$lang[6] = 'Opslag is niet beschikbaar, controleer de instelling of probeer later nog een keer';
$lang[7] = 'Sorry, er ging iets fout.';
$lang[8] = 'De aanvraag is niet compleet of niet correct en kan niet door de server afgehandeld worden';
$lang[9] = 'De syntax van het commando is niet correct';
$lang[10] = 'De syntax van de URL is niet correct';
$lang[11] = 'Onvoldoende autorisatie om een pagina op te vragen';
$lang[12] = 'De snippet voor deze contextgroup is niet gevonden';
$lang[13] = 'De fabriek is verkeerd geinitialiseerd en kan niet produceren';
$lang[14] = 'Het aantal ongeldige inlogpogingen voor deze gebruiker is overschreden, neem contact op met de sitebeheerder.';
$lang[15] = 'Ongeldig wachtwoord. Resterend aantal pogingen voor deze gebruiker: ';
$lang[16] = 'De combinatie van gebruikersnaam en wachtwoord is onbekend.';
$lang[17] = 'De stylesheetcache is corrupt geraakt.';
$lang[18] = 'Het gevraagde gelinkte bestand is niet gevonden.';
$lang[19] = 'Het gevraagde bestand is niet gevonden';
$lang[20] = 'Onvoldoende autorisatie om dit uit te voeren.';
$lang[21] = 'De gevraagde positie is niet gevonden.';
$lang[22] = 'Het commando bevat onbekende gegevens en kan niet uitgevoerd worden.';
$lang[23] = 'Het commando is ongeldig en kan niet uitgevoerd worden';
$lang[24] = 'De ingevoerde waarde is niet toegestaan voor dit veld.';
$lang[25] = 'Dit item is door een andere gebruiker bewerkt, ververs de pagina om de bewerking opnieuw uit te voeren.';
$lang[26] = 'Het bestand is groter dan de limiet voor het uploaden van bestanden';
$lang[27] = 'Het item dat gemaakt moet worden bestaat al';

$lang['SETTINGS_SITE_NAME'] = 'Naam';
$lang['SETTINGS_SITE_ROOT'] = 'URL';
$lang['SETTINGS_SITE_ROOTFOLDER'] = 'Map';
$lang['SETTINGS_SITE_LANGUAGE'] = 'Taal';
$lang['SETTINGS_SITE_ADMINEMAIL'] = 'E-mailadres beheerder';
$lang['SETTINGS_SITE_MAXUPLOADSIZE'] = 'Maximale grootte uploadbestanden';
$lang['SETTINGS_SITE_UPLOAD_LOCATION'] = 'Locatie voor uploadbestanden';
$lang['SETTINGS_SITE_UPLOAD_LOCATION_PERMISSIONS'] = 'Permissies voor de locatie voor uploadbestanden';

$lang['SETTINGS_SECURITY_HASHALGORITHM'] = 'Hash algoritme';
$lang['SETTINGS_SECURITY_SALT'] = 'Salt';
$lang['SETTINGS_SECURITY_MAXLOGINATTEMPTS'] = 'Maximum aantal inlogpogingen';

$lang['SETTINGS_UPDATE_LSSMASTER'] = 'Mastersite voor indelingen, stijlen en structuren';
$lang['SETTINGS_UPDATE_LSSPASSWORD'] = 'Wachtwoord voor iss mastersite';

$lang['SETTINGS_CONTENT_SETMOBILEVIEWPORT'] = 'Zet de mobile view port';
$lang['SETTINGS_CONTENT_MOBILEUSEPNDEFAULT'] = 'Gebruik standaardwaarden voor #pn# indelingen';
$lang['SETTINGS_CONTENT_SHOWLIGHTBOXOBJECTNAME'] = 'Toon de objectnaam in de editor';
$lang['SETTINGS_CONTENT_USECONTENTDIVADMINCLASS'] = 'Gebruik de admin class voor de content div';
$lang['SETTINGS_CONTENT_PRELOADINSTANCES'] = 'Het aantal zoekresultaten dat direct geladen wordt';
$lang['SETTINGS_CONTENT_PRELOADPNOBJECTS'] = 'Het aantal objecten in een #pn# indeling dat direct geladen wordt';

$lang['SETTINGS_CONTEXT_DEFAULTMINWIDTH'] = 'Minimale schermbreedte voor de standaard context';
$lang['SETTINGS_CONTEXT_DEFAULTMINHEIGHT'] = 'Minimale schermhoogte voor de standaard context';

$lang['SETTINGS_GOOGLE_ANALYTICSCODE'] = 'Google Analytics code';

$lang['SETTINGS_FRONTENDMENU_EDITINLINE'] = 'In de site bewerken';
$lang['SETTINGS_FRONTENDMENU_EDITLIGHTBOX'] = 'Via de lightbox bewerken';
$lang['SETTINGS_FRONTENDMENU_EDITNAME'] = 'Objectnaam bewerken';
$lang['SETTINGS_FRONTENDMENU_STYLES'] = 'Stijlen aanpassen';
$lang['SETTINGS_FRONTENDMENU_LAYOUTS'] = 'Indelingen aanpassen';
$lang['SETTINGS_FRONTENDMENU_STRUCTURES'] = 'Structuren aanpassen';
$lang['SETTINGS_FRONTENDMENU_ARGUMENT'] = 'Argumenten aanpassen';
$lang['SETTINGS_FRONTENDMENU_AUTHORIZATION'] = 'Autorisaties aanpassen';
$lang['SETTINGS_FRONTENDMENU_MOVE'] = 'Verplaatsen';
$lang['SETTINGS_FRONTENDMENU_MOVEUPDOWN'] = 'Omhoog of omlaag verplaatsen';
$lang['SETTINGS_FRONTENDMENU_PUBLISH'] = 'Publiceren';
$lang['SETTINGS_FRONTENDMENU_UPDATE'] = 'Bijwerken';
$lang['SETTINGS_FRONTENDMENU_DEACTIVATE'] = 'Deactiveren';
$lang['SETTINGS_FRONTENDMENU_DELETE'] = 'Verwijderen';

$lang['CONTEXTGROUP_DEFAULT'] = 'standaard';
$lang['CONTEXTGROUP_MOBILE'] = 'mobiel';
$lang['CONTEXTGROUP_METADATA'] = 'metadata';
$lang['CONTEXTGROUP_SITEMAP'] = 'sitemap';

$lang['CONTEXTGROUP_DEFAULT_SHORT'] = 'std';
$lang['CONTEXTGROUP_MOBILE_SHORT'] = 'mob';
$lang['CONTEXTGROUP_METADATA_SHORT'] = 'mtd';
$lang['CONTEXTGROUP_SITEMAP_SHORT'] = 'sit';

$lang['CONTEXT_DEFAULT'] = 'standaard';
$lang['CONTEXT_INSTANCE'] = 'overzicht';
$lang['CONTEXT_RECYCLEBIN'] = 'prullenbak';
$lang['CONTEXT_INLINE'] = 'tekststroom';
$lang['CONTEXT_SLIDE'] = 'dia';

$lang['CONTEXT_DEFAULT_SHORT'] = 'std';
$lang['CONTEXT_INSTANCE_SHORT'] = 'ovz';
$lang['CONTEXT_RECYCLEBIN_SHORT'] = 'prb';
$lang['CONTEXT_INLINE_SHORT'] = 'tks';
$lang['CONTEXT_SLIDE_SHORT'] = 'dia';

$lang['INPUTTYPE_TEXTAREA'] = 'Tekst';
$lang['INPUTTYPE_INPUTBOX'] = 'Invoerveld';
$lang['INPUTTYPE_COMBOBOX'] = 'Keuzelijst';
$lang['INPUTTYPE_UPLOADEDFILE'] = 'Bestand';

$lang['POSITION_STYLE'] = 'Voor positie-indelingen';
$lang['OBJECT_STYLE'] = 'Voor indelingen';

$lang[PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_ASC] = 'Wijzigdatum - oudste eerst';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CHANGEDATE_DESC] = 'Wijzigdatum - nieuwste eerst';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_ASC] = 'Aanmaakdatum - oudste eerst';
$lang[PositionInstance::POSITIONINSTANCE_ORDER_CREATEDATE_DESC] = 'Aanmaakdatum - nieuwste eerst';

$lang[PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_ASC] = 'Wijzigdatum - oudste eerst';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CHANGEDATE_DESC] = 'Wijzigdatum - nieuwste eerst';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_ASC] = 'Aanmaakdatum - oudste eerst';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_CREATEDATE_DESC] = 'Aanmaakdatum - nieuwste eerst';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NAME_ASC] = 'Naam - A-Z';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NAME_DESC] = 'Naam - Z-A';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_ASC] = 'Nummer - omhoog';
$lang[PositionReferral::POSITIONREFERRAL_ORDER_NUMBER_DESC] = 'Nummer - omlaag';

$lang[LSSNames::STRUCTURE_CENTERED_TEXT] = 'Gecentreerde tekst';
$lang[LSSNames::STRUCTURE_H1] = 'Kop 1';
$lang[LSSNames::STRUCTURE_H2] = 'Kop 2';
$lang[LSSNames::STRUCTURE_H3] = 'Kop 3';
$lang[LSSNames::STRUCTURE_H4] = 'Kop 4';
$lang[LSSNames::STRUCTURE_H5] = 'Kop 5';
$lang[LSSNames::STRUCTURE_PARAGRAPH_START] = 'Paragraaf begin';
$lang[LSSNames::STRUCTURE_PARAGRAPH_END] = 'Paragraaf einde';
$lang[LSSNames::STRUCTURE_INTERNAL_LINK_START] = 'Interne link begin';
$lang[LSSNames::STRUCTURE_INTERNAL_LINK_END] = 'Interne link einde';
$lang[LSSNames::STRUCTURE_STRONG] = 'Nadruk';
$lang[LSSNames::STRUCTURE_ACCENT] = 'Accentkleur';
$lang[LSSNames::STRUCTURE_ITALIC] = 'Cursief';
$lang[LSSNames::STRUCTURE_EXTERNAL_LINK_START] = 'Externe link begin';
$lang[LSSNames::STRUCTURE_EXTERNAL_LINK_END] = 'Externe link einde';
$lang[LSSNames::STRUCTURE_LIST_ITEM] = 'Lijstitem';
$lang[LSSNames::STRUCTURE_LIST_START] = 'Lijst begin';
$lang[LSSNames::STRUCTURE_LIST_END] = 'Lijst einde';
$lang[LSSNames::STRUCTURE_NEW_LINE] = 'Nieuwe regel';
$lang[LSSNames::STRUCTURE_BREADCRUMB] = 'Kruimelpad';
$lang[LSSNames::STRUCTURE_BREADCRUMB_SEPARATOR] = 'Kruimelpad scheidingsteken';
$lang[LSSNames::STRUCTURE_LAZY_LOAD] = 'Gefaseerd inladen';
$lang[LSSNames::STRUCTURE_SEARCH_BOX] = 'Zoeken';
$lang[LSSNames::STRUCTURE_INSTANCE_HEADER] = 'Overzicht tussenkop';
$lang[LSSNames::STRUCTURE_INSTANCE_SECTION] = 'Overzicht sectie';
$lang[LSSNames::STRUCTURE_CONFIG_BUTTON] = 'Configuratie';
$lang[LSSNames::STRUCTURE_ADD_BUTTON] = 'Toevoegen';
$lang[LSSNames::STRUCTURE_BUTTON_TOGGLE] = 'Knoppen';
$lang[LSSNames::STRUCTURE_EDIT_BUTTON] = 'Bewerken';
$lang[LSSNames::STRUCTURE_CONFIG_PANEL] = 'Configuratiepaneel';
$lang[LSSNames::STRUCTURE_ADD_PANEL] = 'Toevoegpaneel';
$lang[LSSNames::STRUCTURE_DEEP_LINK] = 'Diepe link';
$lang[LSSNames::STRUCTURE_EDIT_PANEL] = 'Bewerkenpaneel';
$lang[LSSNames::STRUCTURE_MOVE_BUTTON] = 'Verplaatsen';
$lang[LSSNames::STRUCTURE_MOVE_PANEL] = 'Verplaatsenpaneel';
$lang[LSSNames::STRUCTURE_ERROR_MESSAGE] = 'Foutmelding';
$lang[LSSNames::STRUCTURE_MODAL] = 'Melding';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD] = 'Beheer - toon toevoegknoppen';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS] = 'Beheer -toon opmaakitems';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_ADD_NAME] = 'Toevoegknoppen';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_TOGGLE_LSS_NAME] = 'Opmaakitems';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_INPUT] = 'Beheer - invoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_CHECKBOX] = 'Beheer - vinkveld';
$lang[LSSNames::STRUCTURE_ADMIN_COMBOBOX] = 'Beheer - lijst/invoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX] = 'Beheer - lijstveld';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_LSS] = 'Beheer - lijstveld opmaakitems';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_OPTION] = 'Beheer - lijstveld optie';
$lang[LSSNames::STRUCTURE_ADMIN_ERROR_MESSAGE] = 'Beheer - foutmelding';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION] = 'Beheer - sectie';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_COLLAPSED] = 'Beheer - sectie ingeklapt';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_ADD] = 'Beheer - sectie toevoegknoppen';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION_HEADER] = 'Beheer - sectiekop';
$lang[LSSNames::STRUCTURE_ADMIN_SEPARATOR] = 'Beheer - scheider';
$lang[LSSNames::STRUCTURE_ADMIN_SUB_ITEM] = 'Beheer - subsectie';
$lang[LSSNames::STRUCTURE_ADMIN_FILE_INPUT] = 'Beheer - bestandsinvoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_AREA] = 'Beheer - tekstinvoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_UPLOAD] = 'Beheer - bestand uploaden';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON] = 'Beheer - knop';
$lang[LSSNames::STRUCTURE_ADMIN_MAIN_BUTTON] = 'Beheer - menuknop';
$lang[LSSNames::STRUCTURE_ADMIN_MENU] = 'Beheer - menu';
$lang[LSSNames::STRUCTURE_ADMIN_MENU_ITEM] = 'Beheer - menuitem';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP] = 'Beheer - knoppengroep';
$lang[LSSNames::STRUCTURE_ADMIN_BUTTON_GROUP_ALT] = 'Beheer - knoppengroep alternatief';
$lang[LSSNames::STRUCTURE_BASIC] = 'Standaard';
$lang[LSSNames::STRUCTURE_POSITION_INSERT] = 'Invoegpositie';
$lang[LSSNames::STRUCTURE_MENU_ITEM] = 'Menuitem';
$lang[LSSNames::STRUCTURE_CONTENT_ITEM] = 'Contentitem';
$lang[LSSNames::STRUCTURE_IMG] = 'Beeld';
$lang[LSSNames::STRUCTURE_TEXT] = 'Tekst';
$lang[LSSNames::STRUCTURE_TEXT_LARGE] = 'Tekst groot';
$lang[LSSNames::STRUCTURE_IMG_FULL_HIDDEN_SMALL] = 'Beeld volledig, verborgen op kleine schermen';
$lang[LSSNames::STRUCTURE_SEARCH_SITE] = 'Zoeken op site';
$lang[LSSNames::STRUCTURE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::STRUCTURE_IMG_CAPTION] = 'Beeld onderschrift';
$lang[LSSNames::STRUCTURE_TITLE_H1] = 'Titel H1';
$lang[LSSNames::STRUCTURE_TITLE_H2] = 'Titel H2';
$lang[LSSNames::STRUCTURE_TITLE_H3] = 'Titel H3';
$lang[LSSNames::STRUCTURE_BANNER_TEXT] = 'Banner tekst';
$lang[LSSNames::STRUCTURE_HTML] = 'HTML';
$lang[LSSNames::STRUCTURE_OBJECT_UNPUBLISHED_INDICATOR] = 'Object ongepubliceerde aanpassingen';

$lang[LSSNames::LAYOUT_AD] = 'Advertentie';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_TITLE_TEXT] = 'Artikel beeld - titel - tekst';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_TITLE_TEXT_WIDE] = 'Artikel beeld - titel - tekst breed';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT] = 'Artikel beeld links - tekst rechts';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_TEXT_RIGHT_WIDE] = 'Artikel beeld links - tekst rechts - breed';
$lang[LSSNames::LAYOUT_ARTICLE_IMG_LEFT_SMALL_TEXT_RIGHT_WIDE] = 'Artikel beeld links klein tekst rechts breed';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_IMG_TEXT] = 'Artikel titel - beeld - tekst';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT] = 'Artikel titel - tekst';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_WIDE] = 'Artikel titel - tekst breed';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_LEFT_IMG_RIGHT] = 'Artikel titel - tekst links beeld rechts';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_TEXT_FULL] = 'Artikel titel - tekst volledig';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK] = 'Artikel titel - twee blokken';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_EQUAL] = 'Artikel titel - twee blokken gelijk';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_LARGE_BLOCK] = 'Artikel titel - twee blokken links groot';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_OVERLAY_BLOCK] = 'Artikel titel - twee blokken links overlay';
$lang[LSSNames::LAYOUT_ARTICLE_TITLE_BLOCK_BLOCK_LARGE] = 'Artikel titel - twee blokken rechts groot';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK] = 'Artikel twee blokken';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_IMG_BLOCK] = 'Artikel twee blokken - gelijk beeld links';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_IMG] = 'Artikel twee blokken - gelijk beeld rechts';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_WIDE] = 'Artikel twee blokken - gelijk en breed';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK] = 'Artikel twee blokken - links groot';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_LARGE_BLOCK_WIDE] = 'Artikel twee blokken - links groot - breed';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK] = 'Artikel twee blokken - overlay links';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_OVERLAY_BLOCK_WIDE] = 'Artikel twee blokken - overlay links breed';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY] = 'Artikel twee blokken - overlay rechts';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_OVERLAY_WIDE] = 'Artikel twee blokken - overlay rechts breed';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE] = 'Artikel twee blokken - rechts groot';
$lang[LSSNames::LAYOUT_ARTICLE_BLOCK_BLOCK_LARGE_WIDE] = 'Artikel twee blokken - rechts groot - breed';
$lang[LSSNames::LAYOUT_BANNER] = 'Banner';
$lang[LSSNames::LAYOUT_IN_TEXT_IMG] = 'Beeld bij tekst';
$lang[LSSNames::LAYOUT_IN_TEXT_IMG_SMALL] = 'Beeld bij tekst klein';
$lang[LSSNames::LAYOUT_IMG_TEXT] = 'Beeld en tekst';
$lang[LSSNames::LAYOUT_IMG_COLUMN_TEXT] = 'Beeld en tekst - beeld kolom';
$lang[LSSNames::LAYOUT_IMG_TEXT_COLUMN] = 'Beeld en tekst - tekst kolom';
$lang[LSSNames::LAYOUT_IMG_TEXT_SNIPPET] = 'Beeld en tekst snippet';
$lang[LSSNames::LAYOUT_IMG_CLICKABLE] = 'Beeld klikbaar';
$lang[LSSNames::LAYOUT_IMG_CLICKABLE_FULL] = 'Beeld klikbaar volledig';
$lang[LSSNames::LAYOUT_IMG_LANDSCAPE] = 'Beeld liggend';
$lang[LSSNames::LAYOUT_IMG_CAPTION] = 'Beeld onderschrift';
$lang[LSSNames::LAYOUT_IMG_PORTRAIT] = 'Beeld staand';
$lang[LSSNames::LAYOUT_IMG_THUMBNAIL] = 'Beeld thumbnail';
$lang[LSSNames::LAYOUT_IMG_FULL] = 'Beeld volledig';
$lang[LSSNames::LAYOUT_IMG_FULL_HIDDEN_XS] = 'Beeld volledig - verborgen in 1-kolomsopmaak';
$lang[LSSNames::LAYOUT_IMG_NO_CAPTION] = 'Beeld zonder onderschrift';
$lang[LSSNames::LAYOUT_BLOCK] = 'Blok';
$lang[LSSNames::LAYOUT_CAROUSEL] = 'Carousel';
$lang[LSSNames::LAYOUT_CAROUSELITEM] = 'Carouselitem';
$lang[LSSNames::LAYOUT_CAROUSELITEM_ACTIVE] = 'Carouselitem active';
$lang[LSSNames::LAYOUT_CONTENT_NO_BUTTONS] = 'Content - geen menu';
$lang[LSSNames::LAYOUT_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::LAYOUT_PAGEPART] = 'Inzet';
$lang[LSSNames::LAYOUT_COLUMN_1] = 'Kolom breedte 1';
$lang[LSSNames::LAYOUT_COLUMN_10] = 'Kolom breedte 10';
$lang[LSSNames::LAYOUT_COLUMN_11] = 'Kolom breedte 11';
$lang[LSSNames::LAYOUT_COLUMN_12] = 'Kolom breedte 12';
$lang[LSSNames::LAYOUT_COLUMN_12_NO_BUTTONS] = 'Kolom breedte 12 - geen menu';
$lang[LSSNames::LAYOUT_COLUMN_2] = 'Kolom breedte 2';
$lang[LSSNames::LAYOUT_COLUMN_3] = 'Kolom breedte 3';
$lang[LSSNames::LAYOUT_COLUMN_3_SM_6] = 'Kolom breedte 3 - kleine schermen 6';
$lang[LSSNames::LAYOUT_COLUMN_4] = 'Kolom breedte 4';
$lang[LSSNames::LAYOUT_COLUMN_5] = 'Kolom breedte 5';
$lang[LSSNames::LAYOUT_COLUMN_6] = 'Kolom breedte 6';
$lang[LSSNames::LAYOUT_COLUMN_7] = 'Kolom breedte 7';
$lang[LSSNames::LAYOUT_COLUMN_8] = 'Kolom breedte 8';
$lang[LSSNames::LAYOUT_COLUMN_8_SM_12] = 'Kolom breedte 8 - small 12';
$lang[LSSNames::LAYOUT_COLUMN_9] = 'Kolom breedte 9';
$lang[LSSNames::LAYOUT_MENU_LIST] = 'Menulijst';
$lang[LSSNames::LAYOUT_NAVPILLS] = 'Navigatie pills';
$lang[LSSNames::LAYOUT_NAVTOP_WIDE] = 'Navigatie top breed';
$lang[LSSNames::LAYOUT_NAVTOP] = 'Navigatie top standaard';
$lang[LSSNames::LAYOUT_NAVTOP_FIXED] = 'Navigatie top vast';
$lang[LSSNames::LAYOUT_INSTANCE] = 'Overzicht';
$lang[LSSNames::LAYOUT_PAGE] = 'Pagina';
$lang[LSSNames::LAYOUT_PAGESECTION] = 'Paginadeel';
$lang[LSSNames::LAYOUT_SITEROOT] = 'Site root';
$lang[LSSNames::LAYOUT_SITECONTENT] = 'Sitecontent';
$lang[LSSNames::LAYOUT_SUBNAV] = 'Subnavigatie';
$lang[LSSNames::LAYOUT_TEXT] = 'Tekst';
$lang[LSSNames::LAYOUT_LINE] = 'Tekstregel';
$lang[LSSNames::LAYOUT_TITLE] = 'Titel';
$lang[LSSNames::LAYOUT_WHITESPACE] = 'Witruimte';

$lang[LSSNames::STYLE_AD_TITLE_IMG_TEXT] = 'Advertentie titel - beeld - tekst';
$lang[LSSNames::STYLE_ARTICLE_TITLE_IMG_TEXT] = 'Artikel titel - beeld - tekst';
$lang[LSSNames::STYLE_TITLE_IMG_TEXT_MARGIN] = 'Artikel titel - beeld - tekst - grote marge';
$lang[LSSNames::STYLE_ARTICLE_TITLE_TEXT] = 'Artikel titel - tekst';
$lang[LSSNames::STYLE_ARTICLE_TITLE_TEXT_BGLIGHT] = 'Artikel titel - tekst lichte achtergrond';
$lang[LSSNames::STYLE_ARTICLE_TITLE_BLOCK_BLOCK] = 'Artikel titel - twee blokken';
$lang[LSSNames::STYLE_ARTICLE_TITLE_BLOCK_BLOCK_MARGIN] = 'Artikel titel - twee blokken - grote marge';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK] = 'Artikel twee blokken';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK_COMPACT] = 'Artikel twee blokken - compact';
$lang[LSSNames::STYLE_ARTICLE_BLOCK_BLOCK_NO_BG] = 'Artikel twee blokken - geen achtergrond';
$lang[LSSNames::STYLE_BANNER] = 'Banner';
$lang[LSSNames::STYLE_BANNER_DARK] = 'Banner donker';
$lang[LSSNames::STYLE_BANNER_TEXT] = 'Banner tekst';
$lang[LSSNames::STYLE_BANNER_TEXT_DARK] = 'Banner tekst donker';
$lang[LSSNames::STYLE_BANNER_TEXT_ACCENT] = 'Banner tekst accent';
$lang[LSSNames::STYLE_IMG] = 'Beeld';
$lang[LSSNames::STYLE_IMG_TEXT] = 'Beeld en tekst - beeld';
$lang[LSSNames::STYLE_IMG_TEXT_SNIPPET] = 'Beeld en tekst snippet';
$lang[LSSNames::STYLE_IMG_CLICKABLE] = 'Beeld klikbaar';
$lang[LSSNames::STYLE_IMG_CLICKABLE_CENTER] = 'Beeld klikbaar midden';
$lang[LSSNames::STYLE_IMG_CLICKABLE_CENTER_FULL] = 'Beeld klikbaar midden volledig';
$lang[LSSNames::STYLE_IMG_CLICKABLE_FULL] = 'Beeld klikbaar volledig';
$lang[LSSNames::STYLE_IMG_LEFT] = 'Beeld links';
$lang[LSSNames::STYLE_IMG_MARGIN_TOP] = 'Beeld marge boven';
$lang[LSSNames::STYLE_IMG_BORDER] = 'Beeld met kader';
$lang[LSSNames::STYLE_IMG_CENTER] = 'Beeld midden';
$lang[LSSNames::STYLE_IMG_MARGIN_NEGATIVE] = 'Beeld negatieve marge';
$lang[LSSNames::STYLE_IMG_CAPTION] = 'Beeld onderschrift';
$lang[LSSNames::STYLE_IMG_RIGHT] = 'Beeld rechts';
$lang[LSSNames::STYLE_IMG_THUMBNAIL] = 'Beeld thumbnail';
$lang[LSSNames::STYLE_IMG_FULL] = 'Beeld volledig';
$lang[LSSNames::STYLE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::STYLE_PAGEPART_RIGHT] = 'Inzet rechts';
$lang[LSSNames::STYLE_COLUMN] = 'Kolom';
$lang[LSSNames::STYLE_COLUMN_BLOCK_ACCENT] = 'Kolom blok accent';
$lang[LSSNames::STYLE_COLUMN_BLOCK_DARK] = 'Kolom blok donker';
$lang[LSSNames::STYLE_COLUMN_BLOCK_LIGHT] = 'Kolom blok licht';
$lang[LSSNames::STYLE_COLUMN_THREE_COLUMNS] = 'Kolom driekoloms';
$lang[LSSNames::STYLE_COLUMN_CENTER] = 'Kolom gecentreerd';
$lang[LSSNames::STYLE_COLUMN_CENTER_SMALL] = 'Kolom gecentreerd mobiel';
$lang[LSSNames::STYLE_COLUMN_TWO_THREE_COLUMNS] = 'Kolom twee-driekoloms';
$lang[LSSNames::STYLE_COLUMN_TWO_COLUMNS] = 'Kolom tweekoloms';
$lang[LSSNames::STYLE_COLUMN_FRONT] = 'Kolom voorgrond';
$lang[LSSNames::STYLE_MENU_ITEM] = 'Menuitem';
$lang[LSSNames::STYLE_MENU_LIST] = 'Menulijst';
$lang[LSSNames::STYLE_NAV_AND_CONTENT] = 'Navigatie en content';
$lang[LSSNames::STYLE_OBJECT_DEFAULT] = 'Object default';
$lang[LSSNames::STYLE_INSTANCE] = 'Overzicht';
$lang[LSSNames::STYLE_INSTANCE_NO_BG] = 'Overzicht geen achtergrond';
$lang[LSSNames::STYLE_INSTANCE_LIGHT] = 'Overzicht licht';
$lang[LSSNames::STYLE_PAGE] = 'Pagina';
$lang[LSSNames::STYLE_PAGESECTION] = 'Paginadeel';
$lang[LSSNames::STYLE_PAGESECTION_COMPACT] = 'Paginadeel compact';
$lang[LSSNames::STYLE_PAGESECTION_MARGIN] = 'Paginadeel marges';
$lang[LSSNames::STYLE_POSITION_DEFAULT] = 'Positie default';
$lang[LSSNames::STYLE_SITE] = 'Site';
$lang[LSSNames::STYLE_TEXT_THREE_COLUMNS] = 'Tekst driekoloms';
$lang[LSSNames::STYLE_TEXT_EXTRA_LARGE] = 'Tekst extra groot';
$lang[LSSNames::STYLE_TEXT_LARGE] = 'Tekst groot';
$lang[LSSNames::STYLE_TEXT_LARGE_ALT] = 'Tekst groot alt';
$lang[LSSNames::STYLE_TEXT_DARK_BG] = 'Tekst op donkere achtergrond';
$lang[LSSNames::STYLE_TEXT_LIGHT_BG] = 'Tekst op lichte achtergrond';
$lang[LSSNames::STYLE_TEXT_TWO_COLUMNS] = 'Tekst tweekoloms';
$lang[LSSNames::STYLE_LINE] = 'Tekstregel';
$lang[LSSNames::STYLE_TITLE] = 'Titel';
$lang[LSSNames::STYLE_TITLE_ARTICLE] = 'Titel artikel positie';
$lang[LSSNames::STYLE_TITLE_DARK] = 'Titel donker';
$lang[LSSNames::STYLE_TITLE_LEFT] = 'Titel links';
$lang[LSSNames::STYLE_TITLE_MARGIN] = 'Titel marge';
$lang[LSSNames::STYLE_TITLE_RIGHT] = 'Titel rechts';
$lang[LSSNames::STYLE_WHITESPACE] = 'Witruimte';
$lang[LSSNames::STYLE_WHITESPACE_LARGE] = 'Witruimte groot';

$lang[LSSNames::SET_AD] = 'Advertentie';
$lang[LSSNames::SET_ARTICLE_TITLE_IMG_TEXT] = 'Artikel titel - beeld - tekst';
$lang[LSSNames::SET_ARTICLE_TITLE_TEXT] = 'Artikel titel - tekst';
$lang[LSSNames::SET_ARTICLE_TITLE_BLOCK_BLOCK] = 'Artikel titel - twee blokken';
$lang[LSSNames::SET_ARTICLE_BLOCK_BLOCK] = 'Artikel twee blokken';
$lang[LSSNames::SET_BANNER] = 'Banner';
$lang[LSSNames::SET_IMG] = 'Beeld';
$lang[LSSNames::SET_IMG_TEXT] = 'Beeld en tekst';
$lang[LSSNames::SET_IMG_TEXT_SNIPPET] = 'Beeld en tekst snippet';
$lang[LSSNames::SET_IMG_CLICKABLE] = 'Beeld klikbaar';
$lang[LSSNames::SET_IMG_CAPTION] = 'Beeld onderschrift';
$lang[LSSNames::SET_CAROUSEL] = 'Carousel';
$lang[LSSNames::SET_CAROUSELITEM] = 'Carouselitem';
$lang[LSSNames::SET_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::SET_PAGEPART] = 'Inzet';
$lang[LSSNames::SET_COLUMN] = 'Kolom';
$lang[LSSNames::SET_MENU_LIST] = 'Menulijst';
$lang[LSSNames::SET_NAV_AND_CONTENT] = 'Navigatie en content';
$lang[LSSNames::SET_INSTANCE] = 'Overzicht';
$lang[LSSNames::SET_PAGE] = 'Pagina';
$lang[LSSNames::SET_PAGESECTION] = 'Paginadeel';
$lang[LSSNames::SET_SITE] = 'Site';
$lang[LSSNames::SET_SITECONTENT] = 'Sitecontent';
$lang[LSSNames::SET_SUBNAV] = 'Subnavigatie';
$lang[LSSNames::SET_TEXT] = 'Tekst';
$lang[LSSNames::SET_LINE] = 'Tekstregel';
$lang[LSSNames::SET_TITLE] = 'Titel';
$lang[LSSNames::SET_WHITESPACE] = 'Witruimte';
$lang[LSSNames::SET_DEFAULT] = '_default';

$lang[LSSNames::TEMPLATE_AD] = 'Advertentie';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_IMG_TEXT] = 'Artikel titel - beeld - tekst';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_TEXT] = 'Artikel titel - tekst';
$lang[LSSNames::TEMPLATE_ARTICLE_TITLE_BLOCK_BLOCK] = 'Artikel titel - twee blokken';
$lang[LSSNames::TEMPLATE_ARTICLE_BLOCK_BLOCK] = 'Artikel twee blokken';
$lang[LSSNames::TEMPLATE_BANNER] = 'Banner';
$lang[LSSNames::TEMPLATE_IMG] = 'Beeld';
$lang[LSSNames::TEMPLATE_IMG_TEXT] = 'Beeld en tekst';
$lang[LSSNames::TEMPLATE_IMG_TEXT_SNIPPET] = 'Beeld en tekst snippet';
$lang[LSSNames::TEMPLATE_IMG_CLICKABLE] = 'Beeld klikbaar';
$lang[LSSNames::TEMPLATE_BLOCK] = 'Blok';
$lang[LSSNames::TEMPLATE_CAROUSEL] = 'Carousel';
$lang[LSSNames::TEMPLATE_CAROUSELITEM] = 'Carouselitem';
$lang[LSSNames::TEMPLATE_FOOTER] = 'Footer';
$lang[LSSNames::TEMPLATE_GLYPHICON] = 'Glyphicon';
$lang[LSSNames::TEMPLATE_HEADER] = 'Header';
$lang[LSSNames::TEMPLATE_PAGEPART] = 'Inzet';
$lang[LSSNames::TEMPLATE_COLUMN] = 'Kolom';
$lang[LSSNames::TEMPLATE_MENU_LIST] = 'Menulijst';
$lang[LSSNames::TEMPLATE_NAV_AND_CONTENT] = 'Navigatie en content';
$lang[LSSNames::TEMPLATE_INSTANCE] = 'Overzicht';
$lang[LSSNames::TEMPLATE_PAGE] = 'Pagina';
$lang[LSSNames::TEMPLATE_PAGESECTION] = 'Paginadeel horizontaal';
$lang[LSSNames::TEMPLATE_SITE] = 'Site';
$lang[LSSNames::TEMPLATE_SUBNAV] = 'Subnavigatie';
$lang[LSSNames::TEMPLATE_TEXT] = 'Tekst';
$lang[LSSNames::TEMPLATE_LINE] = 'Tekstregel';
$lang[LSSNames::TEMPLATE_WHITESPACE] = 'Witruimte';
$lang[LSSNames::TEMPLATE_DEFAULT] = 'Geen template';

$lang[AdminLabels::ADMIN_OBJECT_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_OBJECT_TEMPLATE_NAME] = 'Gebaseerd op sjabloon';
$lang[AdminLabels::ADMIN_OBJECT_INTERNAL_LINK] = 'Link naar dit item';
$lang[AdminLabels::ADMIN_OBJECT_DEEP_LINK] = 'Diepe link naar dit item';
$lang[AdminLabels::ADMIN_OBJECT_ACTIVE] = 'Actief';
$lang[AdminLabels::ADMIN_OBJECT_SET] = 'Set';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_LAYOUT] = 'Indeling';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_STYLE] = 'Stijl';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT_DEFAULT] = 'Standaardwaarde argument';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_ARGUMENT] = 'Argument';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_LAYOUT] = 'Indeling vasthouden';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_INHERIT_STYLE] = 'Stijl vasthouden';
$lang[AdminLabels::ADMIN_OBJECT_VERSION_TEMPLATE] = 'Sjabloon voor posities';
$lang[AdminLabels::ADMIN_POSITION_STRUCTURE] = 'Positie-indeling';
$lang[AdminLabels::ADMIN_POSITION_STYLE] = 'Positiestijl';
$lang[AdminLabels::ADMIN_POSITION_INHERIT_STRUCTURE] = 'Positie-indeling vasthouden';
$lang[AdminLabels::ADMIN_POSITION_INHERIT_STYLE] = 'Positiestijl vasthouden';
$lang[AdminLabels::ADMIN_POSITION_REMOVE] = 'Positie verwijderen';
$lang[AdminLabels::ADMIN_POSITION_ADD_CONTENT_ITEM] = 'Tekstitem invoegen';
$lang[AdminLabels::ADMIN_POSITION_ADD_OBJECT] = 'Object invoegen';
$lang[AdminLabels::ADMIN_POSITION_ADD_INSTANCE] = 'Overzicht invoegen';
$lang[AdminLabels::ADMIN_POSITION_ADD_REFERRAL] = 'Menu invoegen';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_INPUT_TYPE] = 'Invoerveldtype';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_BODY] = 'Sjablooncontent';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_UPLOAD] = 'Bestand';
$lang[AdminLabels::ADMIN_POSITION_CONTENT_ITEM_CURRENT_VALUE] = 'Huidig';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_ACTIVE_ITEMS] = 'Actieve content';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_FILL_ON_LOAD] = 'Overzicht vullen bij laden pagina';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_USE_INSTANCE_CONTEXT] = 'Als lijst tonen';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_GROUP_BY] = 'Groeperen';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_LISTWORDS] = 'Termen';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT] = 'Specifiek object';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_OBJECT_DEFAULT] = 'Geen object (standaardwaarde)';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_ORDER_BY] = 'Sorteren op';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_PARENT] = 'Onderdeel van';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_SEARCHWORDS] = 'Zoeken';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE] = 'Gebaseerd op sjabloon';
$lang[AdminLabels::ADMIN_POSITION_INSTANCE_TEMPLATE_DEFAULT] = 'Geen template (standaardwaarde)';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_ARGUMENT] = 'Menu-aanduiding';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_NUMBER_OF_ITEMS] = 'Aantal items (0 is alles)';
$lang[AdminLabels::ADMIN_POSITION_REFERRAL_ORDER_BY] = 'Sortering';
$lang[AdminLabels::ADMIN_BUTTON_MOVE] = 'Verplaatsen';
$lang[AdminLabels::ADMIN_BUTTON_MOVE_UP] = 'Omhoog verplaatsen';
$lang[AdminLabels::ADMIN_BUTTON_MOVE_DOWN] = 'Omlaag verplaatsen';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH] = 'Wijzigingen publiceren';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_NEW] = 'Nieuw item publiceren';
$lang[AdminLabels::ADMIN_BUTTON_UNDO] = 'Ongedaan maken';
$lang[AdminLabels::ADMIN_BUTTON_KEEP] = 'Sluiten';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL] = 'Annuleren';
$lang[AdminLabels::ADMIN_BUTTON_TO_RECYCLE_BIN] = 'Naar prullenbak';
$lang[AdminLabels::ADMIN_BUTTON_FROM_RECYCLE_BIN] = 'Herstellen';
$lang[AdminLabels::ADMIN_BUTTON_CLOSE] = 'Sluiten';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_MAIN] = 'Configuratiemenu';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_LAYOUTS] = 'Indelingen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STYLES] = 'Stijlen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STYLEPARAMS] = 'Stijlinstellingen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STRUCTURES] = 'Positie-indelingen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_TEMPLATES] = 'Sjablonen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_USERS] = 'Gebruikers';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_USERGROUPS] = 'Gebruikersgroepen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_ROLES] = 'Rollen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SETTINGS] = 'Instellingen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_INCLUDE_FILES] = 'Invoegbestanden';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SNIPPETS] = 'Basisfragmenten';
$lang[AdminLabels::ADMIN_LAYOUT_VERSION_BODY] = 'Indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUT] = 'Nieuwe indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUTVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SET] = 'Nieuwe set';
$lang[AdminLabels::ADMIN_BUTTON_ADD_USER] = 'Nieuwe gebruiker';
$lang[AdminLabels::ADMIN_BUTTON_ADD_USERGROUP] = 'Nieuwe gebruikersgroep';
$lang[AdminLabels::ADMIN_BUTTON_ADD_ROLE] = 'Nieuwe rol';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SETTING] = 'Nieuwe instelling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_INCLUDE_FILE] = 'Nieuw invoegbestand';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SNIPPET] = 'Nieuw basisfragment';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTURE] = 'Nieuwe positie-indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTUREVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLE] = 'Nieuwe stijl';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLE_PARAM] = 'Nieuwe stijlinstelling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLEVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLEPARAMVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_TEMPLATE] = 'Nieuw sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUT] = 'Verwijder indeling';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUTVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_SET] = 'Verwijder set';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_USER] = 'Verwijder gebruiker';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_USERGROUP] = 'Verwijder gebruikersgroep';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_ROLE] = 'Verwijder rol';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_INCLUDE_FILE] = 'Verwijder invoegbestand';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_SNIPPET] = 'Verwijder basisfragment';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTURE] = 'Verwijder positie-indeling';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTUREVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLE] = 'Verwijder stijl';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLE_PARAM] = 'Verwijder stijlinstelling';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLEVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLEPARAMVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_TEMPLATE] = 'Verwijder sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_LAYOUTVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEPARAMVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_TEMPLATE] = 'Publiceer sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_FILEINCLUDEVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_SNIPPETVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_LAYOUTVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STRUCTUREVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STYLEVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STYLEPARAMVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_TEMPLATE] = 'Annuleer sjabloon';
$lang[AdminLabels::ADMIN_CONFIG_LAYOUTS] = 'Indelingen';
$lang[AdminLabels::ADMIN_LAYOUT_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_LAYOUT_SET] = 'Set';
$lang[AdminLabels::ADMIN_LAYOUT_VERSION_BODY] = 'Indeling';
$lang[AdminLabels::ADMIN_CONFIG_STRUCTURES] = 'Positie-indelingen';
$lang[AdminLabels::ADMIN_STRUCTURE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_STRUCTURE_SET] = 'Set';
$lang[AdminLabels::ADMIN_STRUCTURE_VERSION_BODY] = 'Positie-indeling';
$lang[AdminLabels::ADMIN_CONFIG_STYLES] = 'Stijlen';
$lang[AdminLabels::ADMIN_STYLE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_STYLE_SET] = 'Set';
$lang[AdminLabels::ADMIN_STYLE_TYPE] = 'Type';
$lang[AdminLabels::ADMIN_STYLE_CLASS_SUFFIX] = 'Css-class toevoegsel';
$lang[AdminLabels::ADMIN_STYLE_VERSION_BODY] = 'Stijl';
$lang[AdminLabels::ADMIN_CONFIG_STYLE_PARAMS] = 'Stijlinstellingen';
$lang[AdminLabels::ADMIN_STYLE_PARAM_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_CONFIG_TEMPLATES] = 'Sjablonen';
$lang[AdminLabels::ADMIN_TEMPLATE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_TEMPLATE_DELETED] = 'Niet in gebruik';
$lang[AdminLabels::ADMIN_TEMPLATE_INSTANCE_ALLOWED] = 'Zichtbaar in overzichten';
$lang[AdminLabels::ADMIN_TEMPLATE_SEARCHABLE] = 'Hoort bij bovenliggend item';
$lang[AdminLabels::ADMIN_TEMPLATE_SET] = 'Set';
$lang[AdminLabels::ADMIN_TEMPLATE_STRUCTURE] = 'Positie-indeling bij invoegen';
$lang[AdminLabels::ADMIN_TEMPLATE_STYLE] = 'Stijl bij invoegen';
$lang[AdminLabels::ADMIN_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_CONFIG_USERS] = 'Gebruikers';
$lang[AdminLabels::ADMIN_CONFIG_USERGROUPS] = 'Gebruikersgroepen';
$lang[AdminLabels::ADMIN_CONFIG_ROLES] = 'Rollen';
$lang[AdminLabels::ADMIN_CONFIG_SETTINGS] = 'Instellingen';
$lang[AdminLabels::ADMIN_CONFIG_INCLUDE_FILES] = 'Invoegbestanden';
$lang[AdminLabels::ADMIN_CONFIG_SNIPPETS] = 'Basisfragmenten';
$lang[AdminLabels::ADMIN_SET_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_USER_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_USER_PASSWORD] = 'Wachtwoord';
$lang[AdminLabels::ADMIN_USER_FIRST_NAME] = 'Voornaam';
$lang[AdminLabels::ADMIN_USER_LAST_NAME] = 'Achternaam';
$lang[AdminLabels::ADMIN_USER_LOGIN_COUNTER] = 'Herstel aantal foutieve logins: ';
$lang[AdminLabels::ADMIN_USERGROUP_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_ROLE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_SETTING_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_SETTING_VALUE] = 'Waarde';
$lang[AdminLabels::ADMIN_INCLUDE_FILE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_INCLUDE_FILE_MIME_TYPE] = 'Mimetype';
$lang[AdminLabels::ADMIN_SNIPPET_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_SNIPPET_MIME_TYPE] = 'Mimetype';
$lang[AdminLabels::ADMIN_SNIPPET_CONTEXT_GROUP] = 'Contextgroep';
$lang[AdminLabels::ADMIN_PROCESSING] = 'Verwerken...';

$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_CONTENT] = 'Inhoud beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_STYLE] = 'Stijlen beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_STRUCTURE] = 'Positie-indelingen beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_FLUSH_ARCHIVE] = 'Archief opschonen';
$lang[AdminLabels::ADMIN_PERMISSIONS_VIEW_OBJECT] = 'Object bekijken';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_EDIT] = 'Bewerken';
$lang[AdminLabels::ADMIN_PERMISSIONS_UPLOAD_FILE] = 'Bestand uploaden';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_EDIT] = 'Eigen inhoud bewerken';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_ADD] = 'Inhoud toevoegen';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_CREATOR_DEACTIVATE] = 'Eigen inhoud deactiveren';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTEND_DEACTIVATE] = 'Inhoud deactiveren';
$lang[AdminLabels::ADMIN_PERMISSIONS_SHOW_ADMIN_BAR] = 'Beheerknoppen tonen';
$lang[AdminLabels::ADMIN_PERMISSIONS_FRONTENT_RESPOND] = 'Reageren op berichten';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LSS_VERSION] = 'Thema maken';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LAYOUT] = 'Indelingen beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_SYSTEM] = 'Systeem beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_LANGUAGE] = 'Taal beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_SETTING] = 'Instellingen beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_USER] = 'Gebruikers beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_ROLE] = 'Rollen beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_AUTHORIZATION] = 'Objectautorisatie beheren';
$lang[AdminLabels::ADMIN_PERMISSIONS_MANAGE_TEMPLATE] = 'Sjablonen beheren';

$lang[AdminLabels::ADMIN_PERMISSIONS_USER] = 'Gebruikerspermissies';
$lang[AdminLabels::ADMIN_PERMISSIONS_EDITOR] = 'Redactiepermissies';
$lang[AdminLabels::ADMIN_PERMISSIONS_DESIGNER] = 'Ontwerppermissies';
$lang[AdminLabels::ADMIN_PERMISSIONS_ADMINISTRATOR] = 'Beheerpermissies';
