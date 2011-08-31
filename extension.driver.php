<?php

	Class Extension_URLtoPDF extends Extension {

		public function about() {
			return array(
				'name' => 'URL to PDF',
				'version' => '0.1',
				'release-date' => 'unreleased',
				'author' => array(
					'name' => 'Brendan Abbott',
					'website' => 'http://www.bloodbone.ws',
					'email' => 'brendan@bloodbone.ws'
				),
				'description' => 'Uses the mPDF library to take your HTML page and output it as a PDF'
			);
		}

	/*-------------------------------------------------------------------------
		Definition:
	-------------------------------------------------------------------------*/

		public function getSubscribedDelegates(){
			return array(
				array(
					'page' => '/frontend/',
					'delegate' => 'FrontendOutputPostGenerate',
					'callback' => 'generatePDFfromURL'
				),
			);
		}

	/*-------------------------------------------------------------------------
		Delegates:
	-------------------------------------------------------------------------*/

		/**
		 * Generate a PDF from a complete URL
		 */
		public function generatePDFfromURL(array &$context = null) {
			$page_data = Frontend::Page()->pageData();

			if(!isset($page_data['type']) || !is_array($page_data['type']) || empty($page_data['type'])) return;

			foreach($page_data['type'] as $type) {
				if($type == 'pdf') {
					// Page has the 'pdf' type set, so lets generate!
					$this->generatePDF($context['output']);
				}
			}
		}

		public function generatePDF($output) {
			$params = Frontend::Page()->_param;

			$pdf = self::initPDF();

			$pdf->SetAuthor($params['website-name']);
			$pdf->SetTitle($params['page-title']);

			$pdf->WriteHTML($output);

			//Close and output PDF document
			$pdf->Output();
			exit();
		}

		private static function initPDF() {
			require_once(EXTENSIONS . '/urltopdf/lib/MPDF53/mpdf.php');

			$pdf = new mpdf();

			return $pdf;
		}

	}
