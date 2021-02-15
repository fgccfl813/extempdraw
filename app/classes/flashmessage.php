<?php

class FlashMessage
{
    private $message;
    private $type;
    private $body = [];
    private static $types = ['primary', 'secondary', 'info', 'success', 'danger', 'warning', 'light', 'dark'];

    public function __construct(string $message = '', array $body = [], string $type = 'secondary')
    {
        $this->setMessage($message);
        $this->setBody($body);
        $this->setType($type);
    }

    public function publish()
    {
        printf('<div class="alert alert-%s" role="alert">' . "\n", $this->getType());
        printf('<h4 class="alert-heading">%s' . "\n<ul>\n", $this->getMessage());
        foreach ($this->body as $item) {
            printf("\t<li>%s</li>\n", $this->prettifyLink($item));
        }
        echo "</ul>\n</div>\n";
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getType()
    {
        return $this->type;
    }

    public function addBody(...$newBody)
    {
        foreach ($newBody as $item) {
            $this->body[] = $item;
        }
    }

    public function setMessage(string $newMessage) {
        $this->message = $newMessage;
    }

    public function setType(string $newType)
    {
        $newType = trim(strtolower($newType));
        if (in_array($newType, self::$types)) {
            $this->type = $newType;
        }
    }

    public function setBody(array $newBody = [])
    {
        $this->body = (count($newBody)) ? $newBody : [];
    }

    private function prettifyLink($text)
    {
        return str_replace('<a ', '<a class="alert-link" ', $text);
    }

}