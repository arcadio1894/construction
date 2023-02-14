<?php

namespace App\Http\Controllers;

use App\ImagesQuote;
use App\Quote;
use Illuminate\Http\Request;
use Webklex\PDFMerger\Facades\PDFMergerFacade as PDFMerger;
use Barryvdh\DomPDF\Facade as PDF;

class PdfsController extends Controller
{
    public function mergePdfs()
    {
        $oMerger = PDFMerger::init();

        $pdf1 = public_path().'/pdfs/PDF de prueba.pdf';
        $pdf2 = public_path().'/pdfs/PDF de prueba2.pdf';

        $oMerger->addPDF($pdf1, 'all');
        $oMerger->addPDF($pdf2, 'all');

        $oMerger->merge();
        $oMerger->stream();
    }

    public function printQuote()
    {
        // Eliminamos elos archivos
        $files = glob(public_path().'/pdfs/*');
        foreach($files as $file){
            if(is_file($file))
                unlink($file);
        }

        $quote = Quote::where('id', 59)
            ->with('customer')
            ->with('deadline')
            ->with(['equipments' => function ($query) {
                $query->with(['materials', 'consumables', 'workforces', 'turnstiles']);
            }])->first();

        $images = ImagesQuote::where('quote_id', $quote->id)
            ->where('type', 'img')
            ->orderBy('order', 'ASC')->get();

        $view = view('exports.quoteCustomer', compact('quote', 'images'));

        $pdf = PDF::loadHTML($view);

        $name = $quote->code . ' '. $quote->description_quote . '.pdf';

        $image_path = public_path().'/pdfs/'.$name;
        if (file_exists($image_path)) {
            unlink($image_path);
        }

        $output = $pdf->output();
        file_put_contents(public_path().'/pdfs/'.$name, $output);

        $pdfPrincipal = public_path().'/pdfs/'.$name;

        $oMerger = PDFMerger::init();

        $oMerger->addPDF($pdfPrincipal, 'all');

        $pdfs = ImagesQuote::where('quote_id', $quote->id)
            ->where('type', 'pdf')->get();

        foreach ( $pdfs as $pdf )
        {
            $namePdf = public_path().'/images/planos/'.$pdf->image;
            $oMerger->addPDF($namePdf, 'all');
        }

        $oMerger->merge();
        $oMerger->stream();
    }
}
