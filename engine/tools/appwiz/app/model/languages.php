<?php

/**
 * ZnetDK, Starter Web Application for rapid & easy development
 * See official website http://www.znetdk.fr 
 * Copyright (C) 2015 Pascal MARTINEZ (contact@znetdk.fr)
 * License GNU GPL http://www.gnu.org/licenses/gpl-3.0.html GNU GPL
 * --------------------------------------------------------------------
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * --------------------------------------------------------------------
 * App Wizard languages (ISO639-1)  
 *
 * File version: 1.0
 * Last update: 09/18/2015
 */

namespace app\model;

/**
 * Database of the ISO639-1 languages
 */
class Languages {

    /**
     * Searchs the languages matching the specified keyword
     * @param string $keyword Pattern of the language to find out
     * @return array Languages found for the specified keyword 
     */
    static public function getLanguages($keyword) {
        $allLang = self::getAllLanguages();
        $foundLanguages = array();
        foreach($allLang as $code=>$label) {
            if(stristr($label, $keyword)) {
                $foundLanguages[] = array('value'=>$code,'label'=>$label);
            }
        }
        return $foundLanguages;
    }
    
    /**
     * Returns the ISO code matching the specified language label 
     * @param string $languageLabel Label of the language to look for
     * @return mixed The language code matching the specified label, FALSE otherwise.
     */
    static public function getLanguageCode($languageLabel) {
        $allLang = self::getAllLanguages();
        foreach($allLang as $code=>$label) {
            if ($languageLabel === $label) {
                return $code;
            }
        }
        return FALSE;
    }
    
    /**
     * Returns the language label of the current language set in the user session
     * @return string Label matching the current language.
     */
    static public function getCurrentLanguageLabel() {
        $allLanguages = self::getAllLanguages();
        $code = \UserSession::getLanguage();
        return $allLanguages[$code];
    }
    
    static private function getAllLanguages() {
        return array(
        "aa"=>"Afar",
        "ab"=>"Abkhazian",
        "ae"=>"Avestan",
        "af"=>"Afrikaans",
        "ak"=>"Akan",
        "am"=>"Amharic",
        "an"=>"Aragonese",
        "ar"=>"Arabic",
        "as"=>"Assamese",
        "av"=>"Avaric",
        "ay"=>"Aymara",
        "az"=>"Azerbaijani",
        "ba"=>"Bashkir",
        "be"=>"Belarusian",
        "bg"=>"Bulgarian",
        "bh"=>"Bihari languages",
        "bi"=>"Bislama",
        "bm"=>"Bambara",
        "bn"=>"Bengali",
        "bo"=>"Tibetan",
        "br"=>"Breton",
        "bs"=>"Bosnian",
        "ca"=>"Catalan; Valencian",
        "ce"=>"Chechen",
        "ch"=>"Chamorro",
        "co"=>"Corsican",
        "cr"=>"Cree",
        "cs"=>"Czech",
        "cu"=>"Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic",
        "cv"=>"Chuvash",
        "cy"=>"Welsh",
        "da"=>"Danish",
        "de"=>"German",
        "dv"=>"Divehi; Dhivehi; Maldivian",
        "dz"=>"Dzongkha",
        "ee"=>"Ewe",
        "el"=>"Greek, Modern (1453-)",
        "en"=>"English",
        "eo"=>"Esperanto",
        "es"=>"Spanish; Castilian",
        "et"=>"Estonian",
        "eu"=>"Basque",
        "fa"=>"Persian",
        "ff"=>"Fulah",
        "fi"=>"Finnish",
        "fj"=>"Fijian",
        "fo"=>"Faroese",
        "fr"=>"French",
        "fy"=>"Western Frisian",
        "ga"=>"Irish",
        "gd"=>"Gaelic; Scottish Gaelic",
        "gl"=>"Galician",
        "gn"=>"Guarani",
        "gu"=>"Gujarati",
        "gv"=>"Manx",
        "ha"=>"Hausa",
        "he"=>"Hebrew",
        "hi"=>"Hindi",
        "ho"=>"Hiri Motu",
        "hr"=>"Croatian",
        "ht"=>"Haitian; Haitian Creole",
        "hu"=>"Hungarian",
        "hy"=>"Armenian",
        "hz"=>"Herero",
        "ia"=>"Interlingua (International Auxiliary Language Association)",
        "id"=>"Indonesian",
        "ie"=>"Interlingue; Occidental",
        "ig"=>"Igbo",
        "ii"=>"Sichuan Yi; Nuosu",
        "ik"=>"Inupiaq",
        "io"=>"Ido",
        "is"=>"Icelandic",
        "it"=>"Italian",
        "iu"=>"Inuktitut",
        "ja"=>"Japanese",
        "jv"=>"Javanese",
        "ka"=>"Georgian",
        "kg"=>"Kongo",
        "ki"=>"Kikuyu; Gikuyu",
        "kj"=>"Kuanyama; Kwanyama",
        "kk"=>"Kazakh",
        "kl"=>"Kalaallisut; Greenlandic",
        "km"=>"Central Khmer",
        "kn"=>"Kannada",
        "ko"=>"Korean",
        "kr"=>"Kanuri",
        "ks"=>"Kashmiri",
        "ku"=>"Kurdish",
        "kv"=>"Komi",
        "kw"=>"Cornish",
        "ky"=>"Kirghiz; Kyrgyz",
        "la"=>"Latin",
        "lb"=>"Luxembourgish; Letzeburgesch",
        "lg"=>"Ganda",
        "li"=>"Limburgan; Limburger; Limburgish",
        "ln"=>"Lingala",
        "lo"=>"Lao",
        "lt"=>"Lithuanian",
        "lu"=>"Luba-Katanga",
        "lv"=>"Latvian",
        "mg"=>"Malagasy",
        "mh"=>"Marshallese",
        "mi"=>"Maori",
        "mk"=>"Macedonian",
        "ml"=>"Malayalam",
        "mn"=>"Mongolian",
        "mr"=>"Marathi",
        "ms"=>"Malay",
        "mt"=>"Maltese",
        "my"=>"Burmese",
        "na"=>"Nauru",
        "nb"=>"Bokmål, Norwegian; Norwegian Bokmål",
        "nd"=>"Ndebele, North; North Ndebele",
        "ne"=>"Nepali",
        "ng"=>"Ndonga",
        "nl"=>"Dutch; Flemish",
        "nn"=>"Norwegian Nynorsk; Nynorsk, Norwegian",
        "no"=>"Norwegian",
        "nr"=>"Ndebele, South; South Ndebele",
        "nv"=>"Navajo; Navaho",
        "ny"=>"Chichewa; Chewa; Nyanja",
        "oc"=>"Occitan (post 1500); Provençal",
        "oj"=>"Ojibwa",
        "om"=>"Oromo",
        "or"=>"Oriya",
        "os"=>"Ossetian; Ossetic",
        "pa"=>"Panjabi; Punjabi",
        "pi"=>"Pali",
        "pl"=>"Polish",
        "ps"=>"Pushto; Pashto",
        "pt"=>"Portuguese",
        "qu"=>"Quechua",
        "rm"=>"Romansh",
        "rn"=>"Rundi",
        "ro"=>"Romanian; Moldavian; Moldovan",
        "ru"=>"Russian",
        "rw"=>"Kinyarwanda",
        "sa"=>"Sanskrit",
        "sc"=>"Sardinian",
        "sd"=>"Sindhi",
        "se"=>"Northern Sami",
        "sg"=>"Sango",
        "si"=>"Sinhala; Sinhalese",
        "sk"=>"Slovak",
        "sl"=>"Slovenian",
        "sm"=>"Samoan",
        "sn"=>"Shona",
        "so"=>"Somali",
        "sq"=>"Albanian",
        "sr"=>"Serbian",
        "ss"=>"Swati",
        "st"=>"Sotho, Southern",
        "su"=>"Sundanese",
        "sv"=>"Swedish",
        "sw"=>"Swahili",
        "ta"=>"Tamil",
        "te"=>"Telugu",
        "tg"=>"Tajik",
        "th"=>"Thai",
        "ti"=>"Tigrinya",
        "tk"=>"Turkmen",
        "tl"=>"Tagalog",
        "tn"=>"Tswana",
        "to"=>"Tonga (Tonga Islands)",
        "tr"=>"Turkish",
        "ts"=>"Tsonga",
        "tt"=>"Tatar",
        "tw"=>"Twi",
        "ty"=>"Tahitian",
        "ug"=>"Uighur; Uyghur",
        "uk"=>"Ukrainian",
        "ur"=>"Urdu",
        "uz"=>"Uzbek",
        "ve"=>"Venda",
        "vi"=>"Vietnamese",
        "vo"=>"Volapük",
        "wa"=>"Walloon",
        "wo"=>"Wolof",
        "xh"=>"Xhosa",
        "yi"=>"Yiddish",
        "yo"=>"Yoruba",
        "za"=>"Zhuang; Chuang",
        "zh"=>"Chinese",
        "zu"=>"Zulu");
    }

}
