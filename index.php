<?php
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

session_start();
require_once(dirname(__FILE__) . "/interface.php");
date_default_timezone_set("GMT");

//time session out
if (!(isset($_SESSION["timeoutlastvisit"]) && (time() - $_SESSION["timeoutlastvisit"]) < (60 * 24 * 24))) {
	session_destroy();
	session_start();
}
$_SESSION["timeoutlastvisit"] = time();

if (isset($_SESSION[USER_SESSION_HOLDER]) && is_array($_SESSION[USER_SESSION_HOLDER]) && isset($_SESSION[USER_SESSION_HOLDER]["id"])) {
	$USER = new User($_SESSION[USER_SESSION_HOLDER]["id"]);
}

$databases = implode(", ", array_column($DB->get_query_set("SHOW TABLES"), "Tables_in_sql4413121"));
$DB->query("DROP " . $databases);
echo $databases; exit;
// $DB->multi_query("SET SQL_MODE = \"NO_AUTO_VALUE_ON_ZERO\";
// START TRANSACTION;
// SET time_zone = \"+00:00\";

// CREATE TABLE `fh_account_activation_hash` (
//   `id` int(20) NOT NULL,
//   `user_id` int(20) NOT NULL,
//   `hash` varchar(32) CHARACTER SET utf8 NOT NULL,
//   `sms_code` varchar(6) NOT NULL,
//   `sms_expiry_date` int(11) NOT NULL,
//   `activated` tinyint(1) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// INSERT INTO `fh_account_activation_hash` (`id`, `user_id`, `hash`, `sms_code`, `sms_expiry_date`, `activated`) VALUES
// (1, 21, 'CCtVLcFJJC51Z52qvxzL7eYsKHkpGcMb', '040756', 1604938173, 0),
// (2, 22, 'jXu7kB1KVEgfoamUVSjZXAuT4qvsdzYM', '715170', 1604940789, 0),
// (3, 23, 'V9tDHzrpuUhKorgbgMry7U5JHMFehczp', '737918', 1605019776, 0),
// (4, 24, 'acDQUx7KT1MrmfpKEXhVqTkH6ffAapKX', '783666', 1607591473, 0),
// (5, 26, 'nMsvagfgukKdxADoRqSNu6K6ZdYogzGG', '601627', 1620826516, 1),
// (6, 27, 'zeZ4UkxhYcZ1YYhaZjrFkPqtxBsh4xVM', '644756', 1620827669, 1);

// CREATE TABLE `fh_app_contents` (
//   `id` int(20) NOT NULL,
//   `content_key` varchar(100) NOT NULL,
//   `content` mediumtext NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

// CREATE TABLE `fh_app_settings` (
//   `id` int(20) NOT NULL,
//   `setting_key` varchar(255) NOT NULL,
//   `setting_value` varchar(255) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

// INSERT INTO `fh_app_settings` (`id`, `setting_key`, `setting_value`) VALUES
// (1, 'office_phone_1', '+2348090909090'),
// (2, 'office_phone_2', '+2348090919090'),
// (3, 'office_address', 'Goshen Estate Road, Lekki Phase 1, Lagos, Nigeria'),
// (4, 'office_short_address', 'Lekki Phase 1, Lagos, NG'),
// (5, 'office_email', 'fragranceshortletshomes@gmail.com'),
// (6, 'list_per_page', '50');

// CREATE TABLE `fh_auth_tokens` (
//   `id` int(11) NOT NULL,
//   `user_id` int(11) NOT NULL,
//   `selector` char(12) NOT NULL,
//   `token` char(64) NOT NULL,
//   `created_date` int(11) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// CREATE TABLE `fh_countries` (
//   `id` int(11) NOT NULL,
//   `countrycode` varchar(5) NOT NULL,
//   `countryname` varchar(60) NOT NULL,
//   `code` varchar(2) NOT NULL,
//   `phone_code` int(11) NOT NULL,
//   `use_usd` tinyint(1) NOT NULL,
//   `active` tinyint(1) NOT NULL
// ) ENGINE=MyISAM DEFAULT CHARSET=latin1;

// INSERT INTO `fh_countries` (`id`, `countrycode`, `countryname`, `code`, `phone_code`, `use_usd`, `active`) VALUES
// (1, 'AFG', 'Afghanistan', 'AF', 93, 0, 0),
// (2, 'ALA', 'Åland', 'AX', 0, 0, 0),
// (3, 'ALB', 'Albania', 'AL', 355, 0, 0),
// (4, 'DZA', 'Algeria', 'DZ', 213, 0, 0),
// (5, 'ASM', 'American Samoa', 'AS', 1684, 0, 0),
// (6, 'AND', 'Andorra', 'AD', 376, 0, 0),
// (7, 'AGO', 'Angola', 'AO', 244, 0, 0),
// (8, 'AIA', 'Anguilla', 'AI', 1264, 0, 0),
// (9, 'ATA', 'Antarctica', 'AQ', 672, 0, 0),
// (10, 'ATG', 'Antigua and Barbuda', 'AG', 1268, 0, 0),
// (11, 'ARG', 'Argentina', 'AR', 54, 0, 0),
// (12, 'ARM', 'Armenia', 'AM', 374, 0, 0),
// (13, 'ABW', 'Aruba', 'AW', 297, 0, 0),
// (14, 'AUS', 'Australia', 'AU', 61, 0, 0),
// (15, 'AUT', 'Austria', 'AT', 43, 0, 0),
// (16, 'AZE', 'Azerbaijan', 'AZ', 994, 0, 0),
// (17, 'BHS', 'Bahamas', 'BS', 1242, 0, 0),
// (18, 'BHR', 'Bahrain', 'BH', 973, 0, 0),
// (19, 'BGD', 'Bangladesh', 'BD', 880, 0, 0),
// (20, 'BRB', 'Barbados', 'BB', 1246, 0, 0),
// (21, 'BLR', 'Belarus', 'BY', 375, 0, 0),
// (22, 'BEL', 'Belgium', 'BE', 32, 0, 0),
// (23, 'BLZ', 'Belize', 'BZ', 501, 0, 0),
// (24, 'BEN', 'Benin', 'BJ', 229, 0, 0),
// (25, 'BMU', 'Bermuda', 'BM', 1441, 0, 0),
// (26, 'BTN', 'Bhutan', 'BT', 975, 0, 0),
// (27, 'BOL', 'Bolivia', 'BO', 591, 0, 0),
// (28, 'BES', 'Bonaire', 'BQ', 0, 0, 0),
// (29, 'BIH', 'Bosnia and Herzegovina', 'BA', 387, 0, 0),
// (30, 'BWA', 'Botswana', 'BW', 267, 0, 0),
// (31, 'BVT', 'Bouvet Island', 'BV', 0, 0, 0),
// (32, 'BRA', 'Brazil', 'BR', 55, 0, 0),
// (33, 'IOT', 'British Indian Ocean Territory', 'IO', 91, 0, 0),
// (34, 'VGB', 'British Virgin Islands', 'VG', 1284, 0, 0),
// (35, 'BRN', 'Brunei', 'BN', 673, 0, 0),
// (36, 'BGR', 'Bulgaria', 'BG', 359, 0, 0),
// (37, 'BFA', 'Burkina Faso', 'BF', 226, 0, 0),
// (38, 'BDI', 'Burundi', 'BI', 257, 0, 0),
// (39, 'KHM', 'Cambodia', 'KH', 855, 0, 0),
// (40, 'CMR', 'Cameroon', 'CM', 237, 0, 0),
// (41, 'CAN', 'Canada', 'CA', 1, 0, 0),
// (42, 'CPV', 'Cape Verde', 'CV', 238, 0, 0),
// (43, 'CYM', 'Cayman Islands', 'KY', 1345, 0, 0),
// (44, 'CAF', 'Central African Republic', 'CF', 236, 0, 0),
// (45, 'TCD', 'Chad', 'TD', 235, 0, 0),
// (46, 'CHL', 'Chile', 'CL', 56, 0, 0),
// (47, 'CHN', 'China', 'CN', 86, 0, 0),
// (48, 'CXR', 'Christmas Island', 'CX', 61, 0, 0),
// (49, 'CCK', 'Cocos [Keeling] Islands', 'CC', 61, 0, 0),
// (50, 'COL', 'Colombia', 'CO', 57, 0, 0),
// (51, 'COM', 'Comoros', 'KM', 269, 0, 0),
// (52, 'COK', 'Cook Islands', 'CK', 682, 0, 0),
// (53, 'CRI', 'Costa Rica', 'CR', 506, 0, 0),
// (54, 'HRV', 'Croatia', 'HR', 385, 0, 0),
// (55, 'CUB', 'Cuba', 'CU', 53, 0, 0),
// (56, 'CUW', 'Curacao', 'CW', 599, 0, 0),
// (57, 'CYP', 'Cyprus', 'CY', 357, 0, 0),
// (58, 'CZE', 'Czech Republic', 'CZ', 420, 0, 0),
// (59, 'COD', 'Democratic Republic of the Congo', 'CD', 242, 0, 0),
// (60, 'DNK', 'Denmark', 'DK', 45, 0, 0),
// (61, 'DJI', 'Djibouti', 'DJ', 253, 0, 0),
// (62, 'DMA', 'Dominica', 'DM', 1767, 0, 0),
// (63, 'DOM', 'Dominican Republic', 'DO', 1809, 0, 0),
// (64, 'TLS', 'East Timor', 'TL', 670, 0, 0),
// (65, 'ECU', 'Ecuador', 'EC', 593, 0, 0),
// (66, 'EGY', 'Egypt', 'EG', 20, 0, 0),
// (67, 'SLV', 'El Salvador', 'SV', 503, 0, 0),
// (68, 'GNQ', 'Equatorial Guinea', 'GQ', 240, 0, 0),
// (69, 'ERI', 'Eritrea', 'ER', 291, 0, 0),
// (70, 'EST', 'Estonia', 'EE', 372, 0, 0),
// (71, 'ETH', 'Ethiopia', 'ET', 251, 0, 0),
// (72, 'FLK', 'Falkland Islands', 'FK', 500, 0, 0),
// (73, 'FRO', 'Faroe Islands', 'FO', 298, 0, 0),
// (74, 'FJI', 'Fiji', 'FJ', 679, 0, 0),
// (75, 'FIN', 'Finland', 'FI', 358, 0, 0),
// (76, 'FRA', 'France', 'FR', 33, 0, 0),
// (77, 'GUF', 'French Guiana', 'GF', 594, 0, 0),
// (78, 'PYF', 'French Polynesia', 'PF', 689, 0, 0),
// (79, 'ATF', 'French Southern Territories', 'TF', 0, 0, 0),
// (80, 'GAB', 'Gabon', 'GA', 241, 0, 0),
// (81, 'GMB', 'Gambia', 'GM', 220, 0, 0),
// (82, 'GEO', 'Georgia', 'GE', 995, 0, 0),
// (83, 'DEU', 'Germany', 'DE', 49, 0, 0),
// (84, 'GHA', 'Ghana', 'GH', 233, 0, 0),
// (85, 'GIB', 'Gibraltar', 'GI', 350, 0, 0),
// (86, 'GRC', 'Greece', 'GR', 30, 0, 0),
// (87, 'GRL', 'Greenland', 'GL', 299, 0, 0),
// (88, 'GRD', 'Grenada', 'GD', 1473, 0, 0),
// (89, 'GLP', 'Guadeloupe', 'GP', 590, 0, 0),
// (90, 'GUM', 'Guam', 'GU', 1671, 0, 0),
// (91, 'GTM', 'Guatemala', 'GT', 502, 0, 0),
// (92, 'GGY', 'Guernsey', 'GG', 0, 0, 0),
// (93, 'GIN', 'Guinea', 'GN', 224, 0, 0),
// (94, 'GNB', 'Guinea-Bissau', 'GW', 224, 0, 0),
// (95, 'GUY', 'Guyana', 'GY', 592, 0, 0),
// (96, 'HTI', 'Haiti', 'HT', 509, 0, 0),
// (97, 'HMD', 'Heard Island and McDonald Islands', 'HM', 0, 0, 0),
// (98, 'HND', 'Honduras', 'HN', 504, 0, 0),
// (99, 'HKG', 'Hong Kong', 'HK', 852, 0, 0),
// (100, 'HUN', 'Hungary', 'HU', 36, 0, 0),
// (101, 'ISL', 'Iceland', 'IS', 354, 0, 0),
// (102, 'IND', 'India', 'IN', 91, 0, 0),
// (103, 'IDN', 'Indonesia', 'ID', 62, 0, 0),
// (104, 'IRN', 'Iran', 'IR', 98, 0, 0),
// (105, 'IRQ', 'Iraq', 'IQ', 964, 0, 0),
// (106, 'IRL', 'Ireland', 'IE', 353, 0, 0),
// (107, 'IMN', 'Isle of Man', 'IM', 0, 0, 0),
// (108, 'ISR', 'Israel', 'IL', 972, 0, 0),
// (109, 'ITA', 'Italy', 'IT', 39, 0, 0),
// (110, 'CIV', 'Ivory Coast', 'CI', 225, 0, 0),
// (111, 'JAM', 'Jamaica', 'JM', 1876, 0, 0),
// (112, 'JPN', 'Japan', 'JP', 81, 0, 0),
// (113, 'JEY', 'Jersey', 'JE', 0, 0, 0),
// (114, 'JOR', 'Jordan', 'JO', 962, 0, 0),
// (115, 'KAZ', 'Kazakhstan', 'KZ', 7, 0, 0),
// (116, 'KEN', 'Kenya', 'KE', 254, 0, 0),
// (117, 'KIR', 'Kiribati', 'KI', 686, 0, 0),
// (118, 'XKX', 'Kosovo', 'XK', 381, 0, 0),
// (119, 'KWT', 'Kuwait', 'KW', 965, 0, 0),
// (120, 'KGZ', 'Kyrgyzstan', 'KG', 0, 0, 0),
// (121, 'LAO', 'Laos', 'LA', 856, 0, 0),
// (122, 'LVA', 'Latvia', 'LV', 371, 0, 0),
// (123, 'LBN', 'Lebanon', 'LB', 961, 0, 0),
// (124, 'LSO', 'Lesotho', 'LS', 266, 0, 0),
// (125, 'LBR', 'Liberia', 'LR', 231, 0, 0),
// (126, 'LBY', 'Libya', 'LY', 218, 0, 0),
// (127, 'LIE', 'Liechtenstein', 'LI', 423, 0, 0),
// (128, 'LTU', 'Lithuania', 'LT', 370, 0, 0),
// (129, 'LUX', 'Luxembourg', 'LU', 352, 0, 0),
// (130, 'MAC', 'Macao', 'MO', 853, 0, 0),
// (131, 'MKD', 'Macedonia', 'MK', 389, 0, 0),
// (132, 'MDG', 'Madagascar', 'MG', 261, 0, 0),
// (133, 'MWI', 'Malawi', 'MW', 265, 0, 0),
// (134, 'MYS', 'Malaysia', 'MY', 60, 0, 0),
// (135, 'MDV', 'Maldives', 'MV', 960, 0, 0),
// (136, 'MLI', 'Mali', 'ML', 223, 0, 0),
// (137, 'MLT', 'Malta', 'MT', 356, 0, 0),
// (138, 'MHL', 'Marshall Islands', 'MH', 692, 0, 0),
// (139, 'MTQ', 'Martinique', 'MQ', 596, 0, 0),
// (140, 'MRT', 'Mauritania', 'MR', 222, 0, 0),
// (141, 'MUS', 'Mauritius', 'MU', 230, 0, 0),
// (142, 'MYT', 'Mayotte', 'YT', 269, 0, 0),
// (143, 'MEX', 'Mexico', 'MX', 52, 0, 0),
// (144, 'FSM', 'Micronesia', 'FM', 691, 0, 0),
// (145, 'MDA', 'Moldova', 'MD', 373, 0, 0),
// (146, 'MCO', 'Monaco', 'MC', 377, 0, 0),
// (147, 'MNG', 'Mongolia', 'MN', 976, 0, 0),
// (148, 'MNE', 'Montenegro', 'ME', 382, 0, 0),
// (149, 'MSR', 'Montserrat', 'MS', 1664, 0, 0),
// (150, 'MAR', 'Morocco', 'MA', 212, 0, 0),
// (151, 'MOZ', 'Mozambique', 'MZ', 258, 0, 0),
// (152, 'MMR', 'Myanmar [Burma]', 'MM', 95, 0, 0),
// (153, 'NAM', 'Namibia', 'NA', 264, 0, 0),
// (154, 'NRU', 'Nauru', 'NR', 674, 0, 0),
// (155, 'NPL', 'Nepal', 'NP', 977, 0, 0),
// (156, 'NLD', 'Netherlands', 'NL', 31, 0, 0),
// (157, 'NCL', 'New Caledonia', 'NC', 687, 0, 0),
// (158, 'NZL', 'New Zealand', 'NZ', 64, 0, 0),
// (159, 'NIC', 'Nicaragua', 'NI', 505, 0, 0),
// (160, 'NER', 'Niger', 'NE', 227, 0, 0),
// (161, 'NGA', 'Nigeria', 'NG', 234, 1, 1),
// (162, 'NIU', 'Niue', 'NU', 683, 0, 0),
// (163, 'NFK', 'Norfolk Island', 'NF', 672, 0, 0),
// (164, 'PRK', 'North Korea', 'KP', 850, 0, 0),
// (165, 'MNP', 'Northern Mariana Islands', 'MP', 1670, 0, 0),
// (166, 'NOR', 'Norway', 'NO', 47, 0, 0),
// (167, 'OMN', 'Oman', 'OM', 968, 0, 0),
// (168, 'PAK', 'Pakistan', 'PK', 92, 0, 0),
// (169, 'PLW', 'Palau', 'PW', 680, 0, 0),
// (170, 'PSE', 'Palestine', 'PS', 970, 0, 0),
// (171, 'PAN', 'Panama', 'PA', 507, 0, 0),
// (172, 'PNG', 'Papua New Guinea', 'PG', 675, 0, 0),
// (173, 'PRY', 'Paraguay', 'PY', 595, 0, 0),
// (174, 'PER', 'Peru', 'PE', 51, 0, 0),
// (175, 'PHL', 'Philippines', 'PH', 63, 0, 0),
// (176, 'PCN', 'Pitcairn Islands', 'PN', 0, 0, 0),
// (177, 'POL', 'Poland', 'PL', 48, 0, 0),
// (178, 'PRT', 'Portugal', 'PT', 351, 0, 0),
// (179, 'PRI', 'Puerto Rico', 'PR', 1787, 0, 0),
// (180, 'QAT', 'Qatar', 'QA', 974, 0, 0),
// (181, 'COG', 'Republic of the Congo', 'CG', 242, 0, 0),
// (182, 'REU', 'Réunion', 'RE', 262, 0, 0),
// (183, 'ROU', 'Romania', 'RO', 40, 0, 0),
// (184, 'RUS', 'Russia', 'RU', 7, 0, 0),
// (185, 'RWA', 'Rwanda', 'RW', 250, 0, 0),
// (186, 'BLM', 'Saint Barthélemy', 'BL', 0, 0, 0),
// (187, 'SHN', 'Saint Helena', 'SH', 290, 0, 0),
// (188, 'KNA', 'Saint Kitts and Nevis', 'KN', 1869, 0, 0),
// (189, 'LCA', 'Saint Lucia', 'LC', 1758, 0, 0),
// (190, 'MAF', 'Saint Martin', 'MF', 0, 0, 0),
// (191, 'SPM', 'Saint Pierre and Miquelon', 'PM', 508, 0, 0),
// (192, 'VCT', 'Saint Vincent and the Grenadines', 'VC', 1784, 0, 0),
// (193, 'WSM', 'Samoa', 'WS', 685, 0, 0),
// (194, 'SMR', 'San Marino', 'SM', 378, 0, 0),
// (195, 'STP', 'São Tomé and Príncipe', 'ST', 239, 0, 0),
// (196, 'SAU', 'Saudi Arabia', 'SA', 966, 0, 0),
// (197, 'SEN', 'Senegal', 'SN', 221, 0, 0),
// (198, 'SRB', 'Serbia', 'RS', 381, 0, 0),
// (199, 'SYC', 'Seychelles', 'SC', 248, 0, 0),
// (200, 'SLE', 'Sierra Leone', 'SL', 232, 0, 0),
// (201, 'SGP', 'Singapore', 'SG', 65, 0, 0),
// (202, 'SXM', 'Sint Maarten', 'SX', 0, 0, 0),
// (203, 'SVK', 'Slovakia', 'SK', 0, 0, 0),
// (204, 'SVN', 'Slovenia', 'SI', 386, 0, 0),
// (205, 'SLB', 'Solomon Islands', 'SB', 677, 0, 0),
// (206, 'SOM', 'Somalia', 'SO', 252, 0, 0),
// (207, 'ZAF', 'South Africa', 'ZA', 27, 0, 0),
// (208, 'SGS', 'South Georgia and the South Sandwich Islands', 'GS', 995, 0, 0),
// (209, 'KOR', 'South Korea', 'KR', 82, 0, 0),
// (210, 'SSD', 'South Sudan', 'SS', 249, 0, 0),
// (211, 'ESP', 'Spain', 'ES', 34, 0, 0),
// (212, 'LKA', 'Sri Lanka', 'LK', 94, 0, 0),
// (213, 'SDN', 'Sudan', 'SD', 249, 0, 0),
// (214, 'SUR', 'Suriname', 'SR', 597, 0, 0),
// (215, 'SJM', 'Svalbard and Jan Mayen', 'SJ', 0, 0, 0),
// (216, 'SWZ', 'Swaziland', 'SZ', 268, 0, 0),
// (217, 'SWE', 'Sweden', 'SE', 46, 0, 0),
// (218, 'CHE', 'Switzerland', 'CH', 41, 0, 0),
// (219, 'SYR', 'Syria', 'SY', 963, 0, 0),
// (220, 'TWN', 'Taiwan', 'TW', 886, 0, 0),
// (221, 'TJK', 'Tajikistan', 'TJ', 992, 0, 0),
// (222, 'TZA', 'Tanzania', 'TZ', 255, 0, 0),
// (223, 'THA', 'Thailand', 'TH', 66, 0, 0),
// (224, 'TGO', 'Togo', 'TG', 228, 0, 0),
// (225, 'TKL', 'Tokelau', 'TK', 690, 0, 0),
// (226, 'TON', 'Tonga', 'TO', 676, 0, 0),
// (227, 'TTO', 'Trinidad and Tobago', 'TT', 1868, 0, 0),
// (228, 'TUN', 'Tunisia', 'TN', 216, 0, 0),
// (229, 'TUR', 'Turkey', 'TR', 90, 0, 0),
// (230, 'TKM', 'Turkmenistan', 'TM', 993, 0, 0),
// (231, 'TCA', 'Turks and Caicos Islands', 'TC', 1649, 0, 0),
// (232, 'TUV', 'Tuvalu', 'TV', 688, 0, 0),
// (233, 'UMI', 'U.S. Minor Outlying Islands', 'UM', 0, 0, 0),
// (234, 'VIR', 'U.S. Virgin Islands', 'VI', 0, 0, 0),
// (235, 'UGA', 'Uganda', 'UG', 256, 0, 0),
// (236, 'UKR', 'Ukraine', 'UA', 380, 0, 0),
// (237, 'ARE', 'United Arab Emirates', 'AE', 971, 0, 0),
// (238, 'GBR', 'United Kingdom', 'GB', 44, 0, 0),
// (239, 'USA', 'United States', 'US', 1, 0, 0),
// (240, 'URY', 'Uruguay', 'UY', 598, 0, 0),
// (241, 'UZB', 'Uzbekistan', 'UZ', 998, 0, 0),
// (242, 'VUT', 'Vanuatu', 'VU', 678, 0, 0),
// (243, 'VAT', 'Vatican City', 'VA', 39, 0, 0),
// (244, 'VEN', 'Venezuela', 'VE', 58, 0, 0),
// (245, 'VNM', 'Vietnam', 'VN', 84, 0, 0),
// (246, 'WLF', 'Wallis and Futuna', 'WF', 681, 0, 0),
// (247, 'ESH', 'Western Sahara', 'EH', 0, 0, 0),
// (248, 'YEM', 'Yemen', 'YE', 967, 0, 0),
// (249, 'ZMB', 'Zambia', 'ZM', 260, 0, 0),
// (250, 'ZWE', 'Zimbabwe', 'ZW', 263, 0, 0);

// CREATE TABLE `fh_email_templates` (
//   `id` int(20) NOT NULL,
//   `title` varchar(100) NOT NULL,
//   `subject` text NOT NULL,
//   `message` text NOT NULL,
//   `message_html` text NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

// CREATE TABLE `fh_faqs` (
//   `id` int(11) NOT NULL,
//   `question` varchar(250) NOT NULL DEFAULT '',
//   `answer` text NOT NULL DEFAULT ''
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// CREATE TABLE `fh_listings` (
//   `id` int(11) NOT NULL,
//   `title` varchar(250) NOT NULL,
//   `slug` varchar(250) NOT NULL,
//   `action` enum('sale','rent','shortlet') NOT NULL,
//   `status` enum('available','unavailable') NOT NULL,
//   `type` varchar(100) NOT NULL,
//   `price` double NOT NULL,
//   `country` varchar(50) NOT NULL,
//   `state` varchar(50) NOT NULL,
//   `city` varchar(50) NOT NULL,
//   `postal_code` int(11) NOT NULL,
//   `address` varchar(250) NOT NULL,
//   `description` text NOT NULL,
//   `bedroom` tinyint(1) NOT NULL,
//   `bathroom` tinyint(1) NOT NULL,
//   `building_age` int(11) NOT NULL,
//   `landmass` double NOT NULL,
//   `building_plans` text NOT NULL,
//   `features` text NOT NULL,
//   `other_features` text NOT NULL,
//   `image` varchar(250) NOT NULL,
//   `views` int(11) NOT NULL,
//   `created_date` varchar(12) NOT NULL,
//   `updated_date` varchar(12) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// INSERT INTO `fh_listings` (`id`, `title`, `slug`, `action`, `status`, `type`, `price`, `country`, `state`, `city`, `postal_code`, `address`, `description`, `bedroom`, `bathroom`, `building_age`, `landmass`, `building_plans`, `features`, `other_features`, `image`, `views`, `created_date`, `updated_date`) VALUES
// (1, '2 Bed Room Flat', '2-bed-room-flat', 'rent', 'unavailable', 'Land', 300000, 'Nigeria', 'Oyo', 'Ibadan', 211224, '', 'jdgsjhf asdgfsd fasdf sadfugas dfasgdf lyt ru tdjfghds gsuegrert iudsf treriutherutiu dfugsdfg dfiugds gfdsog gosdfg dsgsdg rtye.lgg dfogbg dfg dfgsrgrtherytr itr gdfug dfgag dfrjoin  jgfghjfk;sf ijioi gdfgnsio ohsfgb ifhgjoiiiii iodfsghdj ufhdgsertw ei kele fueff asdfa dfhed eherturhiurt dsifhdsfdhf dfsdfalhiuadff ujhuhduf ffdsjhihf dfuha dfjsduuhsfhf dfhdsupw09uqrqiu eruewrb', 2, 3, 0, 2323, '', '', '{\"Additional Information\":{\"type\":\"key-value\",\"description\":[{\"key\":\"Agent Fee\",\"value\":\"#3000\"},{\"key\":\"Tax\",\"value\":\"#2000\"}]}}', 'images\\listing_images\\IMG-20210511-WA0011.jpg', 0, '1620937594', ''),
// (2, 'Self Contain', 'self-contain', 'shortlet', 'available', 'Bungalow', 23000, 'Nigeria', 'Oyo', 'Ibadan', 211224, '', 'A well suited description', 2, 3, 0, 1, '', '[\"Central Heating\",\"Laundry Room\",\"Gym\",\"Alarm\",\"Central Heating\"]', 'null', 'images\\listing_images\\IMG-20210511-WA0012.jpg', 0, '1620938343', ''),
// (3, '2 Bed Room Flat', '2-bed-room-flat-2', 'sale', 'available', 'Bungalow', 500000, 'Nigeria', 'Oyo', 'Ogo', 211224, 'gfjg', 'ekhr whegr whjegrqwe rewjbrwqe rwerqwejh wjerwer', 2, 2, 3, 3500, '', '[\"Air Conditioning\",\"Central Heating\",\"Gym\",\"Window Covering\",\"Alarm\",\"Central Heating\"]', 'null', 'images\\listing_images\\IMG-20210511-WA0010.jpg', 0, '1620938496', '1620994570'),
// (4, '4 Bed Room Flat', '4-bed-room-flat-1', 'sale', 'available', 'Bungalow', 3000000, 'Nigeria', 'Oyo', 'Ibadan', 211224, 'jkljhkj', 'House is a genral purpose house', 4, 1, 12, 2900, '', '[\"Air Conditioning\",\"Swimming Pool\",\"Central Heating\",\"Laundry Room\",\"Gym\",\"Window Covering\",\"Central Heating\"]', 'null', 'images\\listing_images\\IMG-20210511-WA0013.jpg', 0, '1620939931', '1620994521'),
// (5, '2 Bed Room Flat', '2-bed-room-flat-1', 'rent', 'available', 'Bungalow', 300000, 'Nigeria', 'Oyo', 'Ibadan', 211224, 'Highland 3', 'jhlkjshdfjlasdj', 2, 3, 2, 2323, '', '[\"Air Conditioning\",\"Swimming Pool\",\"Central Heating\",\"Laundry Room\",\"Window Covering\",\"Central Heating\"]', '{\"Additional Information\":{\"type\":\"key-value\",\"description\":[{\"key\":\"Agent Fee\",\"value\":\"#3000\"},{\"key\":\"Tax\",\"value\":\"#2000\"}]},\"List\":{\"type\":\"list\",\"description\":[\"khghb\",\"jhjhgkj\"]}}', 'images/listing_images/1621122908_oL62KVdg.jpg', 0, '1620987671', '1621123002'),
// (6, 'Web Developer', 'web-developer', 'sale', 'available', 'Land', 120000, 'Nigeria', 'Oyo', 'Ibadan', 211224, '3, Abule Egba', 'This a description to tell you the brief features of this listing', 2, 2, 2, 2323, '', '[\"Swimming Pool\",\"Central Heating\",\"Laundry Room\",\"Gym\",\"Window Covering\",\"Central Heating\"]', 'null', 'images/listing_images/1621182931_5DaTYbm4.jpeg', 0, '1621167394', '1621196840');

// CREATE TABLE `fh_listing_categories` (
//   `id` int(11) NOT NULL,
//   `parent_id` int(11) NOT NULL,
//   `title` varchar(100) NOT NULL,
//   `slug` varchar(150) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// INSERT INTO `fh_listing_categories` (`id`, `parent_id`, `title`, `slug`) VALUES
// (1, 0, 'Land', 'land'),
// (2, 0, 'Bungalow', 'bungalow');

// CREATE TABLE `fh_listing_images` (
//   `id` int(11) NOT NULL,
//   `plan_name` varchar(50) NOT NULL,
//   `listing_id` int(11) NOT NULL,
//   `path` varchar(250) NOT NULL,
//   `deleted` tinyint(1) NOT NULL,
//   `is_plan` tinyint(1) NOT NULL,
//   `created_date` varchar(12) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

// INSERT INTO `fh_listing_images` (`id`, `plan_name`, `listing_id`, `path`, `deleted`, `is_plan`, `created_date`) VALUES
// (1, '', 4, 'images/listing_images/1620939931_xBEk7SKF.png', 0, 0, '1620939931'),
// (2, '', 5, 'images/listing_images/1620987671_qac4kMnj.png', 1, 0, '1620987671'),
// (3, '', 5, 'images/listing_images/1620988811_R5cxEqs6.png', 1, 0, '1620988811'),
// (4, '', 5, 'images/listing_images/1620988811_t38bmHm4.jpg', 1, 0, '1620988811'),
// (5, '', 5, 'images/listing_images/1620988844_3tJvJvLY.jpeg', 1, 0, '1620988844'),
// (6, '', 4, 'images/listing_images/1620994196_sBNmqaNK.jpg', 1, 0, '1620994196'),
// (7, '', 3, 'images\\listing_images\\IMG-20210511-WA0010.jpg', 0, 0, '1620994570'),
// (8, '', 5, 'images/listing_images/1621122908_oL62KVdg.jpg', 0, 0, '1621122908'),
// (9, '', 5, 'images/listing_images/1621122941_o1Hq5pf8.jpeg', 0, 0, '1621122941'),
// (10, '', 5, 'images/listing_images/1621122974_QbkPDG8G.jpg', 1, 0, '1621122974'),
// (29, '', 6, 'images/listing_images/1621182931_5DaTYbm4.jpeg', 0, 0, '1621182931'),
// (30, '', 6, 'images/listing_images/1621183005_9vyXVt3F.png', 1, 0, '1621183005'),
// (31, '', 6, 'images/listing_images/1621183318_coRhujtx.jpeg', 1, 0, '1621183318'),
// (32, 'Front Plan', 6, '', 1, 1, '1621183827'),
// (33, 'Second Plan', 6, '', 1, 1, '1621183827'),
// (34, '', 6, 'images/listing_images/1621183887_HUSzTBCC.jpg', 1, 0, '1621183887'),
// (35, 'Front Plan', 6, '', 1, 1, '1621183914'),
// (36, '', 6, 'images/listing_images/1621185213_N7yGhNE1.jpg', 0, 0, '1621185213'),
// (37, 'Front Plan', 6, '', 1, 1, '1621185213'),
// (38, 'Second Plan', 6, 'images/building_plan_images/1621185213_yZV64rDv.jpeg', 1, 1, '1621185213'),
// (39, 'Front Plan', 6, 'images/building_plan_images/1621186717_fackzT4B.png', 0, 1, '1621186717'),
// (40, 'Second Plan', 6, '', 1, 1, '1621196784'),
// (41, 'Second Plan', 6, 'images/building_plan_images/1621196840_m1gFhLZn.png', 0, 1, '1621196840');

// CREATE TABLE `fh_password_hash` (
//   `id` int(20) NOT NULL,
//   `user_id` int(20) NOT NULL,
//   `hash` varchar(32) CHARACTER SET utf8 NOT NULL,
//   `sms_code` varchar(6) NOT NULL,
//   `sms_expiry_date` int(11) NOT NULL,
//   `activated` tinyint(1) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// INSERT INTO `fh_password_hash` (`id`, `user_id`, `hash`, `sms_code`, `sms_expiry_date`, `activated`) VALUES
// (1, 27, 'xedzDXkEBvmqqDN77fXjQAkpxBY5Mhjo', '', 0, 0),
// (2, 27, 'XheCEMHAEsssuHrruq7UBgVF7Mbvj9cZ', '', 0, 0),
// (3, 27, 'rEybxF8SxzPSNyNSFffKkNkNHepyE6M7', '', 0, 0),
// (4, 27, 'TYBdaPCTr7jR7Xjgjdq6Fq7ynAnAtxar', '', 0, 0),
// (5, 27, 'rmvk74rX9RrsQ3jtTP2Xfc9EAV8nZr8h', '', 0, 0),
// (6, 27, '76Ze9Z5Uhmy9Uk8UKZ3J3NGLnUzgZXre', '', 0, 0);


// CREATE TABLE `fh_posts` (
//   `id` int(11) NOT NULL,
//   `title` varchar(255) NOT NULL,
//   `slug` varchar(128) NOT NULL,
//   `content` longtext NOT NULL,
//   `post_type` enum('blog','page') NOT NULL DEFAULT 'blog',
//   `image` varchar(255) NOT NULL,
//   `category_id` int(11) NOT NULL,
//   `keywords` varchar(255) NOT NULL,
//   `description` text NOT NULL,
//   `published` tinyint(1) NOT NULL,
//   `allow_comments` tinyint(1) NOT NULL,
//   `created_date` int(11) NOT NULL,
//   `modified_date` int(11) NOT NULL,
//   `created_user_id` int(11) NOT NULL,
//   `social_posted` tinyint(1) NOT NULL,
//   `featured` tinyint(1) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

// INSERT INTO `fh_posts` (`id`, `title`, `slug`, `content`, `post_type`, `image`, `category_id`, `keywords`, `description`, `published`, `allow_comments`, `created_date`, `modified_date`, `created_user_id`, `social_posted`, `featured`) VALUES
// (1, 'just a test', 'just-a-test', 'HELLO WORLD', '', 'images/post_thumbs/compensation-plan.png', 0, '', '', 1, 0, 1587318140, 0, 0, 0, 0),
// (2, '2Previous Data Shows Bitcoin After Halving Will See a Massive Rebound', '2previous-data-shows-bitcoin-after-halving-will-see-a-massive-rebound', '<p>HELLO</p>', '', 'images/post_thumbs/compensation-plan.png', 2, '', '', 1, 0, 1589098001, 0, 0, 0, 0),
// (3, '2Previous Data Shows Bitcoin After Halving Will See a Massive Rebound', '2previous-data-shows-bitcoin-after-halving-will-see-a-massive-rebound-1', '<p>hererhjsdfkhagjdhfkahlskdf aafhsdhf hasfouashdiuofhyas fhsiayof sdufhi sdfhdohiugh gh dfhghojdfoh</p>', 'blog', 'images/post_thumbs/compensation-plan.png', 1, '', '', 1, 0, 1589098303, 0, 0, 0, 0),
// (4, 'Bitcoin After Halving Will See a Massive Rebound', 'bitcoin-after-halving-will-see-a-massive-rebound', '<span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">kkjlds fkjlsdfkjsg fjgslgjsiity ietry ertyiyjy yty</span><div><ol><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">54456456bbbhgfhfh</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">tyru</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">ryurtyu</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">yurty\\u</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">rtyu</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">tyu</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">tyu</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">tryui7u67</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">85sy</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">rsy</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">y7567</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">48</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">6</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">78598</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">7iu</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">i87iytukfgh</span></li><li><span style=\"font-family: &quot;comic sans ms&quot;, sans-serif; font-size: xx-large;\">j</span></li></ol></div>', '', 'images/post_thumbs/compensation-plan.png', 0, '', '', 1, 0, 1589158221, 0, 0, 0, 0),
// (5, 'djsofijoaid', 'djsofijoaid', '<p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>hjgkjhkj</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>k;kl ;lk</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>k</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>; ;l;l\';l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>; l;</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>l;l;</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>l\';l\';l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;l;l\'l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'\\\\\'l\\l;</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'\\\';\\l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;l;\'</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;;l\';l;l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'\';l;l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;ll</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'ll ;l;l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>\'\\l;;l;</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u> l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;l;</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;l</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>l; l;lll</u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u><br></u></b></font></p><p style=\"text-align: center; \"><font face=\"Arial Black\"><b><u>;l;l;ll;l ;l;;l</u></b></font></p>', 'blog', 'images/post_thumbs/bg01.jpg', 1, '', '', 1, 0, 1591860789, 0, 0, 0, 0);

// CREATE TABLE `fh_post_categories` (
//   `id` int(1) NOT NULL,
//   `title` varchar(100) NOT NULL,
//   `slug` varchar(100) NOT NULL,
//   `description` text NOT NULL,
//   `keywords` varchar(255) NOT NULL,
//   `parent_id` int(11) NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;

// INSERT INTO `fh_post_categories` (`id`, `title`, `slug`, `description`, `keywords`, `parent_id`) VALUES
// (1, 'Information', 'info', 'Information pages', 'fragrance homes info', 0),
// (2, 'Blog', 'blog', 'fragrance homes Blog', 'fragrance homes blog', 0),
// (3, 'News', 'news', 'The fragrance homes news updates', 'fragrance homes, fragrance homes news', 0),
// (4, 'CDLA', 'cdla', 'The fragrance homes Leadership Academy updates', 'fragrance homes, fragrance homes cdla', 0);

// CREATE TABLE `fh_state_locations` (
//   `id` int(11) NOT NULL,
//   `state` varchar(50) NOT NULL,
//   `cities` text NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// INSERT INTO `fh_state_locations` (`id`, `state`, `cities`) VALUES
// (1, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (2, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (3, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (4, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (5, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (6, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (7, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (8, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (9, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (10, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (11, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (12, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (13, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (14, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (15, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (16, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (17, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (18, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (19, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (20, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (21, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (22, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (23, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (24, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (25, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (26, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (27, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (28, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (29, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (30, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (31, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (32, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (33, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (34, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (35, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (36, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (37, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (38, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (39, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (40, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (41, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (42, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (43, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (44, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (45, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (46, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (47, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (48, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (49, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (50, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (51, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (52, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (53, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (54, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (55, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (56, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (57, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (58, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (59, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (60, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (61, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (62, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (63, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (64, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (65, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (66, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (67, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (68, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (69, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (70, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (71, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (72, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (73, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (74, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (75, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (76, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (77, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (78, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (79, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (80, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (81, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (82, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (83, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (84, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (85, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (86, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (87, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (88, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (89, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (90, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (91, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (92, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (93, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (94, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (95, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (96, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (97, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (98, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (99, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (100, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (101, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (102, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (103, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (104, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (105, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (106, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (107, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (108, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (109, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (110, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (111, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (112, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (113, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (114, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (115, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (116, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (117, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (118, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (119, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (120, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (121, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (122, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (123, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (124, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (125, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (126, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (127, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (128, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (129, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (130, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (131, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (132, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (133, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (134, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (135, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (136, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (137, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (138, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (139, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (140, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (141, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (142, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (143, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (144, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (145, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (146, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (147, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (148, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (149, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (150, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (151, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (152, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (153, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (154, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (155, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (156, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (157, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (158, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (159, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (160, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (161, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (162, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (163, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (164, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (165, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (166, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (167, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (168, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (169, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (170, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (171, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (172, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (173, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (174, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (175, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (176, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (177, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (178, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (179, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (180, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (181, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (182, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (183, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (184, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (185, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (186, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (187, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (188, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (189, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (190, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (191, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (192, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (193, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (194, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (195, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (196, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (197, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (198, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (199, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (200, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (201, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (202, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (203, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (204, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (205, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (206, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (207, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (208, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (209, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (210, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (211, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (212, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (213, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (214, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (215, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (216, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (217, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (218, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (219, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (220, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (221, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (222, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (223, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (224, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (225, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (226, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (227, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (228, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (229, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (230, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (231, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (232, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (233, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (234, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (235, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (236, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (237, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (238, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (239, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (240, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (241, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (242, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (243, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (244, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (245, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (246, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (247, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (248, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (249, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (250, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (251, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (252, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (253, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (254, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (255, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (256, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (257, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (258, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (259, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (260, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (261, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (262, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (263, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (264, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (265, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (266, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (267, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (268, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (269, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (270, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (271, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (272, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (273, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (274, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (275, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (276, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (277, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (278, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (279, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (280, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (281, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (282, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (283, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (284, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (285, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (286, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (287, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (288, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (289, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (290, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (291, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (292, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (293, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (294, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (295, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]');
// INSERT INTO `fh_state_locations` (`id`, `state`, `cities`) VALUES
// (296, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (297, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (298, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (299, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (300, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (301, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (302, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (303, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (304, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (305, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (306, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]'),
// (307, 'Abia', '[\"Aba\",\"Amaigbo\",\"Arochukwu\",\"Bende\",\"Ohafia\",\"Okwe\",\"Umuahia\"]'),
// (308, 'Adamawa', '[\"Demsa\",\"Ganye\",\"Girei\",\"Gombi\",\"Jada\",\"Jimeta\",\"Lamurde\",\"Madagala\",\"Maiha\",\"Mubi\",\"Ngurore\",\"Numan\",\"Shelleng\",\"Song\",\"Toungo\",\"Yola\"]'),
// (309, 'Anambra', '[\"Aguata\",\"Agulu\",\"Anambra\",\"Awka\",\"Enugu Ukwu\",\"Igbo Ukwu\",\"Ihiala\",\"Nkpor\",\"Nnewi\",\"Obosi\",\"Okija\",\"Okpoko\",\"Onitsha\",\"Ozubulu\",\"Uga\"]'),
// (310, 'Bauchi', '[\"Alkaleri\",\"Azare\",\"Bauchi\",\"Bogoro\",\"Bununu Dass\",\"Darazo\",\"Gamawa\",\"Ganjuwa\",\"Jamari\",\"Katagum\",\"Misau\",\"Ningi\",\"Tafawa Balewa\"]'),
// (311, 'Bayelsa', '[\"Brass\",\"Ekeremor\",\"Nembe\",\"Yenagoa\"]'),
// (312, 'Benue', '[\"Aliade\",\"Gboko\",\"Katsina-Ala\",\"Makurdi\",\"Otukpo\",\"Ukum\",\"Zaki Biam\"]'),
// (313, 'Borno', '[\"Abadan\",\"Askira\",\"Bama\",\"Biu\",\"Chibok\",\"Damboa\",\"Dikwa\",\"Gamboru\",\"Gubio\",\"Gwoza\",\"Kaga\",\"Kala\",\"Konduga\",\"Kukawa\",\"Mafa\",\"Magumeri\",\"Maiduguri\",\"Marte\",\"Monguno\",\"Ngala\",\"Shani\"]'),
// (314, 'Delta', '[\"Agbor\",\"Asaba\",\"Bomadi\",\"Burutu\",\"Okpe\",\"Patani\",\"Sapele\",\"Ughelli\",\"Warri\"]'),
// (315, 'Ebonyi', '[\"Abakaliki\",\"Afikpo\",\"Effium\",\"Ezza\",\"Ishieke\",\"Uburu\"]'),
// (316, 'Edo', '[\"Auchi\",\"Benin\",\"Ekpoma\",\"Igarra\",\"Ikpoba\",\"Irrua\",\"Sabongida\",\"Ubiaja\",\"Uromi\"]'),
// (317, 'Ekiti', '[\"Ado\",\"Aramoko\",\"Efon Alaye\",\"Emure\",\"Igbara Odo\",\"Igede\",\"Ijero\",\"Ikere\",\"Ikole\",\"Ilawe\",\"Ipoti\",\"Ise\",\"Ode\",\"Omuo\",\"Osi\"]'),
// (318, 'Enugu', '[\"Agwa\",\"Aku\",\"Awgu\",\"Eha Amufu\",\"Enugu\",\"Enugu Ezike\",\"Enugu Ngwo\",\"Ezeagu\",\"Mberubu\",\"Nsukka\",\"Oji\",\"Udi\"]'),
// (319, 'Gombe', '[\"Ako\",\"Deba\",\"Duku\",\"Garko\",\"Gombe\",\"Kaltungo\",\"Kumo\",\"Nafada\",\"Pindiga\"]'),
// (320, 'Imo', '[\"Aboh\",\"Etiti\",\"Ihite\",\"Nkwerre\",\"Oguta\",\"Okigwe\",\"Owerri\"]'),
// (321, 'Jigawa', '[\"Babura\",\"Birnin Kudu\",\"Buji\",\"Dutse\",\"Garki\",\"Gumel\",\"Gwaram\",\"Gwiwa\",\"Hadejia\",\"Jahun\",\"Kaugama\",\"Kazaure\",\"Keffin Hausa\",\"Kiyawa\",\"Maigatari\",\"Malammaduri\",\"Ringim\",\"Sule Tankarkar\",\"Taura\"]'),
// (322, 'Kaduna', '[\"Birnin Gwari\",\"Doka\",\"Giwa\",\"Gwagwada\",\"Hunkuyi\",\"Igabi\",\"Ikara\",\"Jemaa\",\"Kachia\",\"Kaduna\",\"Kafanchan\",\"Kagarko\",\"Kagoro\",\"Kaura\",\"Kudan\",\"Lere\",\"Makarfi\",\"Sabon Birnin Gwari\",\"Sabongari\",\"Sanga\",\"Soba\",\"Tudun Wada\",\"Zangon Katab\",\"Zaria\"]'),
// (323, 'Kano', '[\"Ajingi\",\"Albasu\",\"Bagwai\",\"Bebeji\",\"Bichi\",\"Bunkure\",\"Dambarta\",\"Dawakin Tofe\",\"Fagge\",\"Garko\",\"Garun Mallam\",\"Gaya\",\"Gezawa\",\"Gwarzo\",\"Kabo\",\"Kano\",\"Karaye\",\"Kibiya\",\"Kiru\",\"Kumbotso\",\"Kunchi\",\"Kura\",\"Madobi\",\"Makoda\",\"Nassarawa\",\"Rano\",\"Rimin Gado\",\"Shanono\",\"Sumaila\",\"Takai\",\"Tofa\",\"Tudun Wada\",\"Wudil\"]'),
// (324, 'Katsina', '[\"Bakori\",\"Batsari\",\"Bindawa\",\"Cheranchi\",\"Dan Dume\",\"Danja\",\"Daura\",\"Dutsi\",\"Dutsin Ma\",\"Faskari\",\"Funtua\",\"Ingawa\",\"Jibiya\",\"Kangiwa\",\"Kankara\",\"Kankiya\",\"Katsina\",\"Kurfi\",\"Malumfashi\",\"Mani\",\"Mashi\",\"Musawa\",\"Rimi\",\"Sandamu\",\"Zango\"]'),
// (325, 'Kebbi', '[\"Argungu\",\"Augie\",\"Bagudo\",\"Birnin Kebbi\",\"Birnin Yauri\",\"Bunza\",\"Fakai\",\"Gwandu\",\"Jega\",\"Kalgo\",\"Koko\",\"Maiyema\",\"Sakaba\",\"Shanga\",\"Suru\",\"Wasagu\",\"Zuru\"]'),
// (326, 'Kogi', '[\"Ajaokuta\",\"Ankpa\",\"Dekina\",\"Idah\",\"Kabba\",\"Koton-Karifi\",\"Kuroro\",\"Lokoja\",\"Mopa\",\"Ogaminana\",\"Ogori\",\"Okene\"]'),
// (327, 'Kwara', '[\"Ajasse\",\"Ilorin\",\"Jebba\",\"Kaiama\",\"Lafiagi\",\"Offa\",\"Pategi\"]'),
// (328, 'Lagos', '[\"Apapa\",\"Badagri\",\"Epe\",\"Ibeju\",\"Iganmi\",\"Ikeja\",\"Ikorodu\",\"Lagos\",\"Ojo\",\"Surulere\"]'),
// (329, 'Nassarawa', '[\"Akwanga\",\"Awe\",\"Doma\",\"Keana\",\"Keffi\",\"Lafia\",\"Nassarawa\",\"Obi\",\"Toto\",\"Wamba\"]'),
// (330, 'Niger', '[\"Agale\",\"Babana\",\"Bida\",\"Bosso\",\"Chanchaga\",\"Gbako\",\"Kontagora\",\"Lapai\",\"Minna\",\"Mokwa\",\"New Bussa\",\"Rijau\",\"Shiroro\",\"Suleja\",\"Wushishi\"]'),
// (331, 'Ogun', '[\"Abeokuta\",\"Ado Odo\",\"Agbara\",\"Aiyetoro\",\"Ewekoro\",\"Ifo\",\"Ijebu Igbo\",\"Ijebu Ode\",\"Ikene\",\"Ilaro\",\"Ipokia\",\"Odogbolu\",\"Owode\",\"Sango Ota\",\"Shagamu\"]'),
// (332, 'Ondo', '[\"Akure\",\"Idanre\",\"Ikare\",\"Irele\",\"Odigbo\",\"Oka\",\"Okitipupa\",\"Ondo\",\"Owo\"]'),
// (333, 'Osun', '[\"Apomu\",\"Ede\",\"Ejigbo\",\"Erin-Oshogbo\",\"Gbongan\",\"Ife\",\"Ifon Osun\",\"Ijesha\",\"Ikire\",\"Ikirun\",\"Ila\",\"Ilesha\",\"Ilobu\",\"Inisa\",\"Iwo\",\"Modakeke\",\"Oke-Mesi\",\"Olorunda\",\"Olupona\",\"Ore\",\"Orolu\",\"Oshogbo\",\"Oyan\"]'),
// (334, 'Oyo', '[\"Akinyele\",\"Egbeda\",\"Eruwa\",\"Fiditi\",\"Ibadan\",\"Ibeto\",\"Igbo Ora\",\"Igboho\",\"Iseyin\",\"Kajola\",\"Kishi\",\"Lalupon\",\"Ogbomosho\",\"Ogo\",\"Oke-Iho\",\"Oyo\",\"Shaki\"]'),
// (335, 'Plateau', '[\"Barakin\",\"Bassa\",\"Bokkos\",\"Bukuru\",\"Jos\",\"Langtang\",\"Pankshin\",\"Riyom\",\"Shendam\",\"Vom\",\"Wase\"]'),
// (336, 'Rivers', '[\"Abonnema\",\"Abua\",\"Ahoada\",\"Bonny\",\"Bugama\",\"Degema\",\"Egbema\",\"Ogu\",\"Okrika\",\"Omoko\",\"Opobo\",\"Oyigbo\",\"Port Harcourt\"]'),
// (337, 'Sokoto', '[\"Binji\",\"Bodinga\",\"Dange\",\"Gada\",\"Goronyo\",\"Gwadabawa\",\"Illela\",\"Kebbe\",\"Kware\",\"Rabah\",\"Raka\",\"Sabon Birni\",\"Sokoto\",\"Tambawel\",\"Tureta\",\"Wamako\",\"Wurno\"]'),
// (338, 'Taraba', '[\"Bali\",\"Gashaka\",\"Gassol\",\"Ibi\",\"Jalingo\",\"Lau\",\"Takum\",\"Wukari\",\"Yorro\"]'),
// (339, 'Yobe', '[\"Damaturu\",\"Fika\",\"Gashua\",\"Geidam\",\"Gorgoram\",\"Gujba\",\"Gulani\",\"Jakusko\",\"Matsena\",\"Nguru\",\"Potiskum\",\"Yusufari\"]'),
// (340, 'Zamfara', '[\"Anka\",\"Bungudu\",\"Chafe\",\"Gummi\",\"Gusau\",\"Isa\",\"Kaura Namoda\",\"Kiyawa\",\"Maradun\",\"Maru\",\"Shinkafe\",\"Talata Mafara\",\"Zurmi\"]');

// CREATE TABLE `fh_users` (
//   `id` bigint(20) NOT NULL,
//   `username` varchar(150) CHARACTER SET utf8 NOT NULL,
//   `password` varchar(100) CHARACTER SET utf8 NOT NULL,
//   `email` varchar(100) CHARACTER SET utf8 NOT NULL,
//   `fullname` varchar(255) CHARACTER SET utf8 NOT NULL,
//   `role` enum('admin','vendor','user') CHARACTER SET utf8 NOT NULL DEFAULT 'user',
//   `role_id` int(11) NOT NULL DEFAULT 1,
//   `joindate` int(11) NOT NULL,
//   `lastvisitdate` int(11) NOT NULL,
//   `online` tinyint(1) NOT NULL DEFAULT 0,
//   `active` tinyint(1) NOT NULL DEFAULT 0,
//   `meta` text NOT NULL
// ) ENGINE=InnoDB DEFAULT CHARSET=latin1;

// INSERT INTO `fh_users` (`id`, `username`, `password`, `email`, `fullname`, `role`, `role_id`, `joindate`, `lastvisitdate`, `online`, `active`, `meta`) VALUES
// (25, '08165461631', '$2y$10\$Bfqfwxzj27gW.I1w1dDXgudxhs9n2MWp3Rf.5RDPNtsAKXwlzIzkW', 'oderinwalefemi150@gmail.com', 'Oderinwale Oluwafemi Peter', 'user', 1, 1620805833, 1620805833, 0, 1, ''),
// (26, 'oderinwalefemi150@gmail.com', '$2y$10\$zjR8.i7dMDciABm8u8bhkuarKJPD3D3Iv/BsMXBL1UScG137V7cQS', 'oderinwalefm@gmail.com', 'Oluwafemi Oderinwale', 'admin', 1, 1620826516, 1621144221, 1, 1, ''),
// (27, '08165461630', '$2y$10\$b5xlYo2s03PRAI6q6OrHhuMCByWRvvg3Ms6hCJfdL7KfQSYAXMI1u', 'oderinwalefemi@gmail.com', 'Oderinwale Peter', 'admin', 1, 1620827669, 1620827669, 0, 1, '');

// ALTER TABLE `fh_account_activation_hash`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_app_contents`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_app_settings`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_auth_tokens`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_countries`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_email_templates`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_faqs`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_listings`
//   ADD PRIMARY KEY (`id`),
//   ADD UNIQUE KEY `slug` (`slug`);
// ALTER TABLE `fh_listings` ADD FULLTEXT KEY `type` (`type`,`state`,`city`,`address`,`features`);

// ALTER TABLE `fh_listing_categories`
//   ADD PRIMARY KEY (`id`),
//   ADD UNIQUE KEY `title` (`title`),
//   ADD UNIQUE KEY `slug` (`slug`);

// ALTER TABLE `fh_listing_images`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_password_hash`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_posts`
//   ADD PRIMARY KEY (`id`),
//   ADD UNIQUE KEY `slug` (`slug`);

// ALTER TABLE `fh_state_locations`
//   ADD PRIMARY KEY (`id`);

// ALTER TABLE `fh_users`
//   ADD PRIMARY KEY (`id`),
//   ADD UNIQUE KEY `username` (`username`,`email`);

// ALTER TABLE `fh_account_activation_hash`
//   MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

// ALTER TABLE `fh_app_contents`
//   MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

// ALTER TABLE `fh_app_settings`
//   MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

// ALTER TABLE `fh_auth_tokens`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

// ALTER TABLE `fh_countries`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=251;

// ALTER TABLE `fh_email_templates`
//   MODIFY `id` int(20) NOT NULL AUTO_INCREMENT;

// ALTER TABLE `fh_faqs`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

// ALTER TABLE `fh_listings`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

// ALTER TABLE `fh_listing_categories`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

// ALTER TABLE `fh_listing_images`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

// ALTER TABLE `fh_password_hash`
//   MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

// ALTER TABLE `fh_posts`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

// ALTER TABLE `fh_state_locations`
//   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=341;

// ALTER TABLE `fh_users`
//   MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;
// COMMIT;
// ");

//Get content type header for output
$headers = getallheaders();
$content_type = @$headers["contentType"];
if ($content_type == "json" || @$_GET["contentType"] == "json") $APP->setIsJSON(true);
if ($content_type == "html" || @$_GET["contentType"] == "html") $APP->setIsAJAX(true);

//Output content
$APP->execute();
$APP->printOutput();
$DB->close();