<?php

class CustomProductAttributes
{
    private array $attrs = [];
    private const TAX_CODE = "attributes_taxonomy";
    public function __construct()
    {
        $this->setAttrs();
        $this->register_attrs();
        add_action('save_post', fn($product_id, $product) => $this->registerSaveAttrs($product_id, $product), 10, 2);

    }
    protected function setAttrs()
    {
        $attributes = get_terms([
            'taxonomy' => static::TAX_CODE,
            'hide_empty' => false
        ]);
        $this->attrs = array_map(function ($attr) {
            
            ['attr_type' => $attr_type] = get_term_meta($attr->term_id);
            if ($attr_type)
                [$attr_type] = $attr_type;

            $attr->attr_code = $attr->slug;
            $attr->attr_type = $attr_type;
            return $attr;
        }, $attributes);
    }
    private function render($attr, $value)
    {

        switch ($attr->attr_type) {
            case 'text':
                ?>
                <tr>
                    <td><?= $attr->name ?></td>
                    <td><input type="text" value="<?= $value ?>" name="<?= $attr->attr_code ?>"></td>
                </tr>
                <?
                break;

            default:
                # code...
                break;
        }
    }
    private function displayAttrs($product)
    {
        ?>
        <table>
            <tr>
                <th>Атрибут</th>
                <th>Значение</th>
            </tr>
            <? foreach ($this->attrs as $i => $attr): ?>
                <?
                $value = get_post_meta($product->ID, $attr->attr_code, true);
                $this->render($attr, $value);
                ?>
            <? endforeach; ?>
        </table>
        <?php
    }
    private function register_attrs()
    {
        add_meta_box(
            'product_meta_box',
            'Атрибуты',
            fn($product) => $this->displayAttrs($product),
            'product',
            'normal',
            'high'
        );
        foreach ($this->attrs as $key => $attr) {
            add_meta_box(
                'product_meta_box',
                $attr->attr_code,
                // 'display_product_meta_box',
                'product',
                'normal',
                'high'
            );
        }
    }
    private function registerSaveAttrs($product_id, $product)
    {
        if ($product->post_type == 'product') {
            foreach ($this->attrs as $i => $attr) {
                if (isset($_POST[$attr->attr_code]) && $_POST[$attr->attr_code] != '') {
                    update_post_meta($product_id, $attr->attr_code, $_POST[$attr->attr_code]);
                }
            }
        }
    }
}
add_action('admin_init', function () {
    new CustomProductAttributes();
});


?>