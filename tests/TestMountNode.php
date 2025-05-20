<?php

namespace Bojaghi\ViteScripts\Tests;

use Bojaghi\ViteScripts\MountNode;
use \WP_UnitTestCase;

class TestMountNode extends WP_UnitTestCase
{
    /**
     * @param string $expected
     * @param array|string $args
     *
     * @return void
     *
     * @dataProvider _MountNodeProvider
     */
    public function test_MountNode(string $expected, array|string $args): void
    {
        ob_start();
        MountNode::render($args);
        $output = ob_get_clean();

        $this->assertEquals($expected, $output);
    }

    protected function _MountNodeProvider(): array
    {
        return [
            'Case #001' => [
                // Expected: default.
                '<div id="" class="" data-vite-scripts-root="true"></div>',
                // Input
                '',
            ],
            'Case #002' => [
                // Expected: args as string.
                '<div id="foo" class="bar" data-vite-scripts-root="true">inner content</div>',
                // Input
                'id=foo&class=bar&inner_content=inner content',
            ],
            'Case #003' => [
                // Expected: extra contents are removed due to wp_kses().
                '<div id="foo" class="bar baz bow" data-vite-scripts-root="true">inner content</div>',
                // Input
                [
                    'id'            => 'foo',
                    'class'         => 'bar baz bow',
                    'extra_attrs'   => [
                        'data-foo' => 'bar',
                        'title'    => 'baz',
                    ],
                    'inner_content' => '<p class="foo">inner content</p>',
                ],
            ],
            'Case #004' => [
                // Expected: extra contents are displayed, because 'extra_allowed_html' is proper.
                '<div id="foo" class="bar baz bow" data-vite-scripts-root="true" data-foo="bar" title="baz"><p class="foo">inner content</p></div>',
                // Input
                [
                    'id'                 => 'foo',
                    'class'              => 'bar baz bow',
                    'extra_allowed_html' => [
                        'div' => [
                            'data-foo' => true,
                            'title'    => true,
                        ],
                        'p'   => [
                            'class' => true,
                        ],
                    ],
                    'extra_attrs'        => [
                        'data-foo' => 'bar',
                        'title'    => 'baz',
                    ],
                    'inner_content'      => '<p class="foo">inner content</p>',
                ],
            ],
        ];
    }
}