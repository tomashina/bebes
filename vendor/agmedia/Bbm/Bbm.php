<?php
/**
 * Created by fj.agmedia.hr
 * Date: 11/07/2018
 * Time: 14:49
 */

namespace Agmedia\Bbm;

use Agmedia\Log\Log;


class Bbm
{

    private $url = 'http://81.17.229.226:8088/cgi-bin/v2izd.cgi?stanje&';

    public $sku;

    public $timeout = 4;

    public $product_data;


    /**
     * Get the stock by SKU
     * and return quantity
     *
     * @param $sku
     * @param $qty
     * @return number $qty
     */
    public function getStock($sku)
    {
        $data = $this->getData($sku);

        Log::write('getStock($sku) :::::::::::::::::', 'test_stock');
        Log::write($data, 'test_stock');

        return $data['stanje'];
    }

    /**
     * Get the stock by SKU
     * and return quantity
     *
     * @param $sku
     * @param $qty
     * @return number $qty
     */
    public function getData($sku)
    {
        $sku = str_replace(' ', '%20', $sku);
        $this->sku = $sku;
        $this->connect();

        Log::write('getData($sku) :::::::::::::::::', 'test_stock');
        Log::write('SKU = ' . $this->sku, 'test_stock');

        return $this->resolveData();
    }

    /**
     * Return URL data as array
     *
     * @param String $data
     * @return Array
     */
    public function resolveData()
    {
        $_data = explode(';', $this->product_data);

        Log::write('resolveData() :::::::::::::::::', 'test_stock');
        Log::write($_data, 'test_stock');

        return array(
            'sifra'  => $_data[0],
            'stanje' => $_data[1],
            'vpc'    => $_data[2],
            'mpc'    => $_data[3],
        );
    }

    /**
     * Connect to URL and retrive data
     *
     * @return String
     */
    public function connect()
    {
        $url = $this->url . $this->sku;
        $ch = curl_init($url);
        
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        //curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->timeout);
        $this->product_data = curl_exec($ch);
        curl_close($ch);

        Log::write('connect() :::::::::::::::::', 'test_stock');
        Log::write($url, 'test_stock');
        Log::write($this->product_data, 'test_stock');

        return $this;
    }

}