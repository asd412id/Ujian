<?php

/**
 *	Admin Helper
 */
 namespace App\Modules\Admin;

 /**
  *
  */
 class Helper
 {

   function limitText($text,$limit=250)
   {
     if (str_word_count($text, 0) > $limit) {
          $words = str_word_count($text, 2);
          $pos = array_keys($words);
          $text = substr($text, 0, $pos[$limit]) . '...';
      }
      return $text;
   }

   public function bulan($bulan)
   {
     switch ($bulan) {
       case '01':
         $bln = 'Januari';
         break;
       case '02':
         $bln = 'Februari';
         break;
       case '03':
         $bln = 'Maret';
         break;
       case '04':
         $bln = 'April';
         break;
       case '05':
         $bln = 'Mei';
         break;
       case '06':
         $bln = 'Juni';
         break;
       case '07':
         $bln = 'Juli';
         break;
       case '08':
         $bln = 'Agustus';
         break;
       case '09':
         $bln = 'September';
         break;
       case '10':
         $bln = 'Oktober';
         break;
       case '11':
         $bln = 'Nopember';
         break;
       case '12':
         $bln = 'Desember';
         break;

       default:
         $bln = 'Bulan tidak terdaftar';
         break;
     }

     return $bln;
   }

   function getServerIP()
   {
     $ipv4 = [];
     if (strpos($_SERVER['HTTP_USER_AGENT'],'Windows NT') !== false) {
       $interfaceCommand = "ipconfig";
       $ipconfig = shell_exec($interfaceCommand);
       $listAdapter = explode("\n",trim($ipconfig));

       foreach ($listAdapter as $key => $adapter) {
         $cip = explode('IPv4 Address. . . . . . . . . . . : ',trim($adapter));
         if (count($cip) > 1) {
           $ipv4[$listAdapter[$key-4]] = $cip[1];
         }
       }

       return $ipv4;
     }else{
       $interfaceCommand = "/sbin/ifconfig | grep 'flags' | awk -F: '{print $1}'";
       $ifconfig1 = exec($interfaceCommand);
       $adapter = explode("\n",$ifconfig1);

       $interfaceCommand = "/sbin/ifconfig | grep 'inet ' | awk -F' ' '{print $2}'";
       $ifconfig2 = exec($interfaceCommand);
       $ipaddr = explode("\n",$ifconfig2);

       foreach ($adapter as $key => $adapt) {
         $ipv4[$adapt] = $ipaddr[$key];
       }

       return $ipv4;
     }
   }

 }
