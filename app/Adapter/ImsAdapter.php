<?php


namespace App\Adapter;


use App\GlobalClass\KonfigurasiEndPoinApi;

class ImsAdapter extends GenericAdapter
{
    public function getBpm($data = [])
    {
        $konfigurasiEndPointGlobalClass = new KonfigurasiEndPoinApi();
        $query = $this->build_http_query($data);
        $urlRequest = $konfigurasiEndPointGlobalClass->imsApi().'/erp/get-bpm?'.$query;

        $hasil = $this->curlAdapterGetRequest($urlRequest);
        return $hasil;
    }

    public function getProject($data = [])
    {
        $konfigurasiEndPointGlobalClass = new KonfigurasiEndPoinApi();
        $urlRequest = $konfigurasiEndPointGlobalClass->imsApi().'/erp/get-project?';

        $hasil = $this->curlAdapterGetRequest($urlRequest);
        return $hasil;
    }

}
