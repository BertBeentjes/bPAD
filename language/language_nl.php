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
$lang[LSSNames::STRUCTURE_EDIT_PANEL] = 'Bewerkenpaneel';
$lang[LSSNames::STRUCTURE_ADMIN_TEXT_INPUT] = 'Beheer - invoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_CHECKBOX] = 'Beheer - vinkveld';
$lang[LSSNames::STRUCTURE_ADMIN_COMBOBOX] = 'Beheer - lijst/invoerveld';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX] = 'Beheer - lijstveld';
$lang[LSSNames::STRUCTURE_ADMIN_LISTBOX_OPTION] = 'Beheer - lijstveld optie';
$lang[LSSNames::STRUCTURE_ADMIN_SECTION] = 'Beheer - sectie';
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

$lang[AdminLabels::ADMIN_OBJECT_NAME] = 'Naam';
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
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_STRUCTURES] = 'Positie-indelingen';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_BUTTON_CONFIG_TEMPLATES] = 'Sjablonen';
$lang[AdminLabels::ADMIN_LAYOUT_VERSION_BODY] = 'Indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUT] = 'Nieuwe indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_LAYOUTVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_SET] = 'Nieuwe set';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTURE] = 'Nieuwe positie-indeling';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STRUCTUREVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLE] = 'Nieuwe stijl';
$lang[AdminLabels::ADMIN_BUTTON_ADD_STYLEVERSION] = 'Nieuwe versie';
$lang[AdminLabels::ADMIN_BUTTON_ADD_TEMPLATE] = 'Nieuw sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUT] = 'Verwijder indeling';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_LAYOUTVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_SET] = 'Verwijder set';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTURE] = 'Verwijder positie-indeling';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STRUCTUREVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLE] = 'Verwijder stijl';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_STYLEVERSION] = 'Verwijder versie';
$lang[AdminLabels::ADMIN_BUTTON_REMOVE_TEMPLATE] = 'Verwijder sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_LAYOUTVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STRUCTUREVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_STYLEVERSION] = 'Publiceer versie';
$lang[AdminLabels::ADMIN_BUTTON_PUBLISH_TEMPLATE] = 'Publiceer sjabloon';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_LAYOUTVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STRUCTUREVERSION] = 'Annuleer versie';
$lang[AdminLabels::ADMIN_BUTTON_CANCEL_STYLEVERSION] = 'Annuleer versie';
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
$lang[AdminLabels::ADMIN_CONFIG_TEMPLATES] = 'Sjablonen';
$lang[AdminLabels::ADMIN_TEMPLATE_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_TEMPLATE_DELETED] = 'Niet in gebruik';
$lang[AdminLabels::ADMIN_TEMPLATE_INSTANCE_ALLOWED] = 'Zichtbaar in overzichten';
$lang[AdminLabels::ADMIN_TEMPLATE_SEARCHABLE] = 'Hoort bij bovenliggend item';
$lang[AdminLabels::ADMIN_TEMPLATE_SET] = 'Set';
$lang[AdminLabels::ADMIN_TEMPLATE_STRUCTURE] = 'Positie-indeling bij invoegen';
$lang[AdminLabels::ADMIN_TEMPLATE_STYLE] = 'Stijl bij invoegen';
$lang[AdminLabels::ADMIN_CONFIG_SETS] = 'Sets';
$lang[AdminLabels::ADMIN_SET_NAME] = 'Naam';
$lang[AdminLabels::ADMIN_PROCESSING] = 'Verwerken...';
?>