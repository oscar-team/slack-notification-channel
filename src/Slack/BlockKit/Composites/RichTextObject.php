<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Support\Arr;

class RichTextObject extends PlainTextOnlyTextObject
{
    /**
     * The formatting to use for this text object.
     *
     * Can be one of "text", "link" or "emoji".
     */
    protected string $type = 'text';

    /**
     * Link to be used in case the type is link
     */
    protected ?string $link = null;

    /**
     * The style for the element
     */
    protected array $style = [];

    /**
     * Sets the type to "emoji"
     */
    public function emoji(): self
    {
        $this->type = 'emoji';

        return $this;
    }

    /**
     * Sets the type to "link"
     */
    public function link(string $link): self
    {
        $this->type = 'link';

        return $this;
    }

    /**
     * Sets the style
     */
    public function style(array $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Sets the value of a style
     */
    public function setStyle(string $key, mixed $value): self
    {
        $this->style[$key] = $value;

        return $this;
    }

    /**
     * Removes the value of a style
     */
    public function removeStyle(string $key): self
    {
        if (isset($this->style[$key])) {
            unset($this->style[$key]);
        }

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        $built = array_merge(parent::toArray(), [
            'type' => $this->type,
        ]);

        return $built;
    }
}
