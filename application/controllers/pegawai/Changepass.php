<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Changepass extends CI_Controller {
	
	function __construct()
	{
		parent::__construct();
		if($this->session->userdata('level') != 'pegawai'){
			redirect('auth/kicked');
			exit;
		}
	}

	public function index()
	{

		$data = $this->Pegawai_model->ambil_data($this->session->userdata['username']);

		$data = array(
			'username' => $data->username,
            'foto_pegawai' => $data->foto_pegawai,
			'level' => 'pegawai',
            'judul' => 'Ganti Password Akun',
            'judul2' => 'Profil Saya',
            'judul3' => 'Ganti Password Akun',
			
		);
        $this->load->view('pegawai/changepass', $data);
		
	}

    public function gantiPassAksi()
    {
        $passBaru = $this->input->post('passBaru');
		$ulangPass = $this->input->post('ulangPass');
        $this->form_validation->set_rules('passBaru', 'Password', 'required|min_length[8]|max_length[16]|trim|xss_clean|matches[ulangPass]'
			,array(
				'required'      => '%s wajib diisi.',
				'min_length'      => '%s harus terdiri dari minimal 8 karakter.',
				'max_length'      => '%s harus terdiri dari maksimal 16 karakter.',
				'matches'     => '%s harus sama dengan konfirmasi password.'
		));
		$this->form_validation->set_rules('ulangPass', 'Konfirmasi password', 'required|min_length[8]|max_length[16]|trim|xss_clean|matches[passBaru]'
			,array(
				'required'      => '%s wajib diisi.',
				'min_length'      => '%s harus terdiri dari minimal 8 karakter.',
				'max_length'      => '%s harus terdiri dari maksimal 16 karakter.',
				'matches'     => '%s harus sama dengan password baru.'
		));
		$this->session->set_flashdata('pesan', '<div class="alert alert-danger alert-dismissible " role="alert">
			<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
			</button>
			Gagal. Terdapat kesalahan saat mengganti password.</div>');
        if ($this->form_validation->run() == FALSE)
        {
            
            $this->index();
            return;
        }else{
            $data = array('password' => md5($passBaru));
            $id = $this->Pegawai_model->ambil_data($this->session->userdata['username']);
            $id = array('username' => $id->username);

            $this->Pegawai_model->update_data('pegawai',$data,$id);
            
            redirect('auth/changed');
        }
    }
}