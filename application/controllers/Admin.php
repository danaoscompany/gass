<?php

include "Util.php";

class Admin extends CI_Controller {

	public function login() {
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		$expiry = $this->input->post('expiry');
		$admins = $this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "' AND `password`='" . $password . "'")->result_array();
		if (sizeof($admins) > 0) {
			$admin = $admins[0];
			echo json_encode(array(
				'response_code' => 1,
				'user_id' => intval($admin['id'])
			));
		} else {
			echo json_encode(array(
				'response_code' => -2
			));
		}
	}

	public function get_users() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$users = $this->db->query("SELECT * FROM `users` ORDER BY `email` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}

	public function get_all_users() {
		$users = $this->db->query("SELECT * FROM `users` ORDER BY `email` ASC")->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}

	public function get_users_by_email() {
		$email = $this->input->post('email');
		$users = $this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->result_array();
		for ($i=0; $i<sizeof($users); $i++) {
		}
		echo json_encode($users);
	}
	
	public function add_user() {
		$email = $this->input->post('email');
		$role = intval($this->input->post('role'));
		if ($this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->insert('users', array(
			'email' => $email,
			'role' => $role
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function update_user() {
		$id = intval($this->input->post('id'));
		$email = $this->input->post('email');
		if ($this->db->query("SELECT * FROM `users` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->where('id', $id);
		$this->db->update('users', array(
			'email' => $email
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function delete_user() {
		$id = intval($this->input->post('id'));
		$this->db->where('id', $id);
		$this->db->delete('users');
	}

	public function get_admins() {
		$start = intval($this->input->post('start'));
		$length = intval($this->input->post('length'));
		$admins = $this->db->query("SELECT * FROM `admins` ORDER BY `email` ASC LIMIT " . $start . "," . $length)->result_array();
		for ($i=0; $i<sizeof($admins); $i++) {
		}
		echo json_encode($admins);
	}

	public function get_all_admins() {
		$admins = $this->db->query("SELECT * FROM `admins` ORDER BY `email` ASC")->result_array();
		for ($i=0; $i<sizeof($admins); $i++) {
		}
		echo json_encode($admins);
	}
	
	public function add_admin() {
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if ($this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "'")->num_rows() > 0) {
			echo json_encode(array(
				'response_code' => -1
			));
			return;
		}
		$this->db->insert('admins', array(
			'name' => $name,
			'email' => $email,
			'password' => $password
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function update_admin() {
		$id = intval($this->input->post('id'));
		$changed = intval($this->input->post('changed'));
		$name = $this->input->post('name');
		$email = $this->input->post('email');
		$password = $this->input->post('password');
		if ($changed == 1) {
			if ($this->db->query("SELECT * FROM `admins` WHERE `email`='" . $email . "'")->num_rows() > 0) {
				echo json_encode(array(
					'response_code' => -1
				));
				return;
			}
		}
		$this->db->where('id', $id);
		$this->db->update('admins', array(
			'name' => $name,
			'email' => $email,
			'password' => $password
		));
		echo json_encode(array(
			'response_code' => 1
		));
	}
	
	public function delete_admin() {
		$id = intval($this->input->post('id'));
		$this->db->where('id', $id);
		$this->db->delete('admins');
	}
    
    public function get_locations_by_type() {
    	$type = $this->input->post('type');
    	echo json_encode($this->db->query("SELECT * FROM `locations` WHERE `type`='" . $type . "'")->result_array());
    }
    
    public function get_reports() {
    	$type = $this->input->post('type');
    	echo json_encode($this->db->query("SELECT * FROM `reports` WHERE `type`='" . $type . "'")->result_array());
    }
    
    public function update_document() {
    	$type = $this->input->post('type');
    	$config = array(
			'upload_path' => './userdata/',
			'allowed_types' => "*",
			'overwrite' => TRUE,
			'file_name' => Util::generateUUIDv4()
		);
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
			$this->db->where('type', $type);
			$this->db->update('documents', array(
				'path' => $this->upload->data()['file_name']
			));
		} else {
			echo json_encode($this->upload->display_errors());
		}
    }
    
    public function add_potensi_bencana() {
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$subtype = $this->input->post('subtype');
    	$this->db->insert('locations', array(
    		'title' => $title,
    		'type' => 'bencana',
    		'subtype' => $subtype,
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function add_relokasi_personel() {
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->insert('locations', array(
    		'title' => $title,
    		'type' => 'relokasi_personel',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function add_titik_kumpul() {
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->insert('locations', array(
    		'title' => $title,
    		'type' => 'kumpul',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function add_jalur_evakuasi() {
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->insert('locations', array(
    		'title' => $title,
    		'type' => 'jalur_evakuasi',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function update_potensi_bencana() {
    	$id = $this->input->post('id');
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$subtype = $this->input->post('subtype');
    	$this->db->where('id', $id);
    	$this->db->update('locations', array(
    		'title' => $title,
    		'type' => 'bencana',
    		'subtype' => $subtype,
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function update_relokasi_personel() {
    	$id = $this->input->post('id');
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->where('id', $id);
    	$this->db->update('locations', array(
    		'title' => $title,
    		'type' => 'relokasi_personel',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function update_titik_kumpul() {
    	$id = $this->input->post('id');
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->where('id', $id);
    	$this->db->update('locations', array(
    		'title' => $title,
    		'type' => 'kumpul',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function update_jalur_evakuasi() {
    	$id = $this->input->post('id');
    	$title = $this->input->post('title');
    	$lat = doubleval($this->input->post('lat'));
    	$lng = doubleval($this->input->post('lng'));
    	$address = $this->input->post('address');
    	$this->db->where('id', $id);
    	$this->db->update('locations', array(
    		'title' => $title,
    		'type' => 'jalur_evakuasi',
    		'lat' => $lat,
    		'lng' => $lng,
    		'address' => $address
    	));
    }
    
    public function delete_potensi_bencana() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `locations` WHERE `id`=" . $id);
    }
    
    public function delete_relokasi_personel() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `locations` WHERE `id`=" . $id);
    }
    
    public function delete_titik_kumpul() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `locations` WHERE `id`=" . $id);
    }
    
    public function delete_jalur_evakuasi() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `locations` WHERE `id`=" . $id);
    }
    
    public function get_potensi_bencana_by_id() {
    	$id = $this->input->post('id');
    	echo json_encode($this->db->query("SELECT * FROM `locations` WHERE `id`=" . $id)->row_array());
    }
    
    public function get_relokasi_personel_by_id() {
    	$id = $this->input->post('id');
    	echo json_encode($this->db->query("SELECT * FROM `locations` WHERE `id`=" . $id)->row_array());
    }
    
    public function get_titik_kumpul_by_id() {
    	$id = $this->input->post('id');
    	echo json_encode($this->db->query("SELECT * FROM `locations` WHERE `id`=" . $id)->row_array());
    }
    
    public function get_jalur_evakuasi_by_id() {
    	$id = $this->input->post('id');
    	echo json_encode($this->db->query("SELECT * FROM `locations` WHERE `id`=" . $id)->row_array());
    }
    
    public function add_berita() {
    	$title = $this->input->post('title');
    	$url = $this->input->post('url');
    	$this->db->insert('websites', array(
    		'title' => $title,
    		'url' => $url
    	));
    }
    
    public function update_berita() {
    	$id = $this->input->post('id');
    	$title = $this->input->post('title');
    	$url = $this->input->post('url');
    	$this->db->where('id', $id);
    	$this->db->update('websites', array(
    		'title' => $title,
    		'url' => $url
    	));
    }
    
    public function delete_berita() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `websites` WHERE `id`=" . $id);
    }
    
    public function get_berita_by_id() {
    	$id = $this->input->post('id');
    	echo json_encode($this->db->query("SELECT * FROM `websites` WHERE `id`=" . $id)->row_array());
    }
    
    public function add_penpas() {
    	$title = $this->input->post('title');
    	$config = array(
			'upload_path' => './userdata/',
			'allowed_types' => "*",
			'overwrite' => TRUE,
			'file_name' => Util::generateUUIDv4()
		);
		$this->load->library('upload', $config);
		if ($this->upload->do_upload('file')) {
	    	$this->db->insert('penpas', array(
	    		'title' => $title,
	    		'path' => $this->upload->data()['file_name']
	    	));
    	}
    }
    
    public function update_penpas() {
    	$id = intval($this->input->post('id'));
    	$title = $this->input->post('title');
    	$documentChanged = intval($this->input->post('document_changed'));
    	if ($documentChanged == 0) {
    		$this->db->where('id', $id);
		    $this->db->update('penpas', array(
		    	'title' => $title
		    ));
    	} else if ($documentChanged == 1) {
	    	$config = array(
				'upload_path' => './userdata/',
				'allowed_types' => "*",
				'overwrite' => TRUE,
				'file_name' => Util::generateUUIDv4()
			);
			$this->load->library('upload', $config);
			if ($this->upload->do_upload('file')) {
				$this->db->where('id', $id);
		    	$this->db->update('penpas', array(
		    		'title' => $title,
		    		'path' => $this->upload->data()['file_name']
		    	));
		    	echo "UPDATE `penpas` SET `title`='" . $title . "', `path`='" . $this->upload->data()['file_name'] . "' WHERE `id`=" . $id;
	    	} else {
	    		echo json_encode($this->upload->display_errors());
	    	}
    	}
    }
    
    public function delete_penpas() {
    	$id = $this->input->post('id');
    	$this->db->query("DELETE FROM `penpas` WHERE `id`=" . $id);
    }
}
