<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace App\Controller;

use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends AbstractController
{
    protected $urlMagento = "http://rapidg.local/";

    /**
     * @throws Exception
     * @Route("/", name="index")
     */
    public function index()
    {
        return $this->render(
            'replace.html.twig'
        );
    }

    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/search", name="ajax_search")
     */
    public function searchAction(Request $request)
    {
        $requestString = $request->get('q');

        $result['exists'] = $this->checkSkuExists(trim($requestString));
        return new Response(json_encode($result));
    }


    /**
     * Creates a new ActionItem entity.
     *
     * @Route("/replace", name="action_form")
     */
    public function actionFormReplace(Request $request)
    {
        $sku = $request->get('sku');
        $newSKu = $request->get('new_sku');

        $result = $this->replaceSku(trim($sku), trim($newSKu));
        $this->get('session')->getFlashBag()->add(
            'notice',
            $result ? $sku . " replace with " . $newSKu : "Error"
        );
        return $this->redirectToRoute(
            'index'
        );
    }

    private function replaceSku($sku, $newSku): bool
    {
        $url = $this->urlMagento . "rest/V1/products/replaceSku?sku=" . $sku . "&newSku=" . $newSku;
        $apiUrl = str_replace(" ", "%20", $url);

        return $this->callAPi($apiUrl);
    }

    /**
     * @param $sku
     * @return bool
     */
    private function checkSkuExists($sku): bool
    {
        $url = $this->urlMagento . "rest/V1/products/" . $sku;
        $apiUrl = str_replace(" ", "%20", $url);

        $response = $this->callAPi($apiUrl);

        if (!empty($response['sku'])) {
            return true;
        }
        return false;
    }

    private function callAPi($apiUrl)
    {
        $accessToken = "mff8x55yav8vszt0vb56vvvrxbxzobxk";
        $setHeaders = array('Content-Type:application/json', 'Authorization:Bearer ' . $accessToken);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $setHeaders);
        $response = curl_exec($ch);
        curl_close($ch);

        return json_decode($response, TRUE);
    }
}