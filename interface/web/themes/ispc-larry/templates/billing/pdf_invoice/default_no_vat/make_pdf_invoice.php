<?php

//* Load the tfpdf librarie
require_once(ISPC_WEB_PATH."/billing/lib/tfpdf/tfpdf.php");

/*
   Here we extend tfpdf and create a new class. the name of the Class has to be
   PDFInvoice_ plus the name of the template, in this case "default" so the full
   name of the class is "PDFInvoice_default".
*/

class PDFInvoice_default_no_vat extends tFPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;
	var $widths;
	var $aligns;
	var $date_format;
	var $company;
	var $wb;

	/* Constructor */
	function PDFInvoice_default($orientation='P',$unit='mm',$format='A4')
	{
    	
    	//* Initialization
    	$this->B=0;
    	$this->I=0;
    	$this->U=0;
    	$this->HREF='';
		$this->invoice_id=0;
		$this->date_format="m/d/Y";
		$this->company=array();
		
		//* Call parent constructor
    	parent::tFPDF($orientation,$unit,$format);
	}

	//* Function that writes the footer of the page
	function Footer() {
	
	$company = $this->company;
	
    $this->Line(15,276,200,276);
	
	$this->SetFont($this->font_name,'',8);
	
	$column_text = '';
	$column_text .= $company['company_name']."\n";
	$column_text .= $company['street']."\n";
	$column_text .= $company['zip'].' '.$company['city'].' - '.$this->lng($company['country'])."\n";
	$cell_height = $this->NbLines(50,$column_text)+1;
	
	$this->SetXY(15,-20);
	$this->MultiCell(50,$cell_height,$column_text,0,'L');
	
	$column_text = '';
	$column_text .= $this->lng('bank_account_txt').': '.$company['bank_account_number'].' '.$this->lng('bank_code_txt').': '.$company['bank_code']."\n";
	$column_text .= $this->lng('bank_name_txt').': '.$company['bank_name']."\n";
	if($company['bank_account_swift'] != '') $column_text .= $this->lng('swift_txt').': '.$company['bank_account_swift']."\n";
	$cell_height = $this->NbLines(50,$column_text)+1;
	
	$this->SetXY(65,-20);
	$this->MultiCell(50,$cell_height,$column_text,0,'L');
	
	$column_text = '';
	$column_text .= $this->lng('iban_txt').': '.$company['bank_account_iban']."\n";
	$column_text .= $this->lng('vat_id_txt').': '.$company['vat_id']."\n";
	if($company['company_register'] != '') $column_text .= $company['company_register']."\n";
	$cell_height = $this->NbLines(50,$column_text)+1;
	
	$this->SetXY(115,-20);
	$this->MultiCell(50,$cell_height,$column_text,0,'L');
	
	$column_text = '';
	if($company['ceo_name'] != '') $column_text .= $this->lng('ceo_txt').': '.$company['ceo_name']."\n";
	$column_text .= $this->lng('telephone_txt').': '.$company['telephone']."\n";
	if($company['fax'] != '') $column_text .= $this->lng('fax_txt').': '.$company['fax']."\n";
	$cell_height = $this->NbLines(50,$column_text)+1;
	
	$this->SetXY(165,-20);
	$this->MultiCell(50,$cell_height,$column_text,0,'L');
    
	}
	
	//* Internal helper function: Write text with appended newline
	function WriteLn($height,$text,$height2 = 0) {
        	$this->Write($height,$text);
        	if($height2 > 0) {
                	$this->Ln($height2);
        	} else {
                	$this->Ln();
        	}
	}
	
	//* Internal helper function: set array with column widths
	function SetWidths($w) {
    	//Set the array of column widths
    	$this->widths=$w;
	}
	
	//* Internal helper function: set array with column aligns
	function SetAligns($a)
	{
    	//Set the array of column alignments
    	$this->aligns=$a;
	}
	
	//* Internal helper function: write table row
	function Row($data)
	{
    //Calculate the height of the row
    $nb=0;
    for($i=0;$i<count($data);$i++)
        $nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
    $h=5*$nb;
    //Issue a page break first if needed
    $this->CheckPageBreak($h);
    //Draw the cells of the row
    for($i=0;$i<count($data);$i++)
    {
        $w=$this->widths[$i];
        $a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
        //Save the current position
        $x=$this->GetX();
        $y=$this->GetY();
        //Draw the border
        $this->Rect($x,$y,$w,$h);
        //Print the text
        $this->MultiCell($w,5,$data[$i],0,$a);
        //Put the position to the right of the cell
        $this->SetXY($x+$w,$y);
    }
    //Go to the next line
    $this->Ln($h);
	}
	
	//* Internal helper function: check for page breaks
	function CheckPageBreak($h)
	{
    //If the height h would cause an overflow, add a new page immediately
    if($this->GetY()+$h>$this->PageBreakTrigger)
        $this->AddPage($this->CurOrientation);
	}
	
	//* Internal helper function: Computes the number of lines a MultiCell of width w will take
	function NbLines($w,$txt)
	{
    $cw=&$this->CurrentFont['cw'];
    if($w==0)
        $w=$this->w-$this->rMargin-$this->x;
    $wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
    $s=str_replace("\r",'',$txt);
    $nb=strlen($s);
    if($nb>0 and $s[$nb-1]=="\n")
        $nb--;
    $sep=-1;
    $i=0;
    $j=0;
    $l=0;
    $nl=1;
    while($i<$nb)
    {
        $c=$s[$i];
        if($c=="\n")
        {
            $i++;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
            continue;
        }
        if($c==' ')
            $sep=$i;
        $l+=$cw[$c];
        if($l>$wmax)
        {
            if($sep==-1)
            {
                if($i==$j)
                    $i++;
            }
            else
                $i=$sep+1;
            $sep=-1;
            $j=$i;
            $l=0;
            $nl++;
        }
        else
            $i++;
    }
    return $nl;
	}
	
	//* Internal helper function: translate text strings by using the language files
	function lng($text) {
		global $app;
		if(!is_array($this->wb)) {
			if(is_file(ISPC_WEB_PATH.'/billing/lib/lang/'.strtolower($this->client['language']).'_invoice_pdf.lng')) {
				include(ISPC_WEB_PATH.'/billing/lib/lang/'.strtolower($this->client['language']).'_invoice_pdf.lng');
			} else {
				include(ISPC_WEB_PATH.'/billing/lib/lang/en_invoice_pdf.lng');
			}
			
			$this->wb = $wb;
		}
		if(isset($this->wb[$text])) {
			return $this->textdecode($this->wb[$text]);
		} else {
			return $this->textdecode($text);
		}
	}
	
	//* Internal helper function: to decode text, deprecated
	function textdecode($records) {
		return $records;
	}
	
	//* Internal helper function: to format a currency value
	function currency_format($value) {
		global $app;
		return $app->functions->currency_format($value);
	}
	
	//* Internal helper function: to format a date
	function formatted_date($datestring) {
		$tmp = explode('-',$datestring);
		return date($this->invoice_settings['date_format'],mktime(0, 0, 0, $tmp[1]  , $tmp[2], $tmp[0]));
	}
	
	//* Function that writes the invoice content
	function createInvoice($invoice_id) {
		global $app, $conf;
		
		//* Load invoice config file
		if(is_file('invoice-custom.conf.php')) {
			include('invoice-custom.conf.php');
		} else {
			include('invoice.conf.php');
		}
		
		//* Set some variables
		$this->font_name = $invoice_conf['font_name'];
		$this->font_normal_file = $invoice_conf['font_normal_file'];
		$this->font_bold_file = $invoice_conf['font_bold_file'];
		
		//* Add the font files used in the invoice
		$this->AddFont($this->font_name,'',$this->font_normal_file,true);
		$this->AddFont($this->font_name,'B',$this->font_bold_file,true);
		
		//* Set the invoice ID
		$this->invoice_id = $invoice_id;
		
		//* Load data for client, company, invoice etc. and set the data as variables in the invoice object.
		$invoice = $this->textdecode($app->db->queryOneRecord("SELECT * FROM invoice WHERE invoice_id = ".$this->invoice_id));
		$invoice_items = $this->textdecode($app->db->queryAllRecords("SELECT * FROM invoice_item WHERE invoice_id = ".$this->invoice_id));
		$company = $this->textdecode($app->db->queryOneRecord("SELECT * FROM invoice_company WHERE invoice_company_id = ".$invoice['invoice_company_id']));
		$payment_terms = $this->textdecode($app->db->queryOneRecord("SELECT * FROM invoice_payment_term WHERE invoice_payment_term_id = ".$invoice['payment_terms']));
		$client = $this->textdecode($app->db->queryOneRecord("SELECT * FROM client WHERE client_id = ".$invoice['client_id']));
		$invoice_settings = $this->textdecode($app->db->queryOneRecord("SELECT * FROM invoice_settings WHERE invoice_settings_id = 1"));
		
		//* Set some variables
		$this->invoice = $invoice;
		$this->company = $company;
		$this->client = $client;
		$this->invoice_settings = $invoice_settings;
		
		//* Start the first page of the invoice and set basic page parameters
		$this->AddPage();
		$this->SetMargins(15,5,15);
		
		//* Add the logo image
		if($conf['demo_mode'] == false) {
			$logo_path = ISPC_WEB_PATH.'/billing/lib/logo/'.$this->company['company_logo'];
		} else {
			$logo_path = ISPC_WEB_PATH.'/billing/lib/logo/demo_logo.png';
		}
		if(is_file($logo_path)) $this->Image($logo_path,15,10,60);
		
		//* Write the invoice sender details
		$short_company_name = (trim($this->company['company_name_short']) != '')?$this->company['company_name_short']:$this->company['company_name'];
		$this->Ln(1);
		$this->SetFont($this->font_name,'',10);
		$this->SetX(130);
		$this->WriteLn(5,$short_company_name);
		$this->SetX(130);
		$this->WriteLn(5,$this->company['street']);
		$this->SetX(130);
		$this->WriteLn(5,$this->company['zip'].' '.$this->company['city']);
		$this->SetX(130);
		if($this->company['country'] == 'DE') {
			$this->WriteLn(5,' ');
		} else {
			$this->WriteLn(5,$this->company['country']);
		}

		//* Write the Invoice header text
		$this->Ln(5);
		$this->SetX(130);
		$this->SetFont($this->font_name,'B',14);
		if($this->invoice['invoice_type'] == 'invoice') {
			$this->WriteLn(5,$this->lng("invoice_txt"));
		} elseif ($this->invoice['invoice_type'] == 'proforma') {
			$this->WriteLn(5,$this->lng("proforma_txt"));
		} else {
			$this->WriteLn(5,$this->lng("refund_txt"));
		}
		
		/* --------------------------------------------------------------------------
		   Write the box with client address details
		   -------------------------------------------------------------------------*/
		
		//* Set the position of the client address box
		$this->setXY(15,50);
		
		//* Set font and fontsize 
		$this->SetFont($this->font_name,'B',8);
		
		//* Write the text
		$this->Cell(70,6,$this->lng('bill_to_txt'),$invoice_conf['invoice_adressbox_head_border'],2);
		
		//* Build the multiline string for the address
		$recipient_lines = "";
		if($invoice['company_name'] == '' or $invoice['contact_name'] == '') $recipient_lines .= "\n";
		if($invoice['company_name'] != '') $recipient_lines .= $invoice['company_name']."\n";
		if($invoice['contact_name'] != '') $recipient_lines .= $invoice['contact_name']."\n";
		if($invoice['street'] != '') $recipient_lines .= $invoice['street']."\n";
		if($invoice['city'] != '') $recipient_lines .= $invoice['zip']." ".$invoice['city']."\n";
		
		//* Set font and font size
		$this->SetFont($this->font_name,'',10);
		
		//* Write the recipient address
		$recipient_field_height = $this->NbLines(70,$recipient_lines)+2;
		$this->MultiCell(70,$recipient_field_height,$recipient_lines,$invoice_conf['invoice_adressbox_border']);
		
		/* ----------------------------------------------------------------------
		   Write the box with the invoice details like invoice number etc.
		------------------------------------------------------------------------ */
		
		//* Set the position of the invoice details box
		$this->setXY(130,50);
		
		//* Set fillcolor
		$this->SetFillColor($invoice_conf['invoice_details_fill_color']);
		
		$this->SetFont($this->font_name,'B',8);
		$this->Cell(20,6,$this->lng('date_txt'),1,0,'C',$invoice_conf['invoice_details_fill_background']);
		$this->Cell(40,6,$this->lng('invoice_no_txt'),1,0,'C',$invoice_conf['invoice_details_fill_background']);
		
		$this->setXY(130,56);
		$this->SetFont($this->font_name,'',8);
		$this->Cell(20,6,$this->formatted_date($invoice['invoice_date']),1,0,'C',$invoice_conf['invoice_details_fill_background']);
		$this->Cell(40,6,$invoice['invoice_number'],1,0,'C',$invoice_conf['invoice_details_fill_background']);
		
		$this->setXY(130,62);
		$this->Cell(60,6,$this->lng('payment_terms_txt').': '.$this->lng($payment_terms['description']),1,2,'C',$invoice_conf['invoice_details_fill_background']);
		
		if($this->client['customer_no'] != '') {
			$this->SetFont($this->font_name,'',8);
			$this->Cell(60,6,$this->lng('customer_no_txt').' '.$this->client['customer_no'],1,2,'C',$invoice_conf['invoice_details_fill_background']);
		}
		/*
		if($invoice['vat_id'] != '') {
			$this->SetFont($this->font_name,'B',8);
			$this->Cell(60,6,$invoice['company_name'].' '.$this->lng('vat_id_no_txt'),1,2,'C',$invoice_conf['invoice_details_fill_background']);
			$this->SetFont($this->font_name,'',8);
			$this->Cell(60,6,$invoice['vat_id'],1,2,'C',$invoice_conf['invoice_details_fill_background']);
		}
		*/
		
		/* ----------------------------------------------------------------------
		   Write table with invoice items.
		------------------------------------------------------------------------ */
		
		//* Set the upper left corner of the table
		$this->setXY(15,95);
		
		//* Set the column widths
		$this->SetWidths(explode(',',$invoice_conf['invoice_column_widths']));
		
		//* Set the column aligns
		$this->SetAligns(explode(',',$invoice_conf['invoice_column_aligns']));
		
		//* Write the header row
		$this->SetFont($this->font_name,'B',9);
		$header = array($this->lng('quantity_txt'),$this->lng('description_txt'),$this->lng('unit_price_txt'),$this->lng('value_txt'));
		$this->Row($header);
		
		//* Write the invoice item rows
		$this->SetFont($this->font_name,'',8);
		$data = array();
		$invoice_net_sum = 0;
		$invoice_vat_array = array();
		if(is_array($invoice_items)) {
			foreach($invoice_items as $item) {
				$tmp = array();
				$tmp[0] = $item['quantity'];
				$tmp[1] = $app->db->unquote($item['description']);
				$tmp[2] = $this->currency_format($item['price']);
				//$tmp[3] = $item['vat'].'%';
				$tmp[3] = $this->currency_format($item['quantity']*$item['price']);
				$this->Row($tmp);
				
				//* Calculate VAT
				$invoice_net_sum += $item['quantity']*$item['price'];
				$vat_rate = $item['vat'];
				$invoice_vat_array[$vat_rate] += ($item['quantity']*$item['price'])*($vat_rate/100);
			}
		}
		
		//$invoice_vat = array_sum($invoice_vat_array);
		
		/*
		//* Write line with NET amount
		$this->Cell(130,6,$this->lng('net_amount_txt'),'LB',0,'L');
		$this->Cell(50,6,$invoice_settings['currency'].' '.$this->currency_format($invoice_net_sum),'RB',1,'R');
		
		//* Write line for VAT
		$vat_txt = '';
		foreach($invoice_vat_array as $tmp_rate => $tmp_sum) {
			$tmp_sum = $this->currency_format($tmp_sum);
			$vat_txt .= $this->lng('plus_txt')." ".$invoice_settings['currency']." $tmp_sum ".$this->lng('vat_at_txt')." $tmp_rate% "; 
		}
		$vat_txt = substr($vat_txt,0,-1);
		$vat_txt .= '. ';
		
		$this->Cell(130,6,$vat_txt,'LB',0,'L');
		$this->Cell(50,6,$this->lng('total_vat_txt').':     '.$invoice_settings['currency'].' '.$this->currency_format($invoice_vat),'RB',1,'R');
		*/
		
		//* Write row with total amount
		$this->SetFont($this->font_name,'B',10);
		$this->Cell(130,6,$this->lng('total_amount_txt'),'LB',0,'L');
		$this->Cell(50,6,$invoice_settings['currency'].' '.$this->currency_format($invoice_net_sum+$invoice_vat),'RB',1,'R');
		$this->SetFont($this->font_name,'',8);
		
		//* Add newline
		$this->Ln();
		
		/* ----------------------------------------------------------------------
		   Add text below invoice items table.
		------------------------------------------------------------------------ */
		
		//* Add free text from language file
		if($this->lng("free_end_txt") != '') {
			$this->SetFont($this->font_name,'',8);
			$this->WriteLn(5,$this->lng("free_end_txt"),8);
			$this->WriteLn(5,' ',8);
		}
		
		//* Write "thank you" text
		$short_company_name = (trim($this->company['company_name_short']) != '')?$this->company['company_name_short']:$this->company['company_name'];
		$this->SetFont($this->font_name,'',10);
		$this->WriteLn(5,$this->lng("thank_you_txt"),8);
		$this->WriteLn(5,$this->lng("regards_txt"),8);
		$this->WriteLn(5,$short_company_name,8);
		
	}

}


?>