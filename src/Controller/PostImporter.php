<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Template;
use DateTime;

class PostImporter extends Controller
{
    /**
     * @var array<Post>
     */

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts Importer';
        $context->error = $this->error;
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostImporter();
    }

    public function importPost($post): void
    {
        $id = $post['id'];
        $title = $post['title'];
        $body = $post['body'];
        $created_at_iso = new DateTime($post['created_at']);
        $created_at = $created_at_iso->format('Y-m-d H:i:s');
        $modified_at_iso = new DateTime($post['modified_at']);
        $modified_at = $modified_at_iso->format('Y-m-d H:i:s');
        $author = $post['author'];

        $sql = 'INSERT INTO posts VALUES ("' . $id . '","' . $title . '","' . $body . '","' . $created_at . '","' . $modified_at . '","' . $author . '")';

        if($this->db->query($sql)){
            $this->error = 'Success!';
        } else {
            $this->error = 'A database error occured.';
        }
    }

    protected function loadData(): void
    {
        /*Takes form input from /import page and handles both single and multiple entries in JSON format. Needed to change the iso format for the created_at and modified_at to match the accepted datetime for my MySQL database as they did not convert automatically. 
        
        Things I would have improved if I have the time: 
            - Better security for the page since at the moment it is just a password field that accepts "silverorange" to input data in the database. 
            - Ability to do a JSON file upload instead of a text field. 
            - Better error catching for database errors when query fails -- at the moment it is not working. 
        */
        $this->error = '';

        if($_POST&&$_POST['password'] == 'silverorange'){
            $file = $_POST['import_file'];
            $php_object = json_decode($file, true);
            $count = substr_count($file, '{');
            if($php_object != null){
                //for JSON with multiple entries
                if($count>1){
                    foreach($php_object as $post){
                        $this->importPost($post);
                    }

                //for single entries
                }else{
                    $this->importPost($php_object);
                }
            }else{
                $this->error = "ERROR: Invalid input";
            }
        } elseif($_POST&&$_POST['password'] != 'silverorange'){
            $this->error = 'ERROR: Incorrect password';
        }
    }
}
