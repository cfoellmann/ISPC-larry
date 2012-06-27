<?php

//* Include the ispconfog base libraries
require_once('../../../../../lib/config.inc.php');
require_once('../../../../../lib/app.inc.php');

//* Check permissions for module
$app->auth->check_module_permissions('billing');

//* ID of the invoice that we want to use for tests
$invoice_id = 58;

//* Show the demo logo
$conf['demo_mode'] = true;

//* Include the invoice template
include('make_pdf_invoice.php');

//* Create the invoice
$pdf = new PDFInvoice_default();
$pdf->createInvoice($invoice_id);

//* Get the pdf invoice content as string
$pdf_content = $pdf->Output('doc.pdf','S');

// $invoice_filename = ISPC_ROOT_PATH.'/invoices/invoice_'.$pdf->invoice['invoice_id'].'.pdf';
// file_put_contents($invoice_filename,$pdf_content);

//* Output the content to the browser
header('Content-type: application/pdf');
echo $pdf_content;

?>