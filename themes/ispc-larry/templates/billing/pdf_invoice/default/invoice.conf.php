<?php

/*
  This file contains various parameters for the invoice.
  If you want to change thes evalues, make a copy of this file named
  invoice-custom.conf.php and change the values inside that file.
*/

$invoice_conf = array();

//* Fonts
$invoice_conf['font_name'] = 'DejaVuSansCondensed';
$invoice_conf['font_normal_file'] = 'DejaVuSansCondensed.ttf';
$invoice_conf['font_bold_file'] = 'DejaVuSansCondensed-Bold.ttf';

//* Parameters for invoice details
$invoice_conf['invoice_details_fill_color'] = 220; // 220 = light grey
$invoice_conf['invoice_details_fill_background'] = 0;  // Values: 0/1

//* Parameters for address details
$invoice_conf['invoice_adressbox_head_border'] = 'B';
$invoice_conf['invoice_adressbox_border'] = '0';


//* Set widths and aligns of invoice item columns
$invoice_conf['invoice_column_widths'] = '15,110,20,20,15';
$invoice_conf['invoice_column_aligns'] = 'L,L,R,R,R';



?>