<?php

namespace Illuminate\Notifications\Slack\BlockKit\Blocks;

use Closure;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Notifications\Slack\BlockKit\Composites\RichTextList;
use Illuminate\Notifications\Slack\BlockKit\Composites\RichTextPreformatted;
use Illuminate\Notifications\Slack\BlockKit\Composites\RichTextQuote;
use Illuminate\Notifications\Slack\BlockKit\Composites\RichTextSection;
use Illuminate\Notifications\Slack\Contracts\BlockContract;
use InvalidArgumentException;
use LogicException;

class RichTextBlock implements BlockContract
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
     * An array of image elements and text objects.
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
     * Add a rich text section to the block.
     */
    public function section(Closure $callback): self
    {
        $this->elements[] = $element = new RichTextSection();

        $callback($element);

        return $this;
    }

    /**
     * Add a rich text preformatted to the block.
     */
    public function preformatted(Closure $callback): self
    {
        $this->elements[] = $element = new RichTextPreformatted();

        $callback($element);

        return $this;
    }

    /**
     * Add a rich text quote to the block.
     */
    public function quote(Closure $callback): self
    {
        $this->elements[] = $element = new RichTextQuote();

        $callback($element);

        return $this;
    }

    /**
     * Add a rich text list to the block.
     */
    public function list(Closure $callback): self
    {
        $this->elements[] = $element = new RichTextList();

        $callback($element);

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
            throw new LogicException('There must be at least one element in each rich text block.');
        }

        if (count($this->elements) > 10) {
            throw new LogicException('There is a maximum of 10 elements in each rich text block.');
        }

        $optionalFields = array_filter([
            'block_id' => $this->blockId,
        ]);

        return array_merge([
            'type' => 'rich_text',
            'elements' => array_map(fn (Arrayable $element) => $element->toArray(), $this->elements),
        ], $optionalFields);
    }
}
