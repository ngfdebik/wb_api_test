<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;

class DataController extends Controller
{
    public function index(){
        $dateFrom = date('Y-m-d');
        $dateTo = date('Y-m-d', strtotime("+1 day"));
        //$url = 'http://89.108.115.241:6969/api/stocks?dateFrom='.$dateFrom.'&dateTo='.$dateTo.'&page=1&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100';
        $data = array();

        while(true){
            $i = 1;
            $url = 'http://89.108.115.241:6969/api/stocks?dateFrom='.$dateFrom.'&dateTo='.$dateTo.'&page='.$i.'&key=E6kUTYrYwZq2tN4QEtyzsbEBk3ie&limit=100';
            $response = self::get($url);
            if(!json_decode($response))
            {
                break;
            }
            array_push($data,(array)json_decode($response));
            //$data += json_decode($response);
            $i++;
        }
        foreach($data as $value)
            foreach($value as $cell)
                foreach($cell as $item){
                    //dump(gettype($item));
                    $tmp = (array)$item;
                    if(array_key_exists("barcode", $tmp)){
                        Stock::insertStock($tmp);
                    }
                }
                    //Stock::insertStock((array)$item);
    }

    public function get($url){
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);

        $response = curl_exec($ch);
        curl_close($ch);
        
        return $response;
    }
}
