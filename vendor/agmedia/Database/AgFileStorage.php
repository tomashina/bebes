<?php
/**
 * Created by fj.agmedia.hr
 * Date: 30/06/2017
 * Time: 23:39
 */

namespace Agmedia\Database;


use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local as Adapter;

class AgFileStorage
{

    private $fs;
    private $path;


    public function __construct($path)
    {
        $this->fs = new Filesystem(new Adapter(DIR_UPLOAD));
        $arr = explode('/', str_replace(['.', ' '], '/', $path));
        $this->path = 'agm/storage/' . $arr[0] . '/' . $arr[1] . '.json';

        if (!$this->fs->has($this->path)) {
            $this->fs->write($this->path, '{}');
        }

        return $this;
    }


    
    public function fetch()
    {
        return $this->fs->read($this->path);
    }

    
    
    public function store($data)
    {
        \Agmedia\Log\Log::write('AgFileStorage storeData( $data )', 'storage');
        \Agmedia\Log\Log::write($data, 'storage');

        $response = $this->fs->update($this->path, $data);

        \Agmedia\Log\Log::write('AgFileStorage storeData( $response )', 'storage');
        \Agmedia\Log\Log::write($response, 'storage');


        return $response;
    }
}