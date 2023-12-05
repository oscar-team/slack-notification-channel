<?php

namespace Illuminate\Notifications\Slack\BlockKit\Composites;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\Contracts\ObjectContract;
use InvalidArgumentException;
use LogicException;

class RichTextPreformatted implements ObjectContract
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
     * An array of text objects.
     *
     * Maximum number of items is 10.
     *
     * @var \Illuminate\Notifications\Slack\Contracts\ElementContract[]
     */
    protected array $elements = [];

    /**
     * Set the block identifier.
     */
    public function id(string $id): self
    {
        $this->blockId = $id;

        return $this;
    }

    /**
     * Add a text element to the block.
     */
    public function text(string $text): RichTextObject
    {
        return tap(new RichTextObject($text), function (RichTextObject $element) {
            $this->elements[] = $element;
        });
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
            throw new LogicException('There must be at least one element in each rich text preformatted block.');
        }

        if (count($this->elements) > 10) {
            throw new LogicException('There is a maximum of 10 elements in each rich text preformatted block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'rich_text_preformatted',
            'elements' => array_map(fn (Arrayable $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
