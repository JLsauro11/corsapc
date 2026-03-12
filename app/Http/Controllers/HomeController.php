<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCPDF;

class HomeController extends Controller
{
    public function index() {
        return view('index');
    }

    public function validatePdf(Request $request)
    {
        $validated = $request->validate([
            'vehicleModel' => 'required|string|max:255',
            'engineType' => 'required|string',
            'pipeType' => 'required|in:redlak,dc8',
            'chassisNumber' => 'required|string',
            'engineNumber' => 'required|string',
        ]);

        $templateMap = [
            'redlak' => 'redlak_pipe_template.png',
            'dc8' => 'dc8_pipe_template.png'
        ];
        $templateFile = $templateMap[$validated['pipeType']] ?? 'redlak_pipe_template.png';
        $jpgPath = public_path("assets/{$templateFile}");

        if (!file_exists($jpgPath)) {
            return response()->json(['error' => 'Template not found'], 500);
        }

        return response()->json(['message' => 'Valid']);
    }

    public function generatePdf(Request $request)
    {
        try {
            $validated = $request->validate([
                'vehicleModel' => 'required|string|max:255',
                'engineType' => 'required|string',
                'pipeType' => 'required|in:redlak,dc8',
                'chassisNumber' => 'required|string',
                'engineNumber' => 'required|string',
            ]);

            $data = $validated;
            $templateFile = $data['pipeType'] === 'dc8' ? 'dc8_pipe_template.png' : 'redlak_pipe_template.png';
            $jpgPath = public_path("assets/{$templateFile}");

            if (!file_exists($jpgPath)) {
                return response()->json(['error' => 'Template not found'], 500);
            }

            // ✅ GET EXACT IMAGE DIMENSIONS
            $imageInfo = getimagesize($jpgPath);
            $imgWidthMm = round($imageInfo[0] / 300 * 25.4, 2);  // pixels → mm
            $imgHeightMm = round($imageInfo[1] / 300 * 25.4, 2);

            // ✅ ZERO MARGINS + EXACT PAGE SIZE = NO WHITE SPACE!
            $pdf = new TCPDF('P', 'mm', [$imgWidthMm, $imgHeightMm], true, 'UTF-8', false);

            // **CRITICAL: Remove ALL white space**
            $pdf->SetCreator('RS8');
            $pdf->SetAuthor('RS8');
            $pdf->SetMargins(0, 0, 0, true);           // Zero margins
            $pdf->SetHeaderMargin(0);                  // No header space
            $pdf->SetFooterMargin(0);                  // No footer space
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetAutoPageBreak(false, 0);          // No page break margin
            $pdf->setImageScale(1);                    // No scaling distortion

            $pdf->AddPage();

            // ✅ IMAGE FILLS ENTIRE PAGE - ZERO WHITE!
            $pdf->Image($jpgPath, 0, 0, $imgWidthMm, $imgHeightMm, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);

            $pdf->SetFont('helvetica', 'B', 12);
            $pdf->SetTextColor(0, 0, 0);

            // Your text positions
            $pdf->SetXY(100, 87); $pdf->Cell(0, 8, $data['vehicleModel'], 0, 0, 'L');
            $pdf->SetXY(100, 99); $pdf->Cell(0, 8, $data['chassisNumber'], 0, 0, 'L');
            $pdf->SetXY(100, 93.5); $pdf->Cell(0, 8, ucfirst($data['engineType']), 0, 0, 'L');
            $pdf->SetXY(100, 105); $pdf->Cell(0, 8, $data['engineNumber'], 0, 0, 'L');

            $filename = 'RS8-Certificate-Of-Road-Safety-And-Product-Compliance-' . $data['chassisNumber'] . '-' . strtoupper($data['pipeType']) . '.pdf';

            return response($pdf->Output('', 'S'))
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }



}
