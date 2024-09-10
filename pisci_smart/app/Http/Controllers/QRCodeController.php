<?php

namespace App\Http\Controllers;


use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Dispositif;


class QRCodeController extends Controller
{
    // public function generateQRCode($idDispo)
    // {
    //     // Récupérer le dispositif avec l'ID fourni
    //     $dispositif = Dispositif::find($idDispo);

    //     if (!$dispositif) {
    //         return response()->json(['message' => 'Dispositif non trouvé'], 404);
    //     }

    //     // Générer un QR code à partir des données du dispositif (ici on utilise l'ID comme exemple)
    //     $qrCode = QrCode::size(200)->generate($dispositif->idDispo);

    //     // Retourner le QR code sous forme d'image
    //     return response($qrCode)->header('Content-Type', 'image/png');
    // }
}
