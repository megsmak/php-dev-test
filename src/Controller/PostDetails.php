<?php

namespace silverorange\DevTest\Controller;

use PDO;
use silverorange\DevTest\Context;
use silverorange\DevTest\Template;
use silverorange\DevTest\Model;

class PostDetails extends Controller
{
    /**
     * TODO: When this property is assigned in loadData this PHPStan override
     * can be removed.
     *
     * @phpstan-ignore property.unusedType
     */
    private ?Model\Post $post = null;

    public function getContext(): Context
    {
        $context = new Context();

        if ($this->post === null) {
            $context->title = 'Not Found';
            $context->content = "A post with id {$this->params[0]} was not found.";
        } else {
            $context->title = $this->post->title;
            $context->content = $this->params[0];
            $context->body = $this->post->body;
            $context->created_at = $this->post->created_at;
            $context->modified_at = $this->post->modified_at;
            $context->author = $this->getAuthorName($this->post->author);
        }

        return $context;
    }

    public function getTemplate(): Template\Template
    {
        if ($this->post === null) {
            return new Template\NotFound();
        }

        return new Template\PostDetails();
    }

    public function getStatus(): string
    {
        if ($this->post === null) {
            return $this->getProtocol() . ' 404 Not Found';
        }

        return $this->getProtocol() . ' 200 OK';
    }

    public function setPost($post) : Model\Post
    {
        $result = new Model\Post();
        $result->id = $post['id'];
        $result->title = $post['title'];
        $result->body = $post['body'];
        $result->created_at = $post['created_at'];
        $result->modified_at = $post['modified_at'];
        $result->author = $post['author'];

        return $result;
    }

    public function getAuthorName($id) : string
    {
        $sql = "SELECT `full_name` FROM authors WHERE `id` LIKE '" . $id . "' LIMIT 1;";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        return $row['full_name'];
    }

    protected function loadData(): void
    {
        $sql = "SELECT * FROM posts WHERE `id` LIKE '" . $this->params[0] . "' LIMIT 1;";
        $result = $this->db->query($sql);
        $row = $result->fetch(PDO::FETCH_ASSOC);
        $this->post = $this->setPost($row);
    }
}
