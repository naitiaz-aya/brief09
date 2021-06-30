<?php
  /** 
   
   *@package MyFormPlugin
  
   */

  /*

   *Plugin Name: My Form Plugin
   * Plugin URI: https://github.com/naitiaz-aya/brief09
   * Description: This plugin adds contact form functionality to the pages where you want to have a contact form.
   * Version: 1.0
   * Author: Aya Nait-iaz
   * Author URI: https://github.com/naitiaz-aya
   *Text Domain: MyFormPlugin

  */


  
  wp_register_style( 'namespace', 'https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css' );
  
  add_action('admin_menu', 'menuAdded');
  
  function menuAdded()
  {
      add_menu_page('Goo Contact Page', 'Go Contact', 'manage_options', 'go-contact', 'formInit','dashicons-database-add',5);
  }
  
  
  function formInit()
  {
  wp_enqueue_style('namespace'); 
  
  
      echo '<h1>Hi There,</h1>';
  
      $content = '';
      $content .= '<form method ="post" action = "">';
  
      $content .= '<div>';
      $content .= '<input type = "checkbox" name ="name" id="name1" value="true" />';
      $content .= '<label class="form-label" for ="name"> Name : </label>';
      $content .= '</div>';
  
      $content .= '<div>';
      $content .= '<input type = "checkbox" name ="email" value="true" class="form-control"/>';
      $content .= '<label for ="email"> Email : </label>';
      $content .= '</div>';
  
      $content .= '<div>';
      $content .= '<input type = "checkbox" name ="subject" value="true" class="form-control"/>';
      $content .= '<label for ="subject"> Subject : </label>';
      $content .= '</div>';
  
      $content .= '<div>';
      $content .= '<input type = "checkbox" name ="message" value="true"  class="form-control"/>';
      $content .= '<label for ="message"> Message : </label>';
      $content .= '</div>';
  
      $content .= '<input class="btn btn-primary" type="submit" name="submit-contact" class=" btn btn-md" value= "Submit"/>';
  
      $content .= '</form>';
  
      echo $content;
  
      
  }
  
  function form()
  {
  
      getData();
  
  
      echo '<form action="" method="post">';
  
      if (getData()->name) {
  
          echo 'Your Name  <br />';
          echo '<input type="text" name="name" size="40" /><br>';
      }
      if (getData()->email) {
  
          echo 'Your Email  <br />';
          echo '<input type="email" name="email" size="40" /><br>';
      }
      if (getData()->subject) {
  
          echo 'Subject <br />';
          echo '<input type="text" name="subject" size="40" /><br>';
      }
      if (getData()->message) {
  
          echo 'Your Message  <br />';
          echo '<textarea rows="10" cols="35" name="message"></textarea><br>';
      }
  
      echo '<p><input type="submit" name="sendbtn" value="Send"/></p>';
      echo '</form>';
  
    }
  
  function createtable()
  {
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $tablename = 'myformfields';
      $sql = "CREATE TABLE $wpdb->base_prefix$tablename (
          id INT,
          name BOOLEAN,
          email BOOLEAN,
          subject BOOLEAN,
          message BOOLEAN
          ) $charset_collate;";
  
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  
      maybe_create_table($wpdb->base_prefix . $tablename, $sql);
  }
  
  function createDataTable()
  {
      global $wpdb;
      $charset_collate = $wpdb->get_charset_collate();
      $tablename = 'myformdata';
      $sql = "CREATE TABLE $wpdb->base_prefix$tablename (
           id INT AUTO_INCREMENT,
          name varchar(255) DEFAULT null,
          email varchar(255) DEFAULT null,
          subject varchar(255) DEFAULT null,
          message varchar(255) DEFAULT null,
          PRIMARY key(id)
          ) $charset_collate;";
  
      require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
  
      maybe_create_table($wpdb->base_prefix . $tablename, $sql);
  }
  
  
  function insertData()
  {
      global $wpdb;
      $wpdb->insert(
          'wp_myformfields',
          [
              'id' => 1,
              'name' => true,
              'email' => true,
              'subject' => true,
              'message' => true
          ]
      );
  }
  
  function getData(){
  
      global $wpdb;
      $fields = $wpdb->get_row("SELECT * FROM wp_myformfields WHERE id = 1;");
      return $fields;
  }
  
  
  if (isset($_POST['submit-contact'])) {
  
  
      $name = filter_var($_POST['name']?? false, FILTER_VALIDATE_BOOLEAN);
      $email = filter_var($_POST['email']?? false, FILTER_VALIDATE_BOOLEAN);
      $subject = filter_var($_POST['subject']?? false, FILTER_VALIDATE_BOOLEAN);
      $message = filter_var($_POST['message']?? false, FILTER_VALIDATE_BOOLEAN);
  
      global $wpdb;
      $wpdb->update(
          'wp_myformfields',
          [
              'name' => $name,
              'email' => $email,
              'subject' => $subject,
              'message' => $message
          ],
          ['id' => 1]
      );
  }
  
  if (isset($_POST['sendbtn'])) {
      $arr = $_POST;
      unset($arr['sendbtn']);
  
  
      global $wpdb;
      $wpdb->insert(
          'wp_myformdata',
          $arr
      );
  }
  function shortCode()
  {
      ob_start();
      form();
  
      return ob_get_clean();
  }
  
  add_shortcode('myform', 'shortCode');
  
  
  register_activation_hook(__FILE__, 'createDataTable');
  register_activation_hook(__FILE__, 'createtable');
  register_activation_hook(__FILE__, 'insertData');
  


