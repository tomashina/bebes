<?php
/**
 * Created by /* fj.agmedia.hr
 * Date: 20/06/2017
 * Time: 14:27
 */

namespace Agmedia\Model;

use Agmedia\Database\Database;
use Agmedia\Log\Log;

class Product
{

    /**
     * @var array $product
     */
    public $product;


    /*
    * Constructor
    */
    public function __construct($product = null)
    {
        $this->product = $product;
    }

}