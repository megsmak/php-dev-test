<?php

namespace silverorange\DevTest\Controller;

use silverorange\DevTest\Context;
use silverorange\DevTest\Model\Post;
use silverorange\DevTest\Template;
use PDO;

class PostIndex extends Controller
{
    /**
     * @var array<Post>
     */
    private array $posts = [];

    public function getContext(): Context
    {
        $context = new Context();
        $context->title = 'Posts';
        $context->content = strval(count($this->posts));
        $context->body = $this->getPosts($this->posts);
        return $context;
    }

    public function getTemplate(): Template\Template
    {
        return new Template\PostIndex();
    }

    // Function to render all the posts for Posts page in HTML
    public function getPosts($posts) : string 
    {
        $content = '';

        foreach($posts as $post){

            $author = new Post();
            $author_name = $this->getAuthorName($post['author']);

            $content.= '<h1> <a href="/posts/' . $post['id'] . '">' . $post['title'] . '</a></h1>';
            $content.= '<p>Written by ' . $this->getAuthorName($post['author']) .' on ' . $post['created_at'] . '</p>';
        }

        return $content; 
    }

    // This is a repeat function from PostDetails to find the author name. Ideally I would like this move this somewhere so there isn't repeated code in both controllers. 
    public function getAuthorName($id) : string
    {
        $sql = "SELECT `full_name` FROM authors WHERE `id` LIKE '" . $id . "' LIMIT 1;";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['full_name'];
    }

    protected function loadData(): void
    {
        $this->posts = [];

        $sql = "SELECT * FROM posts ORDER BY `created_at` DESC";
        $result = $this->db->query($sql);
        $results_array = $result->fetchAll(PDO::FETCH_ASSOC);
        $results_count = count($results_array);
        
        foreach($results_array as $result) {
            array_push($this->posts, $result);
            echo "<br>";
        }
    
    }
}
