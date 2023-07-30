<?php

/*****************************************
 * Required files
 ****************************************/
require_once('library/kv_admin_post_types.php');
require_once('library/kv-functions.php');
require_once('library/kv_contact_us.php');
require_once('library/eimams_subscriptions_fn.php');
require_once('library/kv_cron_jobs.php');
require_once('library/ajax_file.php');

if (is_admin()) {
    require_once('library/kv_admin_functions.php');
}



/**
 * Email Getway create and Setup
 **/

add_action('phpmailer_init', 'mailer_config');
function mailer_config($phpmailer)
{
    $phpmailer->Host = 'smtp.titan.email';
    $phpmailer->Port = 587; // could be different
    $phpmailer->Username = "admin@eimams.com"; // if required
    $phpmailer->Password   = 'admin@2023#';                               //SMTP password
    $phpmailer->SMTPAuth = true; // if required

    $phpmailer->IsSMTP();
    $phpmailer->SMTPDebug = PHPMailer\PHPMailer\SMTP::DEBUG_SERVER;
}


add_action('admin_init', 'eimams_general_section');
function eimams_general_section()
{
    add_settings_section(
        'eimams_settings_section', // Section ID
        'Eimams Settings', // Section Title
        'eimams_options_callback', // Callback
        'general' // What Page?  This makes the section show up on the General Settings Page
    );


    add_settings_field( // Option 1
        'enable_jobseeker_subscription', // Option ID
        'Enable Job Seeker Subscription', // Label
        'jobseeker_subscription_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'eimams_settings_section', // Name of our section
        array( // The $args
            'enable_jobseeker_subscription' // Should match Option ID
        )
    );
    add_settings_field( // Option 1
        'enable_employer_subscription', // Option ID
        'Enable Employer Subscription', // Label
        'employer_subscription_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'eimams_settings_section', // Name of our section
        array( // The $args
            'enable_employer_subscription' // Should match Option ID
        )
    );

    add_settings_field( // Option 1
        'enable_shia_subscription', // Option ID
        'Enable Shia Subscription', // Label
        'employer_shia_callback', // !important - This is where the args go!
        'general', // Page it will be displayed (General Settings)
        'eimams_settings_section', // Name of our section
        array( // The $args
            'enable_shia_subscription' // Should match Option ID
        )
    );

    register_setting('general', 'enable_jobseeker_subscription', 'esc_attr');
    register_setting('general', 'enable_employer_subscription', 'esc_attr');
    register_setting('general', 'enable_shia_subscription', 'esc_attr');
    //register_setting('general','option_2', 'esc_attr');
}

function eimams_options_callback()
{ // Section Callback
    echo '<p>Master settings to configure eimams to make changes in its operation. </p>';
}

function jobseeker_subscription_callback($args)
{  // Textbox Callback
    $option = get_option($args[0]);
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="yes" ' . ($option == 'yes' ? 'checked' : '') . ' /> Yes </label>';
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="no" ' . ($option == 'no' ? 'checked' : '') . '/> No </label>';
}
function employer_subscription_callback($args)
{  // Textbox Callback
    $option = get_option($args[0]);
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="yes" ' . ($option == 'yes' ? 'checked' : '') . ' /> Yes </label>';
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="no" ' . ($option == 'no' ? 'checked' : '') . '/> No </label>';
}

function employer_shia_callback($args)
{  // Textbox Callback
    $option = get_option($args[0]);
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="yes" ' . ($option == 'yes' ? 'checked' : '') . ' /> Yes </label>';
    echo '<label> <input type="radio" id="' . $args[0] . '" name="' . $args[0] . '" value="no" ' . ($option == 'no' ? 'checked' : '') . '/> No </label>';
}

function Shia_Aqeeda_select($selected = null)
{ ?>
    <option value="-1">Select Aqeeda </option>
    <option value="635" <?php echo ($selected == 635 ? 'selected' : ''); ?>>Ithna ‘Ashari (Imami)</option>
    <optgroup label="Ismaili:">
        <option value="636" <?php echo ($selected == 636 ? 'selected' : ''); ?>>Bohra </option>
    </optgroup>
    <optgroup id="dawoodi" label="&nbsp;&nbsp;&nbsp;&nbsp;Dawoodi Bohra:">
        <option value="637" <?php echo ($selected == 637 ? 'selected' : ''); ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Alavi Bohra</option>
        <option value="638" <?php echo ($selected == 638 ? 'selected' : ''); ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sulaymani Bohra</option>
    </optgroup>
    <option value="639" <?php echo ($selected == 639 ? 'selected' : ''); ?>>Zaidi</option>
<?php }
//add_action('init', 'add_countries', 100);


function add_countries()
{
    $country_array = array(
        'AF' => 'AFGHANISTAN',
        'AL' => 'ALBANIA',
        'DZ' => 'ALGERIA',
        'AS' => 'AMERICAN SAMOA',
        'AD' => 'ANDORRA',
        'AO' => 'ANGOLA',
        'AI' => 'ANGUILLA',
        'AQ' => 'ANTARCTICA',
        'AG' => 'ANTIGUA AND BARBUDA',
        'AR' => 'ARGENTINA',
        'AM' => 'ARMENIA',
        'AW' => 'ARUBA',
        'AU' => 'AUSTRALIA',
        'AT' => 'AUSTRIA',
        'AZ' => 'AZERBAIJAN',
        'BS' => 'BAHAMAS',
        'BH' => 'BAHRAIN',
        'BD' => 'BANGLADESH',
        'BB' => 'BARBADOS',
        'BY' => 'BELARUS',
        'BE' => 'BELGIUM',
        'BZ' => 'BELIZE',
        'BJ' => 'BENIN',
        'BM' => 'BERMUDA',
        'BT' => 'BHUTAN',
        'BO' => 'BOLIVIA',
        'BA' => 'BOSNIA AND HERZEGOVINA',
        'BW' => 'BOTSWANA',
        'BV' => 'BOUVET ISLAND',
        'BR' => 'BRAZIL',
        'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
        'BN' => 'BRUNEI DARUSSALAM',
        'BG' => 'BULGARIA',
        'BF' => 'BURKINA FASO',
        'BI' => 'BURUNDI',
        'KH' => 'CAMBODIA',
        'CM' => 'CAMEROON',
        'CA' => 'CANADA',
        'CV' => 'CAPE VERDE',
        'KY' => 'CAYMAN ISLANDS',
        'CF' => 'CENTRAL AFRICAN REPUBLIC',
        'TD' => 'CHAD',
        'CL' => 'CHILE',
        'CN' => 'CHINA',
        'CX' => 'CHRISTMAS ISLAND',
        'CC' => 'COCOS (KEELING) ISLANDS',
        'CO' => 'COLOMBIA',
        'KM' => 'COMOROS',
        'CG' => 'CONGO',
        'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
        'CK' => 'COOK ISLANDS',
        'CR' => 'COSTA RICA',
        'CI' => 'COTE D IVOIRE',
        'HR' => 'CROATIA',
        'CU' => 'CUBA',
        'CY' => 'CYPRUS',
        'CZ' => 'CZECH REPUBLIC',
        'DK' => 'DENMARK',
        'DJ' => 'DJIBOUTI',
        'DM' => 'DOMINICA',
        'DO' => 'DOMINICAN REPUBLIC',
        'TP' => 'EAST TIMOR',
        'EC' => 'ECUADOR',
        'EG' => 'EGYPT',
        'SV' => 'EL SALVADOR',
        'GQ' => 'EQUATORIAL GUINEA',
        'ER' => 'ERITREA',
        'EE' => 'ESTONIA',
        'ET' => 'ETHIOPIA',
        'FK' => 'FALKLAND ISLANDS (MALVINAS)',
        'FO' => 'FAROE ISLANDS',
        'FJ' => 'FIJI',
        'FI' => 'FINLAND',
        'FR' => 'FRANCE',
        'GF' => 'FRENCH GUIANA',
        'PF' => 'FRENCH POLYNESIA',
        'TF' => 'FRENCH SOUTHERN TERRITORIES',
        'GA' => 'GABON',
        'GM' => 'GAMBIA',
        'GE' => 'GEORGIA',
        'DE' => 'GERMANY',
        'GH' => 'GHANA',
        'GI' => 'GIBRALTAR',
        'GR' => 'GREECE',
        'GL' => 'GREENLAND',
        'GD' => 'GRENADA',
        'GP' => 'GUADELOUPE',
        'GU' => 'GUAM',
        'GT' => 'GUATEMALA',
        'GN' => 'GUINEA',
        'GW' => 'GUINEA-BISSAU',
        'GY' => 'GUYANA',
        'HT' => 'HAITI',
        'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
        'VA' => 'HOLY SEE (VATICAN CITY STATE)',
        'HN' => 'HONDURAS',
        'HK' => 'HONG KONG',
        'HU' => 'HUNGARY',
        'IS' => 'ICELAND',
        'IN' => 'INDIA',
        'ID' => 'INDONESIA',
        'IR' => 'IRAN, ISLAMIC REPUBLIC OF',
        'IQ' => 'IRAQ',
        'IE' => 'IRELAND',
        'IL' => 'ISRAEL',
        'IT' => 'ITALY',
        'JM' => 'JAMAICA',
        'JP' => 'JAPAN',
        'JO' => 'JORDAN',
        'KZ' => 'KAZAKSTAN',
        'KE' => 'KENYA',
        'KI' => 'KIRIBATI',
        'KP' => 'KOREA DEMOCRATIC PEOPLES REPUBLIC OF',
        'KR' => 'KOREA REPUBLIC OF',
        'KW' => 'KUWAIT',
        'KG' => 'KYRGYZSTAN',
        'LA' => 'LAO PEOPLES DEMOCRATIC REPUBLIC',
        'LV' => 'LATVIA',
        'LB' => 'LEBANON',
        'LS' => 'LESOTHO',
        'LR' => 'LIBERIA',
        'LY' => 'LIBYAN ARAB JAMAHIRIYA',
        'LI' => 'LIECHTENSTEIN',
        'LT' => 'LITHUANIA',
        'LU' => 'LUXEMBOURG',
        'MO' => 'MACAU',
        'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
        'MG' => 'MADAGASCAR',
        'MW' => 'MALAWI',
        'MY' => 'MALAYSIA',
        'MV' => 'MALDIVES',
        'ML' => 'MALI',
        'MT' => 'MALTA',
        'MH' => 'MARSHALL ISLANDS',
        'MQ' => 'MARTINIQUE',
        'MR' => 'MAURITANIA',
        'MU' => 'MAURITIUS',
        'YT' => 'MAYOTTE',
        'MX' => 'MEXICO',
        'FM' => 'MICRONESIA, FEDERATED STATES OF',
        'MD' => 'MOLDOVA, REPUBLIC OF',
        'MC' => 'MONACO',
        'MN' => 'MONGOLIA',
        'MS' => 'MONTSERRAT',
        'MA' => 'MOROCCO',
        'MZ' => 'MOZAMBIQUE',
        'MM' => 'MYANMAR',
        'NA' => 'NAMIBIA',
        'NR' => 'NAURU',
        'NP' => 'NEPAL',
        'NL' => 'NETHERLANDS',
        'AN' => 'NETHERLANDS ANTILLES',
        'NC' => 'NEW CALEDONIA',
        'NZ' => 'NEW ZEALAND',
        'NI' => 'NICARAGUA',
        'NE' => 'NIGER',
        'NG' => 'NIGERIA',
        'NU' => 'NIUE',
        'NF' => 'NORFOLK ISLAND',
        'MP' => 'NORTHERN MARIANA ISLANDS',
        'NO' => 'NORWAY',
        'OM' => 'OMAN',
        'PK' => 'PAKISTAN',
        'PW' => 'PALAU',
        'PS' => 'PALESTINIAN TERRITORY, OCCUPIED',
        'PA' => 'PANAMA',
        'PG' => 'PAPUA NEW GUINEA',
        'PY' => 'PARAGUAY',
        'PE' => 'PERU',
        'PH' => 'PHILIPPINES',
        'PN' => 'PITCAIRN',
        'PL' => 'POLAND',
        'PT' => 'PORTUGAL',
        'PR' => 'PUERTO RICO',
        'QA' => 'QATAR',
        'RE' => 'REUNION',
        'RO' => 'ROMANIA',
        'RU' => 'RUSSIAN FEDERATION',
        'RW' => 'RWANDA',
        'SH' => 'SAINT HELENA',
        'KN' => 'SAINT KITTS AND NEVIS',
        'LC' => 'SAINT LUCIA',
        'PM' => 'SAINT PIERRE AND MIQUELON',
        'VC' => 'SAINT VINCENT AND THE GRENADINES',
        'WS' => 'SAMOA',
        'SM' => 'SAN MARINO',
        'ST' => 'SAO TOME AND PRINCIPE',
        'SA' => 'SAUDI ARABIA',
        'SN' => 'SENEGAL',
        'SC' => 'SEYCHELLES',
        'SL' => 'SIERRA LEONE',
        'SG' => 'SINGAPORE',
        'SK' => 'SLOVAKIA',
        'SI' => 'SLOVENIA',
        'SB' => 'SOLOMON ISLANDS',
        'SO' => 'SOMALIA',
        'ZA' => 'SOUTH AFRICA',
        'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
        'ES' => 'SPAIN',
        'LK' => 'SRI LANKA',
        'SD' => 'SUDAN',
        'SR' => 'SURINAME',
        'SJ' => 'SVALBARD AND JAN MAYEN',
        'SZ' => 'SWAZILAND',
        'SE' => 'SWEDEN',
        'CH' => 'SWITZERLAND',
        'SY' => 'SYRIAN ARAB REPUBLIC',
        'TW' => 'TAIWAN, PROVINCE OF CHINA',
        'TJ' => 'TAJIKISTAN',
        'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
        'TH' => 'THAILAND',
        'TG' => 'TOGO',
        'TK' => 'TOKELAU',
        'TO' => 'TONGA',
        'TT' => 'TRINIDAD AND TOBAGO',
        'TN' => 'TUNISIA',
        'TR' => 'TURKEY',
        'TM' => 'TURKMENISTAN',
        'TC' => 'TURKS AND CAICOS ISLANDS',
        'TV' => 'TUVALU',
        'UG' => 'UGANDA',
        'UA' => 'UKRAINE',
        'AE' => 'UNITED ARAB EMIRATES',
        'GB' => 'UNITED KINGDOM',
        'US' => 'UNITED STATES',
        'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
        'UY' => 'URUGUAY',
        'UZ' => 'UZBEKISTAN',
        'VU' => 'VANUATU',
        'VE' => 'VENEZUELA',
        'VN' => 'VIET NAM',
        'VG' => 'VIRGIN ISLANDS, BRITISH',
        'VI' => 'VIRGIN ISLANDS, U.S.',
        'WF' => 'WALLIS AND FUTUNA',
        'EH' => 'WESTERN SAHARA',
        'YE' => 'YEMEN',
        'YU' => 'YUGOSLAVIA',
        'ZM' => 'ZAMBIA',
        'ZW' => 'ZIMBABWE',
    );


    $lang = array(
        'ENG' => 'ENGLISH',
        'BENG' => 'BANGLA',


        'ABK' => 'Abkhaz',
        'ADY' => 'Adyghe',
        'AFR' => 'Afrikaans',
        'AKA' => 'Akan',
        'ALB' => 'Albanian',
        'AME' => 'American Sign Language',
        'AMH' => 'Amharic',
        'ARA' => 'Arabic',
        'ARA' => 'Aragonese',
        'ARM' => 'Aramaic',
        'ARME' => 'Armenian',
        'AYM' => 'Aymara',
        'BAL' => 'Balinese',
        'BAS' => 'Basque',
        'BET' => 'Betawi',
        'BOS' => ' Bosnian',
        'BRE' => 'Breton',
        'BUL' => 'Bulgarian',
        'CAN' => 'Cantonese',
        'CAT' => 'Catalan',
        'CHE' => 'Cherokee',
        'CHI' => 'Chickasaw',
        'CHIN' => 'Chinese',
        'COP' => 'Coptic',
        'COR' => 'Cornish',
        'CORS' => 'Corsican',
        'CRIM' => 'Crimean Tatar',
        'CROT' => 'Croatian',
        'CZE' => 'Czech',
        'DAN' => 'Danish',
        'DUT' => 'Dutch',
        'DAW' => 'Dawro',
        'ESP' => 'Esperanto',
        'EST' => 'Estonian',
        'EWE' => 'Ewe',
        'FIJI' => 'Fiji Hindi',
        'FILI' => 'Filipino',
        'FIN' => 'Finnish',
        'FRE' => 'French',
        'GAL' => 'Galician',
        'GEO' => 'Georgian',
        'GER' => 'German',
        'GREE' => 'Greek Modern',
        'ANC' => 'Ancient Greek',
        'GREEN' => 'Greenlandic',
        'HAIT' => 'Haitian Creole',
        'HAWA' => 'Hawaiian',
        'HEB' => 'Hebrew',
        'HIND' => 'Hindi',
        'HUNG' => 'Hungarian',
        'ICE' => 'Icelandic',
        'INDO' => 'Indonesian',
        'INU' => 'Inuktitut',
        'INT' => 'Interlingua',
        'IRI' => 'Irish',
        'ITAL' => 'Italian',
        'JAPA' => 'Japanese',
        'JAVA' => 'Javanese',
        'KAB' => 'Kabardian',
        'KAL' => 'Kalasha',
        'KANN' => 'Kannada',
        'KASH' => 'Kashubian',
        'KHM' => 'Khmer',
        'KINY' => 'Kinyarwanda',
        'KORE' => 'Korean',
        'KUR' => 'Kurdish Kurdî',
        'LAD' => 'Ladin',
        'LAT' => 'Latgalian',
        'LATI' => 'Latin',
        'LING' => 'Lingala',
        'LIVO' => 'Livonian',
        'LOJ' => 'Lojban',
        'LOWER' => 'Lower Sorbian',
        'LOW' => 'Low German',
        'MACE' => 'Macedonian',
        'MALAY' => 'Malay',
        'MAL' => 'Malayalam',
        'MAND' => 'Mandarin',
        'MANX' => 'Manx',
        'MAO' => 'Maori',
        'MAUR' => 'Mauritian Creole',
        'MIDD' => 'Middle Low German',
        'MIN' => 'Min Nan',
        'MONG' => 'Mongolian',
        'NOR' => 'Norwegian',
        'OLDARM' => 'Old Armenian',
        'OLDENG' => 'Old English',
        'OLDFRE' => 'Old French',
        'OLDJAV' => 'Old Javanese',
        'OLDNOR' => 'Old Norse',
        'OLDPRU' => 'Old Prussian',
        'ORIYA' => 'Oriya',
        'PANG' => 'Pangasinan',
        'PAPI' => 'Papiamentu',
        'PASH' => 'Pashto',
        'PERS' => 'Persian',
        'PITJ' => 'Pitjantjatjara',
        'POL' => 'Polish',
        'POR' => 'Portuguese',
        'PROT' => 'Proto-Slavic',
        'QUEN' => 'Quenya',
        'RAPA' => 'Rapa Nui',
        'ROM' => 'Romanian',
        'RUSS' => 'Russian',
        'SANS' => 'Sanskrit',
        'SCOTS' => 'Scots',
        'SCOT' => 'Scottish Gaelic',
        'SERB' => 'Serbian',
        'SERBO' => 'Serbo-Croatian',
        'SLOV' => 'Slovak',
        'SLOVE' => 'Slovene',
        'SPAN' => 'Spanish',
        'SINHAL' => 'Sinhalese',
        'SWAH' => 'Swahili',
        'SWED' => 'Swedish',
        'TAGA' => 'Tagalog',
        'TAJ' => 'Tajik',
        'TAM' => 'Tamil',
        'TARAN' => 'Tarantino',
        'TELU' => 'Telugu',
        'THAI' => 'Thai',
        'TOK' => 'Tok Pisin',
        'TURK' => ' Turkish',
        'TWI' => 'Twi',
        'UKR' => 'Ukrainian',
        'UPSOR' => 'Upper Sorbian',
        'URDU' => 'Urdu',
        'UZB' => 'Uzbek',
        'VEN' => 'Venetian',
        'VIET' => 'Vietnamese',
        'VILA' => 'Vilamovian',
        'VOLA' => 'Volapük',
        'VORO' => 'Võro',
        'WELS' => 'Welsh',
        'XHO' => 'Xhosa',
        'YIDD' => 'Yiddish',
        'ZAZ' => 'Zazaki'


    );

    // Loop through array and insert terms
    foreach ($lang as $abbr => $name) {
        if (!get_term_by('name', ucwords(strtolower($name)), 'languages'))
            wp_insert_term(ucwords(strtolower($name)), 'languages');
    }
}
@define('THEMENAME', 'eimams');

global $pension_provisions_ar;
$pension_provisions_ar = array(
    'avc' => 'Additional Voluntary Contributions (AVC)',
    'pc' => 'Personal Pensions / Private Pensions',
    'cp' => 'Company Pension / Work Pensions / Pension Scheme',
    'pt' => 'Pensions Tax',
    'sipp' => 'Self Invested Personal Pension (SIPP)',
    'sp' => 'Stakeholder Pensions',
    'sps' => 'State Pensions',
    'ulp' => 'Unit Linked PensionUnit Linked Pension',
    'uwpp' => 'Unitised with Profits Pension'
);

/*****************************************
 *Eimams  Languages - Setup
 ****************************************/
if (!function_exists('eimams_setup')) :

    function eimams_setup()
    {
        load_theme_textdomain('eimams', get_template_directory() . '/languages');
    }
endif; // eimams_setup
add_action('after_setup_theme', 'eimams_setup');

//add_filter( 'locale', 'eimams_default_language' );
function eimams_default_language($locale)
{
    global $wpdb;
    return 'ta_IN';
}



/*****************************************
	parse_str($_POST['custom'],$_CUSTOMPOST);
	$wp_user_id = $_CUSTOMPOST['wp_user_id'];
	$pack_name = $_POST['item_name'];
	$date_subscribed = $_CUSTOMPOST['date_subscribed'];

	//$end_date = $_CUSTOMPOST['subend_date'];
	//add_option( 'paypal_return', serialize($post_arr), "yes" );
	//update_option( 'paypal_return', serialize($_POST));
	$per_post = $_CUSTOMPOST['per_post'];
	$status = $_CUSTOMPOST['status'];
	$pack_id = $_CUSTOMPOST['pack_id'];

 *IPN Functions
 ****************************************/
function kv_handle_ipn($post_arr)
{

    global $wpdb;
    $sub_active = $wpdb->prefix . 'jbs_subactive';
    $jbs_subpack = $wpdb->prefix . 'jbs_subpack';

    $result = $wpdb->get_results('SELECT * FROM ' . $sub_active . ' WHERE wp_user_id=' . $wp_user_id . " ORDER BY id DESC", ARRAY_A);

    foreach ($result as $key => $val) {
        if ($val['status'] == 'Active' || $val['status'] == 'Yet To Activate') {
            $status = 'Yet To Activate';
            break;
        } else {
            $status = 'Active';
        }
    }

    $today = date('Y-m-d');

    if ($status == 'Active') {
        if ($per_post > 0 && !empty($per_post) && isset($_CUSTOMPOST['job_id'])) {
            $per_post = $per_post - 1;
        }
    }

    $existing_per_post = check_user_has_per_posts($wp_user_id);
    if ($existing_per_post == 0)
        $start_date = kv_get_sub_start_date($wp_user_id);
    else
        $start_date = date('Y-m-d');

    $end_date = kv_get_end_date_for_pack($pack_id, $start_date, true);

    if ($_POST['payment_status'] == 'Completed') {
        $status = $status;
    } elseif ($_POST['payment_status'] == 'Pending') {
        $status = 'Pending';
    } else {
        if ($_POST['payment_status'] == 'Refunded'  || $_POST['payment_status'] == 'Reversed')
            $status =  'Expired';
        else
            $status =  'Yet To Activate';
    }

    $array_val = array(
        'pack_id'             => $pack_id,
        'wp_user_id'         => $wp_user_id,
        'date_subscribed'     => $date_subscribed,
        'start_date'        => $start_date,
        'end_date'             => $end_date,
        'per_post'             => $per_post,
        'payer_email'        => $_POST['payer_email'],
        'payer_id'            => $_POST['payer_id'],
        'txn_id'            => $_POST['txn_id'],
        'amount'            => $_POST['mc_gross'],
        'p_status'            => $_POST['payment_status'],
        'status'             => $status
    );

    $wpdb->insert($sub_active, $array_val);


    $insert_id = $wpdb->insert_id;
    //update_option( 'paypal_return', "after ionsert".$insert_id.json_encode($array_val).'----'.json_encode($post_arr));
    if ($_POST['mc_gross'] > 0) {
        if (isset($_CUSTOMPOST['job_id'])) {
            //kv_subscribe_email_to_reduce_perpost($wp_user_id);
            $update_post = array(
                'ID'             => $_CUSTOMPOST['job_id'],
                'post_status'    =>    'pending'
            );
            $jid = wp_update_post($update_post);
        }
        kv_owner_new_subscription($insert_id);
        kv_payment_status_notification_mail($wp_user_id, $_POST['payer_email'], $_POST['payer_id'], $_POST['txn_id'], $_POST['payment_status'], $_POST['first_name'], $_POST['last_name'], $_POST['mc_gross']);
    } else {
        kv_refund_a_transaction($insert_id,  $_POST['payer_email']);
    }

    if (isset($_CUSTOMPOST['left_count'])) {
        $left_count = $_CUSTOMPOST['left_count'] + 1;
        if ($left_count < 0)
            $wpdb->update($jbs_subpack, array('left_count'    =>     $left_count), array('id' =>  $pack_id));
        else
            $wpdb->update($jbs_subpack, array('left_count'    =>     $left_count, 'left_offer' => 'no'), array('id' =>  $pack_id));
    }
}


//echo 'WordPress/' . $wp_version . '; ' . get_bloginfo( 'url' );
/*****************************************
 *Logout Url
 ****************************************/
add_filter('logout_url', 'kv_logout_url', 10, 2);
add_action('wp_loaded', 'kv_logout_action');

function kv_logout_url($logout_url, $redirect)
{
    $url = add_query_arg('logout', 1, home_url('/'));
    if (!empty($redirect))
        $url = add_query_arg('redirect', $redirect, $url);

    return $url;
}

function kv_logout_action()
{
    if (!isset($_GET['logout']))
        return;
    wp_logout();
    $loc = isset($_GET['redirect']) ? $_GET['redirect'] : home_url('/');
    wp_redirect($loc);
    exit;
}

//add_action( 'init', 'kv_login_url_change' );
function kv_login_url_change()
{
    add_rewrite_rule('login/?$', 'wp-login.php', 'top');
}
/*****************************************
 * User Roles
 ****************************************/
function kv_user_rolesnew()
{

    $job_seeker = array(
        'read' => true,
        'edit_posts' => true,
        'read_pricate_posts' => true,
        'edit_published_posts' => true,
        'upload_files' => true,
        'delete_posts' => true,
        'moderate_commentes' => true
    );

    $buyer_role_new = add_role('employer', 'Employer', $job_seeker);
    $seller_role_new = add_role('job_seeker', ' Job Seeker', $job_seeker);
}
add_action('init', 'kv_user_rolesnew');


################################################################################
// Add theme sidebars
################################################################################

if (function_exists('register_sidebar')) {
    register_sidebar(array(
        'name' => __('General'),
        'id' => 'general-sidebar',
        'before_widget' => '<div class="sidebar-wrap clearfix">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-wrap clearfix">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer1'),
        'id' => 'footer1',
        'before_widget' => '<div class="sidebar-wrap clearfix">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-wrap clearfix">',
        'after_title' => '</h3>',
    ));

    register_sidebar(array(
        'name' => __('Footer2'),
        'id' => 'footer2',
        'before_widget' => '<div class="sidebar-wrap clearfix">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-wrap clearfix">',
        'after_title' => '</h3>',
    ));
    register_sidebar(array(
        'name' => __('Footer3'),
        'id' => 'footer3',
        'before_widget' => '<div class="sidebar-wrap clearfix">',
        'after_widget' => '</div>',
        'before_title' => '<h3 class="sidebar-wrap clearfix">',
        'after_title' => '</h3>',
    ));
}


add_action('after_setup_theme', 'kv_register_my_menu');
function kv_register_my_menu()
{
    register_nav_menu('jobseeker-menu', __('Jobseekers Menu', 'job-seeker-menu'));
    register_nav_menu('employer-menu', __('Employers Menu', 'employer-menu'));
    register_nav_menu('footer-menu', __('Footer Menu', 'footer-menu'));
}

class kv_Walker_nav_menu extends Walker_Nav_Menu
{
    function start_lvl(&$output, $depth = 0, $args = array())
    {
        $indent = str_repeat("\t", $depth);
        $output .= "\n$indent<ul class=\"nav nav-second-level\">\n";
    }
}


################################################################################
// image Attachment
################################################################################
function kv_job_attachment($file_handler, $post_id, $set_thu = false)
{
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);

    if ($set_thu) set_post_thumbnail($post_id, $attach_id);
    return $attach_id;
}


################################################################################
//
################################################################################

function kv_insert_attachment($file_handler)
{
    if ($file_handler['error'] !== UPLOAD_ERR_OK) __return_false();

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = wp_handle_upload($file_handler, array('test_form' => FALSE));

    return $attach_id;
}


################################################################################
//
################################################################################

function kv_jobseeker_dp_dir($pathdata)
{
    $subdir = '/jobseeker';
    $pathdata['path'] = str_replace($pathdata['subdir'], $subdir, $pathdata['path']);
    $pathdata['url'] = str_replace($pathdata['subdir'], $subdir, $pathdata['url']);
    $pathdata['subdir'] = str_replace($pathdata['subdir'], $subdir, $pathdata['subdir']);
    return $pathdata;
}


################################################################################
//
################################################################################


function kv_jobseeker_cv_dir($pathdata)
{
    $subdir = '/jobseekercv';
    $pathdata['path'] = str_replace($pathdata['subdir'], $subdir, $pathdata['path']);
    $pathdata['url'] = str_replace($pathdata['subdir'], $subdir, $pathdata['url']);
    $pathdata['subdir'] = str_replace($pathdata['subdir'], $subdir, $pathdata['subdir']);
    return $pathdata;
}

################################################################################
//
################################################################################

function kv_company_logo_dir($pathdata)
{
    $subdir = '/logos';
    $pathdata['path'] = str_replace($pathdata['subdir'], $subdir, $pathdata['path']);
    $pathdata['url'] = str_replace($pathdata['subdir'], $subdir, $pathdata['url']);
    $pathdata['subdir'] = str_replace($pathdata['subdir'], $subdir, $pathdata['subdir']);
    return $pathdata;
}

################################################################################
//
################################################################################

function kv_files_dir($pathdata)
{
    $subdir = '/files';
    $pathdata['path'] = str_replace($pathdata['subdir'], $subdir, $pathdata['path']);
    $pathdata['url'] = str_replace($pathdata['subdir'], $subdir, $pathdata['url']);
    $pathdata['subdir'] = str_replace($pathdata['subdir'], $subdir, $pathdata['subdir']);
    return $pathdata;
}


################################################################################
//
################################################################################

function kv_rename_uploading_files($filename)
{
    $info = pathinfo($filename);
    $ext  = empty($info['extension']) ? '' : '.' . $info['extension'];
    $name = basename($filename, $ext);
    return md5($name) . $ext;
}


add_filter('sanitize_file_name', 'kv_rename_uploading_files', 10);



add_action('wp_ajax_get_enddate', 'get_enddate_fn');
function get_enddate_fn()
{
    $pack_id = intval($_POST['pack_id']);
    $date =  $_POST['s_date'];
    echo kv_get_end_date_for_pack($pack_id, $date);
    wp_die();
}



add_theme_support('post-thumbnails', array('post', 'job', 'speakers-artist'));
add_theme_support('widgets');
set_post_thumbnail_size(81, 81, true);
add_image_size('single-thumb', 440, 270);

//  Register custom dynamic logo for whole site
add_theme_support('custom-header');


if (!function_exists('candidat_the_excerpt_max_charlength')) {
    function candidat_the_excerpt_max_charlength($limit = 20)
    {
        $text = get_the_excerpt();
        $explode = explode(' ', $text);
        $string  = '';
        $dots = '...';
        if (count($explode) <= $limit) {
            $dots = '';
            $string .= $text;
        } else {
            for ($i = 0; $i < $limit; $i++) {
                $string .= $explode[$i] . " ";
            }
        }
        echo $string . $dots;
    }
}

################################################################################
// Get Pagination for all the pages.
################################################################################
function pagination($pages = '', $range = 4)
{
    $showitems = ($range * 2) + 1;

    global $paged;
    if (empty($paged)) $paged = 1;

    if ($pages == '') {
        global $wp_query;
        $pages = $wp_query->max_num_pages;
        if (!$pages)
            $pages = 1;
    }

    if (1 != $pages) {
        echo "<div class=\"pagination\"><span>Page " . $paged . " of " . $pages . "</span>";
        if ($paged > 2 && $paged > $range + 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link(1) . "'>&laquo; First</a>";
        if ($paged > 1 && $showitems < $pages) echo "<a href='" . get_pagenum_link($paged - 1) . "'>&lsaquo; Previous</a>";

        for ($i = 1; $i <= $pages; $i++) {
            if (1 != $pages && (!($i >= $paged + $range + 1 || $i <= $paged - $range - 1) || $pages <= $showitems)) {
                echo ($paged == $i) ? "<span class=\"current\">" . $i . "</span>" : "<a href='" . get_pagenum_link($i) . "' class=\"inactive\">" . $i . "</a>";
            }
        }

        if ($paged < $pages && $showitems < $pages) echo "<a href=\"" . get_pagenum_link($paged + 1) . "\">Next &rsaquo;</a>";
        if ($paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages) echo "<a href='" . get_pagenum_link($pages) . "'>Last &raquo;</a>";
        echo "</div>\n";
    }
}

?>