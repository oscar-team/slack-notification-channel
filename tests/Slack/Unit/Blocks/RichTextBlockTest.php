<?php

namespace Illuminate\Tests\Notifications\Slack\Unit\Blocks;

use Illuminate\Notifications\Slack\BlockKit\Blocks\RichTextBlock;
use Illuminate\Notifications\Slack\BlockKit\Composites\RichTextSection;
use Illuminate\Tests\Notifications\Slack\TestCase;
use LogicException;

class RichTextBlockTest extends TestCase
{
    /** @test */
    public function is_is_arrayable()
    {
        $block = new RichTextBlock();
        $block->section(function (RichTextSection $section) {
            $section->text('Check out these different block types with paragraph breaks between them:\n\n');
        });

        $this->assertSame([
            'type' => 'rich_text',
            'elements' => [[
                'type' => 'rich_text_section',
                'elements' => [[
                    'type' => 'text',
                    'text' => 'Check out these different block types with paragraph breaks between them:\n\n',
                ]],
            ]],
        ], $block->toArray());
    }

    /** @test */
    public function it_throws_an_exception_when_no_element_was_provided()
    {
        $this->expectException(LogicException::class);
        $this->expectExceptionMessage('There must be at least one element in each rich text block.');

        $block = new RichTextBlock();

        $block->toArray();
    }
}
