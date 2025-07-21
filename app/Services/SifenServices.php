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

    public function envioEvento(Sifen $sifen, string $de, int $secuencia, int $tipoEvento)
    {
        try {

            $de  = str_replace('<?xml version="1.0" encoding="UTF-8" standalone="no"?>', '', $de);
            $ruta_cert = storage_path('app/keys/firma.p12');
            $password = 'LqO#9j0E';
            $xml = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                        <env:Envelope xmlns:env="http://www.w3.org/2003/05/soap-envelope"
                            xmlns:xsd="http://ekuatia.set.gov.py/sifen/xsd">
                            <env:Header/>
                            <env:Body>
                                <xsd:rEnviEventoDe xmlns:xsd="http://ekuatia.set.gov.py/sifen/xsd">
                                    <xsd:dId>' . $secuencia . '</xsd:dId>
                                    <xsd:dEvReg>' . $de . '</xsd:dEvReg>
                                </xsd:rEnviEventoDe>
                            </env:Body>
                        </env:Envelope>';

            $url = "https://sifen.set.gov.py/de/ws/eventos/evento.wsdl";
            dd($xml);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $xml);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/soap+xml'));
            curl_setopt($ch, CURLOPT_SSLCERT, $ruta_cert);
            curl_setopt($ch, CURLOPT_SSLCERTTYPE, 'P12'); //para usar en formato.p12 en caso de .pem quitar
            curl_setopt($ch, CURLOPT_SSLCERTPASSWD, $password);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            // dd($xml);
            // Ejecutar solicitud cURL
            $response = curl_exec($ch);

            if ($response === false) {
                // echo 'Error de cURL: ' . curl_error($ch);
                throw new \Exception('ERROR DE CURL - '.curl_error($ch));
            } else {
                $xml = simplexml_load_string($response);

                if ($tipoEvento == 1) {
                    // Acceder a los datos y guardarlos en variables
                    $dFecProc = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->dFecProc;
                    $dEstRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->dEstRes;
                    $dCodRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dCodRes;
                    $dMsgRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dMsgRes;

                    if ($dEstRes == 'Rechazado') {

                        $data = array(
                            "status" => false,
                            "code"   => "$dMsgRes",
                            "fecha"  => "$dFecProc",
                        );
                        $json = json_encode($data);
                        return $json;

                    } else {
                        $data = array(
                            "status" => true,
                            "code"   => "$dMsgRes",
                            "fecha"  => "$dFecProc",

                        );
                        $json = json_encode($data);
                        return $json;
                    }

                }

                //evento Cancelacion
                if ($tipoEvento == 2) {
                    // Acceder a los datos y guardarlos en variables
                    $xml = simplexml_load_string($response);
                    $xml->registerXPathNamespace('env', 'http://www.w3.org/2003/05/soap-envelope');
                    $xml->registerXPathNamespace('sifen', 'http://ekuatia.set.gov.py/sifen/xsd');

                    $dFecProcNode = $xml->xpath('//sifen:dFecProc');
                    $dEstResNode  = $xml->xpath('//sifen:dEstRes');
                    $dCodResNode  = $xml->xpath('//sifen:dCodRes');
                    $dMsgResNode  = $xml->xpath('//sifen:dMsgRes');

                    if (!$dEstResNode || !$dFecProcNode || !$dMsgResNode) {
                        throw new \Exception('Nodos esperados no encontrados en respuesta de SIFEN.');
                    }

                    $dFecProc = (string) $dFecProcNode[0];
                    $dEstRes  = (string) $dEstResNode[0];
                    $dCodRes  = (string) $dCodResNode[0];
                    $dMsgRes  = (string) $dMsgResNode[0];

                    $data = [
                        'status' => $dEstRes != 'Rechazado',
                        'code'   => $dMsgRes,
                        'fecha'  => $dFecProc,
                    ];
                    return json_encode($data);
                    // $dFecProc = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->dFecProc;
                    // $dEstRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->dEstRes;
                    // $dCodRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dCodRes;
                    // $dMsgRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dMsgRes;

                    // if ($dEstRes == 'Rechazado') {

                    //     $data = array(
                    //         "status" => false,
                    //         "code"   => "$dMsgRes",
                    //         "fecha"  => "$dFecProc",
                    //     );
                    //     $json = json_encode($data);
                    //     return $json;
                    // } else {
                    //     $data = array(
                    //         "status" => true,
                    //         "code"   => "$dMsgRes",
                    //         "fecha"  => "$dFecProc",

                    //     );
                    //     $json = json_encode($data);
                    //     return $json;
                    // }

                }

                if ($tipoEvento == 3) {

                    // Acceder a los datos y guardarlos en variables
                    $dFecProc = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->dFecProc;
                    $dEstRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->dEstRes;
                    $dCodRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dCodRes;
                    $dMsgRes  = (string) $xml->children('env', true)->Body->children('ns2', true)->rRetEnviEventoDe->gResProcEVe->gResProc->dMsgRes;

                    if ($dEstRes == 'Rechazado') {

                        $data = array(
                            "status" => false,
                            "code"   => "$dMsgRes",
                            "fecha"  => "$dFecProc",
                        );
                        $json = json_encode($data);
                        return $json;

                    } else {
                        $data = array(
                            "status" => true,
                            "code"   => "$dMsgRes",
                            "fecha"  => "$dFecProc",

                        );
                        $json = json_encode($data);
                        return $json;
                    }

                    //echo $response;
                }

            }

        } catch (\Exception $e) {
            Log::error('Fallo al generar XML Evento: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }

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
            $secuencia = 400000;
            $cdc = $sifen->cdc;

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
                $relativePath = 'eventos/event_' . $cdc . '_' . $secuencia . '.xml';
            $absolutePath = $this->firmarXML($xml, $relativePath, $secuencia, $cdc);

                $xmlFirmado = file_get_contents($absolutePath);
                return $xmlFirmado;
            }
        } catch (\Exception $e) {
            Log::error('Fallo al generar XML Evento: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function nominacion(Sifen $sifen)
    {
        try {
            $factura = Factura::find('factura_id', $sifen->factura_id);
            $persona = $factura->persona;
            $secuencia = 400000;
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

            $xml = $xmlString;
            $relativePath = 'eventos/event_' . $cdc . '_' . $secuencia . '.xml';
            $absolutePath = $this->firmarXML($xml, $relativePath, $secuencia, $cdc);

            $xmlFirmado = file_get_contents($absolutePath);
            return $xmlFirmado;
        } catch (\Exception $e) {
            Log::error('Fallo al generar XML Evento: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function cancelacion(Sifen $sifen, string $motivo)
    {
        try {
            $cdc    = $sifen->cdc; // $datos['cdc'];
            $fechaFirma = date("Y-m-d\TH:i:s", strtotime(date("d-m-Y H:i:s")));
            $secuencia = 400000;
            // $motivo = $datos['motivo'];
            $xmlString = '<?xml version="1.0" encoding="UTF-8" standalone="no"?>
                        <gGroupGesEve xmlns="http://ekuatia.set.gov.py/sifen/xsd"
                        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                        xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepEvento_v150.xsd">
                        <rGesEve xsi:schemaLocation="http://ekuatia.set.gov.py/sifen/xsd siRecepEvento_v150.xsd">
                            <rEve Id="' . $secuencia . '">
                                <dFecFirma>' . $fechaFirma . '</dFecFirma>
                                <dVerFor>150</dVerFor>
                                <gGroupTiEvt>
                                    <rGeVeCan>
                                        <Id>' . $cdc . '</Id>
                                        <mOtEve>' . $motivo . '</mOtEve>
                                    </rGeVeCan>
                                </gGroupTiEvt>
                            </rEve>
                        </rGesEve>
                    </gGroupGesEve>';
                $xml = $xmlString;
                $relativePath = 'eventos/event_' . $cdc . '_' . $secuencia . '.xml';
                $absolutePath = $this->firmarXML($xml, $relativePath, $secuencia, $cdc);
                $xmlFirmado = file_get_contents($absolutePath);
                return $xmlFirmado;

        } catch (\Exception $e) {
            Log::error('Fallo al generar XML Evento: ' . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }

    public function directorios(){

        if (!Storage::disk('public')->exists('eventos')) {
            Storage::disk('public')->makeDirectory('eventos');
        }

        return true;
    }

    public function firmarXML(string $xmlString, string $relativePath, int $secuencia, string $cdc)
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
        $relativePath = 'eventos/event_' . $cdc . '_' . $secuencia . '.xml';
        Storage::disk('public')->put($relativePath, $xml_firmado);
        $absolutePath = Storage::disk('public')->path($relativePath);

        return $absolutePath;
    }

}
