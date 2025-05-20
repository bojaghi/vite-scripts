<?php

namespace Bojaghi\ViteScripts;

use Bojaghi\FieldsRender\Render as R;

/**
 * Helper for rendering the topmost mount node
 */
class MountNode
{
    public static function render(string|array $args = ''): void
    {
        $defaults = [
            'id'                 => '',
            'class'              => '',
            'extra_allowed_html' => [],
            'extra_attrs'        => [],
            'inner_content'      => '',
        ];

        $defaultAllowed = [
            'div' => [
                'id'                     => true,
                'class'                  => true,
                'data-vite-scripts-root' => true,
            ],
        ];

        $args = wp_parse_args($args, $defaults);

        $extraAttrs = $args['extra_attrs'];
        unset($extraAttrs['id'], $extraAttrs['class'], $extraAttrs['data-vite-scripts-root']);

        $attrs = array_merge([
            'id'                     => $args['id'],
            'class'                  => $args['class'],
            'data-vite-scripts-root' => 'true',
        ], $extraAttrs);

        $allowed        = array_merge($defaultAllowed, $args['extra_allowed_html']);
        $allowed['div'] = array_merge($allowed['div'], $defaultAllowed['div']); // Keep div's original attrs.

        echo wp_kses(R::open('div', $attrs) . $args['inner_content'] . R::close(), $allowed);
    }
}