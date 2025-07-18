<?php

namespace App\Services;

use App\Models\Factura;
use App\Models\Sifen;
use App\Models\Timbrado;
use RobRichards\XMLSecLibs\XMLSecurityKey;
use RobRichards\XMLSecLibs\XMLSecurityDSig;
use RobRichards\XMLSecLibs\Utils\XPath;
use DOMDocument;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SifenServices
{

    protected $entidad, $sifenHelper;

    public function __construct()
    {

    }

    public function envio(Sifen $sifen)
    {

    }

    public function inutizacion(Sifen $sifen, string $motivo)
    {
        try {

            $this->directorios();

            $datajson = [
                'tipoEvento' => 2,
                'cdc' => $sifen->cdc,
                'motivo' => $motivo
            ];

            $factura = Factura::find($sifen->factura_id);
            $timbrado = Timbrado::find($factura->timbrado_id);
            $esta = $factura->establecimiento->sucursal;

            if ($datajson['tipoEvento'] == 1) {
                $timbrado = $timbrado->timbrado; //$datos['timbrado']
                $tipoDoc = $factura->tipo_documento_id; //$datos['tipoDoc'];
                $establecimiento = $esta->sucursal; //$datos['establecimiento'];
                $punto = $esta->general; //$datos['punto'];
                // $desde = $datos['desde'];
                // $hasta = $datos['hasta'];
                $desde = 1;
                $hasta = 1;
                $motivo = $motivo;//$datos['motivo'];
                $xmlString = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                            <gGroupGesEve xmlns="http://ekuatia.set.gov.py/sifen/xsd"
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepEvento_v150.xsd">
                            <rGesEve xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepEvento_v150.xsd">
                                <rEve Id="' . $sifen->secuencia . '">
                                    <dFecFirma>' . $sifen->fechaFirma . '</dFecFirma>
                                    <dVerFor>150</dVerFor>
                                    <gGroupTiEvt>
                                        <rGeVeInu>
                                            <dNumTim>' . $timbrado . '</dNumTim>
                                            <dEst>' . $establecimiento . '</dEst>
                                            <dPunExp>' . $punto . '</dPunExp>
                                            <dNumIn>' . $desde . '</dNumIn>
                                            <dNumFin>' . $hasta . '</dNumFin>
                                            <iTiDE>' . $tipoDoc . '</iTiDE>
                                            <mOtEve>' . $motivo . '</mOtEve>
                                        </rGeVeInu>
                                    </gGroupTiEvt>
                                </rEve>
                            </rGesEve>
                        </gGroupGesEve>';

                $xml = $xmlString;
                $relativePath = 'eventos/event_' . $sifen->secuencia . '.xml';
                $absolutePath = $this->firmarXML($xml, $relativePath, $sifen->secuencia);

                return $absolutePath;
            }
        } catch (\Exception $e) {
            Log::error('Fallo al generar XML Evento: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function cancelacion(Sifen $sifen, string $motivo)
    {
        $factura = Factura::find('factura_id', $sifen->factura_id);
        $persona = $factura->persona;
        $cedula_ad = (empty($persona->ruc) ? $persona->documendo : $persona->ruc);
        $nombre_ad = $persona->nombre .' '. $persona->apellido;

        $cdc = $sifen->cdc;
        $ruc = $cedula_ad;
        $nombre = htmlspecialchars($nombre_ad, ENT_XML1, 'UTF-8');
        // ACA DEBO TENER EN CUENTA SI ES MUNICIPIO O PERSONA O EMPRESA
        // CAMBIAR SI LLEGO A IMPLEMENTAR OTRO LADO
        $iTiOpe = "2"; // B2C
        $iNatRec = (strpos($ruc, '-') !== false) ? 1 : 2;
        // Verificar si el RUC comienza con 800 o 801
        $iTiContRec = null;
        $dNumIDRec  = null;
        if ($iNatRec === 1) {
            $iTiContRec  = (substr($ruc, 0, 3) === '800' || substr($ruc, 0, 3) === '801') ? 2 : 1;
            $rucCompleto = explode('-', $ruc);
            $druc        = $rucCompleto[0];
            $iTiOpe      = '1';

        } else {
            $dNumIDRec = $ruc;
            $iTiOpe    = '2';
        }

        $xmlString = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
            <gGroupGesEve
                xmlns="http://ekuatia.set.gov.py/sifen/xsd"
                xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepEvento_v150.xsd">
                <rGesEve xmlns="http://ekuatia.set.gov.py/sifen/xsd">
                    <rEve Id="' . $sifen->secuencia . '">
                        <dFecFirma>' . $sifen->fechaFirma . '</dFecFirma>
                        <dVerFor>150</dVerFor>
                        <gGroupTiEvt>
                            <rGEveNom>
                                <Id>' . $cdc . '</Id>
                                <mOtEve>No se puso nombre</mOtEve>
                                <iNatRec>' . $iNatRec . '</iNatRec>
                                <iTiOpe>' . $iTiOpe . '</iTiOpe>
                                <cPaisRec>PRY</cPaisRec>
                                <dDesPaisRe>Paraguay</dDesPaisRe>';
        if ($iNatRec === 1) {
            $xmlString .= '
            <iTiContRec>' . $iTiContRec . '</iTiContRec>
              <dRucRec>' . trim($druc) . '</dRucRec>
              <dDVRec>' . substr($ruc, -1) . '</dDVRec>';
        } else {
            $xmlString .= '
            <iTipIDRec>1</iTipIDRec>
            <dDTipIDRec>Cédula paraguaya</dDTipIDRec>
            <dNumIDRec>' . $dNumIDRec . '</dNumIDRec>';
        }

        $xmlString .= '
        <dNomRec>' . trim($nombre) . '</dNomRec>
                        </rGEveNom>
                    </gGroupTiEvt>
                </rEve>
            </rGesEve>
        </gGroupGesEve>';

    }

    public function directorios(){

        if (!Storage::disk('public')->exists('eventos')) {
            Storage::disk('public')->makeDirectory('eventos');
        }

        return true;
    }

    public function firmarXML(string $xmlString, string $relativePath, int $secuencia)
    {

        $xml = $xmlString;
        $doc = new DOMDocument();
        $doc->loadXML($xml, true);
        $ruta_cert = storage_path('app/keys/firma.p12');
        $pkcs12 = file_get_contents($ruta_cert);
        $priv_key = null;
        $certs    = array();
        //$password = $p12_pass;
        $password = 'LqO#9j0E';
        if (openssl_pkcs12_read($pkcs12, $certs, $password)) {
            $priv_key = $certs['pkey'];
            $cert     = $certs['cert'];
        } else {
            throw new \Exception("Error de contraseña: Verifica que la contraseña de tu clave privada sea correcta." . $ruta_cert);
        }

        $key = new XMLSecurityKey(XMLSecurityKey::RSA_SHA256, array('type' => 'private'));
        $key->loadKey($priv_key);

        $objDSig = new XMLSecurityDSig('', array('prefix' => 'ds'));
        //$objDSig = new XMLSecurityDSig();
        $objDSig->setCanonicalMethod(XMLSecurityDSig::EXC_C14N);

        //$objDSig->setCanonicalMethod(RobRichards\XMLSecLibs\XMLSecurityDSig::C14N);
        $rEvenode = $doc->documentElement->getElementsByTagName('rEve')->item(0);
        $objDSig->addReference(
            $rEvenode,
            XMLSecurityDSig::SHA256,
            array(
                'http://www.w3.org/2000/09/xmldsig#enveloped-signature',
                'http://www.w3.org/2001/10/xml-exc-c14n#',
            ),
            array(
                'id_name'   => 'Id',
                'overwrite' => false,

            )
        );

        $objDSig->sign($key);
        $objDSig->add509Cert($cert);
        // Obtener el nodo de firma
        $signatureNode = $objDSig->sigNode;
        $rGesEveNode   = $doc->getElementsByTagName("rGesEve")->item(0);
        // Importar el nodo de firma al documento
        $importedSignatureNode = $doc->importNode($signatureNode, true);
        // Insertar el nodo de firma importado antes del cierre de la etiqueta </rGesEve>
        $rGesEveNode->insertBefore($importedSignatureNode, $rEvenode->nextSibling);
        $xml_firmado = $doc->saveXML();
        $relativePath = 'eventos/event_' . $secuencia . '.xml';
        Storage::disk('public')->put($relativePath, $xml_firmado);
        $absolutePath = Storage::disk('public')->path($relativePath);

        return $absolutePath;
    }

}
