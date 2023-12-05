<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\Contracts\ObjectContract;
use InvalidArgumentException;
use LogicException;

class RichTextList implements ObjectContract
{
    /**
     * A string acting as a unique identifier for a block.
     *
     * If not specified, a block_id will be generated.
     *
     * You can use this block_id when you receive an interaction payload to identify the source of the action.
     */
    protected ?string $blockId = null;

    /**
     * An array of section objects.
     *
     * Maximum number of items is 10.
     *
     * @var \Illuminate\Notifications\Slack\Contracts\ElementContract[]
     */
    protected array $elements = [];

    /**
     * The style for the element
     *
     * Can be one of "bullet" or "ordered"
     */
    protected string $style = 'bullet';

    /**
     * Set the block identifier.
     */
    public function id(string $id): self
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * Add a section element to the block.
     */
    public function section(Closure $callback): self
    {
        $this->elements[] = $element = new RichTextSection();

        $callback($element);

        return $this;
    }

    /**
     * Sets the style
     */
    public function style(string $style): self
    {
        $this->style = $style;

        return $this;
    }

    /**
     * Get the instance as an array.
     */
    public function toArray(): array
    {
        if ($this->blockId && strlen($this->blockId) > 255) {
            throw new InvalidArgumentException('Maximum length for the block_id field is 255 characters.');
        }

        if (empty($this->elements)) {
            throw new LogicException('There must be at least one element in each rich text list block.');
        }

        if (count($this->elements) > 10) {
            throw new LogicException('There is a maximum of 10 elements in each rich text list block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'rich_text_list',
            'style' => $this->style,
            'elements' => array_map(fn (Arrayable $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
