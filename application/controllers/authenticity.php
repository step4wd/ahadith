<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authenticity extends CI_Controller {
  
  function _remap( $method, $param ) {
			
    if( $method == 'display' ):
      $this->display();
      
    elseif( $method == 'read' ):
      
      //set default for parameter authenticity_id
	  if( !isset( $param[0] ) ):
		$param[0] = '';
	  endif;
      
      $this->read( $param[0] );
      
    elseif( $method == 'add' ):
      $this->add();
      
    elseif( $method == 'update' ):
    
      //set default for parameter authenticity_id
	  if( !isset( $param[0] ) ):
		$param[0] = '';
	  endif;
      
      $this->update( $param[0] );
    elseif( $method == 'delete' ):
      
      //set default for parameter authenticity_id
	  if( !isset( $param[0] ) ):
		$param[0] = '';
	  endif;
      
      $this->delete( $param[0] );
        
    //for all other method names, display an error message
    else:
        $list['error_msg'] = "The Page you are trying to view does not exists. Use the menu if you have access.";
        $list['main_content'] = "message_view";
        $this->load->view('includes/template', $list);
    endif;
  }
  /* Display all columns of authenticity table
  *
  * @return none
  */
  public function display(){
	
	  //limit view authenticity only to authorized
	 if( !$this->user_roles->is_authorized( array('admin_authenticity_view') ) ):
        $list['error_msg'] = "You are not authorized to view Authenticity.";
        $list['main_content'] = "message_view";
        $this->load->view('includes/template', $list);
        return;
    endif;
	
    $this->load->helper('form');
    $this->load->model('authenticity_model');
    $list['ahadith'] = $this->authenticity_model->get_authenticity();
    $list['main_content'] = 'admin/display_authenticity_view'; 
    $this->load->view('admin/includes/template',$list);

  }
  
  /* Read columns of authenticity table
   *
   *@param string $authenticity_id Id of authenticity
   *
   *@return none
   */

  public function read($authenticity_id){
    $this->load->model('authenticity_model');
    $list['authenticity'] =  $this->authenticity_model->get_authenticity_by_id($authenticity_id);
    $list['main_content'] = 'read_authenticity_view';
    $this->load->view('includes/template',$list);

  }
  
  /* Add a new row of authenticity table
   *
   * @return none
   */

  public function add(){
	
	   //limit adding of authenticity only to authorized
	 if( !$this->user_roles->is_authorized( array('admin_authenticity_add') ) ):
        $list['error_msg'] = "You are not authorized to Add Authenticity.";
        $list['main_content'] = "message_view";
        $this->load->view('includes/template', $list);
        return;
    endif;
	
    $this->load->helper('form');
    
    $this->load->library('form_validation');
	  
     
    $this->form_validation->set_rules('txt_title_ar', 'Arabic Title', 'trim|max_length[20]');
    $this->form_validation->set_rules('txt_title_en', 'English Title', 'trim|required|max_length[20]');
    $this->form_validation->set_rules('txt_title_ur', 'Urdu Title', 'trim|max_length[20]');
    $this->form_validation->set_rules('txt_order', 'Order', 'trim|integer|max_length[4]');			      
				      
    $list['main_content'] = 'admin/add_authenticity_view';
    
    if ($this->form_validation->run() == FALSE):
      $this->load->view('admin/includes/template',$list);
    else:
        $data['authenticity_title_ar'] = $this->input->post('txt_title_ar');
        $data['authenticity_title_en'] = $this->input->post('txt_title_en');
        $data['authenticity_title_ur'] = $this->input->post('txt_title_ur');
        $data['authenticity_order'] = $this->input->post('txt_order');
  
        $this->load->model('authenticity_model');
        
        $this->authenticity_model->insert_authenticity($data);
  
        redirect('admin/authenticity');

    endif;

  }
  
  /* update a row in authenticity table
   *
   *@param string $autenticity_id Id of authenticity
   *
   *@return none
   */

  public function update($authenticity_id){
	
	  //limit updating of authenticity only to authorized
	 if( !$this->user_roles->is_authorized( array('admin_authenticity_edit') ) ):
        $list['error_msg'] = "You are not authorized to Edit Authenticity.";
        $list['main_content'] = "message_view";
        $this->load->view('includes/template', $list);
        return;
    endif;
	
    $this->load->model('authenticity_model');
    
    if( $this->authenticity_model->get_authenticity_by_id($authenticity_id) == FALSE ):
      $list['error_msg'] = "No record found for the provided Authenticity ID. Use the menu if you have access.";
      $list['main_content'] = "message_view";
      $this->load->view('admin/includes/template', $list);
      return;
    endif;
    
    $list['authenticity_id'] = $authenticity_id;
    $list['authenticity'] =  $this->authenticity_model->get_authenticity_by_id($authenticity_id);
    $this->load->helper('form');
    
    $this->load->library('form_validation');
    $this->form_validation->set_rules('txt_title_ar', 'Arabic Title', 'trim|max_length[20]');
    $this->form_validation->set_rules('txt_title_en', 'English Title', 'trim|required|max_length[20]');
    $this->form_validation->set_rules('txt_title_ur', 'Urdu Title', 'trim|max_length[20]');
    $this->form_validation->set_rules('txt_order', 'Order', 'trim|integer|max_length[4]');			      
    
    
    $list['main_content'] = 'admin/update_authenticity_view';
    if ($this->form_validation->run() == FALSE):
    $this->load->view('admin/includes/template',$list);
    
    
    else:
    //if( !empty($this->input->post('mysubmit'))):
      $authenticity['authenticity_title_ar'] = $this->input->post('txt_title_ar');
      $authenticity['authenticity_title_en'] = $this->input->post('txt_title_en');
      $authenticity['authenticity_title_ur'] = $this->input->post('txt_title_ur');
      $authenticity['authenticity_order'] = $this->input->post('txt_order');

      $this->load->model('authenticity_model');
      $this->authenticity_model->update_authenticity($authenticity_id,$authenticity);
      
      redirect('admin/authenticity');
      //echo "Successfully updated";
    endif;

  }
  
  /* Delete a row in authenticity table
   *
   *@param string $authenticity_id ID of authenticity
   *
   *@return none
   */

  public function delete( $authenticity_id ){
	
	  //limit deleting of authenticity only to authorized
		if( !$this->user_roles->is_authorized( array('admin_authenticity_delete') ) ):
		   $list['error_msg'] = "You are not authorized to Delete authenticity.";
		   $list['main_content'] = "message_view";
		   $this->load->view('includes/template', $list);
		   return;
	   endif;

    $this->load->model('authenticity_model');
    
    if( $this->authenticity_model->get_authenticity_by_id($authenticity_id) == FALSE ):
      $list['error_msg'] = "No record found for the provided Authenticity ID. Use the menu if you have access.";
      $list['main_content'] = "message_view";
      $this->load->view('admin/includes/template', $list);
      return;
    endif;
    
    $this->authenticity_model->delete_authenticity( $authenticity_id );
    redirect('admin/authenticity');

    //echo "Deleted";
  }

}
