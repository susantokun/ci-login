<?php 

defined('BASEPATH') or exit('No direct script access allowed');

/*
 * |==============================================================|
 * | Please DO NOT modify this information :                      |
 * |--------------------------------------------------------------|
 * | Author          : Susantokun
 * | Email           : admin@susantokun.com
 * | Filename        : Auth.php
 * | Instagram       : @susantokun
 * | Blog            : http://www.susantokun.com
 * | Info            : http://info.susantokun.com
 * | Demo            : http://demo.susantokun.com
 * | Youtube         : http://youtube.com/susantokun
 * | File Created    : Friday, 13th March 2020 3:37:45 am
 * | Last Modified   : Friday, 13th March 2020 3:40:47 am
 * |==============================================================|
 */

class Auth extends MY_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model('Auth_model');
    }

    public function check_account()
    {
        //validasi login
        $email      = $this->input->post('email');
        $password   = $this->input->post('password');

        //ambil data dari database untuk validasi login
        $query = $this->Auth_model->check_account($email, $password);

        if ($query === 1) {
            $this->session->set_flashdata('alert', '<p class="box-msg">
        			<div class="info-box alert-danger">
        			<div class="info-box-icon">
        			<i class="fa fa-warning"></i>
        			</div>
        			<div class="info-box-content" style="font-size:14">
        			<b style="font-size: 20px">GAGAL</b><br>Email yang Anda masukkan tidak terdaftar.</div>
        			</div>
        			</p>
            ');
        } elseif ($query === 2) {
            $this->session->set_flashdata('alert', '<p class="box-msg">
              <div class="info-box alert-info">
              <div class="info-box-icon">
              <i class="fa fa-info-circle"></i>
              </div>
              <div class="info-box-content" style="font-size:14">
              <b style="font-size: 20px">GAGAL</b><br>Akun yang Anda masukkan tidak aktif, silakan hubungi Administrator.</div>
              </div>
              </p>'
            );
        } elseif ($query === 3) {
            $this->session->set_flashdata('alert', '<p class="box-msg">
        			<div class="info-box alert-danger">
        			<div class="info-box-icon">
        			<i class="fa fa-warning"></i>
        			</div>
        			<div class="info-box-content" style="font-size:14">
        			<b style="font-size: 20px">GAGAL</b><br>Password yang Anda masukkan salah.</div>
        			</div>
        			</p>
              ');
        } else {
            //membuat session dengan nama userData yang artinya nanti data ini bisa di ambil sesuai dengan data yang login
            $userdata = array(
              'is_login'    => true,
              'id'          => $query->id,
              'password'    => $query->password,
              'id_role'     => $query->id_role,
              'username'    => $query->username,
              'first_name'  => $query->first_name,
              'last_name'   => $query->last_name,
              'email'       => $query->email,
              'phone'       => $query->phone,
              'photo'       => $query->photo,
              'created_on'  => $query->created_on,
              'last_login'  => $query->last_login
            );
            $this->session->set_userdata($userdata);
            return true;
        }
    }
    public function login()
    {
        $site = $this->Konfigurasi_model->listing();
        $data = array(
            'title'     => 'Login | '.$site['nama_website'],
            'favicon'   => $site['favicon'],
            'site'      => $site
        );
        //melakukan pengalihan halaman sesuai dengan levelnya
        if ($this->session->userdata('id_role') == "1") {
            redirect('admin/home');
        }
        if ($this->session->userdata('id_role') == "2") {
            redirect('member/home');
        }

        //proses login dan validasi nya
        if ($this->input->post('submit')) {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|min_length[5]|max_length[50]');
            $this->form_validation->set_rules('password', 'Password', 'trim|required|min_length[5]|max_length[22]');
            $error = $this->check_account();

            if ($this->form_validation->run() && $error === true) {
                $data = $this->Auth_model->check_account($this->input->post('email'), $this->input->post('password'));

                //jika bernilai TRUE maka alihkan halaman sesuai dengan level nya
                if ($data->id_role == '1') {
                    redirect('admin/home');
                } elseif ($data->id_role == '2') {
                    redirect('member/home');
                }
            } else {
                $this->template->load('authentication/layouts/template', 'authentication/login', $data);
            }
        } else {
            $this->template->load('authentication/layouts/template', 'authentication/login', $data);
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}
