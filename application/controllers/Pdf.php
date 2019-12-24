<?php
	set_time_limit(0);

	defined('BASEPATH') OR exit('No direct script access allowed');

	class Pdf extends MY_Controller
	{
		public function __construct()
		{
			parent::__construct();
		}
		public function index()
		{
			if( !$this->input->post('html') ){ redirect('home','refresh'); }

			$format = ($this->input->post('format')) ? $this->input->post('format') : 'A4';
			$font_size = ($this->input->post('font_size')) ? $this->input->post('font_size') : 0;
			$font_family = ($this->input->post('font_family')) ? $this->input->post('font_family') : '';
			$orientation = ($this->input->post('orientation')) ? $this->input->post('orientation') : 'P';

			$margin_left = ($this->input->post('margin_left')) ? $this->input->post('margin_left') : 15;
			$margin_right = ($this->input->post('margin_right')) ? $this->input->post('margin_right') : 15;
			$margin_top = ($this->input->post('margin_top')) ? $this->input->post('margin_top') : 16;
			$margin_bottom = ($this->input->post('margin_bottom')) ? $this->input->post('margin_bottom') : 25;
			$margin_header = ($this->input->post('margin_header')) ? $this->input->post('margin_header') : 15;
			$margin_footer = ($this->input->post('margin_footer')) ? $this->input->post('margin_footer') : 10;
			$output = ($this->input->post('output')) ? $this->input->post('output') : 'view';

			$title = ($this->input->post('title')) ? $this->input->post('title') : substr(str_shuffle('abcefghijklmnopqrstuvwxyz1234567890'), 0, 30);
			$file = ($this->input->post('file')) ? $this->input->post('file') : $title;

			$this->load->library('mpdf2');
			$mpdf = new mPDF('UTF-8', $format, $font_size, $font_family, $margin_left, $margin_right, $margin_top, $margin_bottom, $margin_header, $margin_footer, $orientation); //mode - default '' | format - A4, for example, default '' | font size - default 0 | default font family | margin_left | margin right | margin top | margin bottom | margin header | margin footer| L - landscape, P - portrait

			$mpdf->useDefaultCSS2 = true;
			$mpdf->SetTitle($title);
			//########################################################################################### // HTML (post)
			$html_inputs = $this->input->post('html');
			$html = '';
			for($i=0; $i<sizeof($html_inputs); $i++)
			{
				$html .= $html_inputs[$i];
			}
			//########################################################################################### // Insere todos os CSS e STYLE no PDF
			if( $this->input->post('css') || $this->input->post('style') )
			{
				// $css = '';
				// if($this->input->post('css'))
				// {
				// 	$files_css = explode(',', $this->input->post('css'));
				// 	foreach ($files_css as $value)
				// 	{
				// 		$css .= file_get_contents($value);
				// 	}
				// }
				// $css .= $this->input->post('style');
				// $mpdf->WriteHTML($css, 1);
				$mpdf->WriteHTML($this->input->post('style'), 1);
			}
			//########################################################################################### //background => BACKGROUND IMAGEM  - (default - 800x1131)
			$pattern = "#<\s*?background\b[^>]*>(.*?)</background\b[^>]*>#s";
			preg_match_all($pattern, $html, $matches);
			$background = $matches[1][0];
			$html = trim(preg_replace($pattern, '', $html));
			$html .= '<style type="text/css">body{background-image: url("'.$background.'")};</style>';
			//########################################################################################### //headerimage => IMAGEM NO CABEÇALHO
			$pattern = "#<\s*?headerimage\b[^>]*>(.*?)</headerimage\b[^>]*>#s";
			preg_match_all($pattern, $html, $matches);
			$headerimage = $matches[1][0];
			$html = trim(preg_replace($pattern, '', $html));
			//########################################################################################### //footerimage => IMAGEM NO RODAPÉ
			$pattern = "#<\s*?footerimage\b[^>]*>(.*?)</footerimage\b[^>]*>#s";
			preg_match_all($pattern, $html, $matches);
			$footerimage = $matches[1][0];
			$html = trim(preg_replace($pattern, '', $html));
			//########################################################################################### // TAG header => CABEÇADO DA PÁGINA
			$pattern = "#<\s*?header\b[^>]*>(.*?)</header\b[^>]*>#s";
			preg_match_all($pattern, $html, $matches);
			$header = $matches[1][0];
			$html = trim(preg_replace($pattern, '', $html));
			//########################################################################################### // TAG footer => RODAPÉ DA PÁGINA
			$pattern = "#<\s*?footer\b[^>]*>(.*?)</footer\b[^>]*>#s";
			preg_match_all($pattern, $html, $matches);
			$footer = $matches[1][0];
			$html = trim(preg_replace($pattern, '', $html));
			//###########################################################################################
			$mpdf->SetHTMLHeader($header);
			$mpdf->SetHTMLFooter($footer);
			if($headerimage){$mpdf->SetHTMLHeader($headerimage);}  // Imagens ideais 680px
			if($footerimage){$mpdf->SetHTMLFooter($footerimage);}  // Imagens ideais 680px
			//###########################################################################################
			$mpdf->WriteHTML($html);
			if($output=='view'){$mpdf->Output($file.'.pdf', 'I');}else{$mpdf->Output($file.'.pdf', 'D');}
		}
	}