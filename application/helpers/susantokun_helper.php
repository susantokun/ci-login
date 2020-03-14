<?php 

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Filename        : susantokun_helper.php
 * | Instagram       : @susantokun
 * | Blog            : http://www.susantokun.com
 * | Info            : http://info.susantokun.com
 * | Demo            : http://demo.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Saturday, 14th March 2020 7:10:38 pm
 * | Last Modified   : Saturday, 14th March 2020 7:10:55 pm
 * |==============================================================|
 */

function konfigurasi($title, $c_des=null)
{
    $CI = get_instance();
    $CI->load->model('Konfigurasi_model');
    $CI->load->model('Auth_model');
    $auth = $CI->Auth_model->get_by_id('id');
    $site = $CI->Konfigurasi_model->listing();
    $data = array(
        'title' => $title.' | '.$site['nama_website'],
        'logo' => $site['logo'],
        'favicon' => $site['favicon'],
        'email' => $site['email'],
        'no_telp' => $site['no_telp'],
        'alamat' => $site['alamat'],
        'facebook' => $site['facebook'],
        'instagram' => $site['instagram'],
        'keywords' => $site['keywords'],
        'metatext' => $site['metatext'],
        'about' => $site['about'],
        'site' => $site,
        'c_judul' => $title,
        'c_des' => $c_des,
        'userdata' => $auth,
    );

    return $data;
}
