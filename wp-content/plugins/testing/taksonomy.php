<?php
class CustomTaksonomies
{
    const TAX_CODE_ATTRIBUTES = "attributes_taxonomy";
    const TAX_CODE_CATALOG = "catalog";
    private array $fields = [];
    public function __construct()
    {
        register_taxonomy(static::TAX_CODE_ATTRIBUTES, 'post', [
            'label' => __('Атрибуты', 'attributes'),
            'public' => true,
            'hierarchical' => false,
            'publicly_queryable' => false
        ]);
        register_taxonomy(static::TAX_CODE_CATALOG, 'product', [
            'label' => __('Каталог', 'catalog'),
            'public' => true,
            'hierarchical' => true,
            'rewrite' => [
                'slug' => 'catalog'
            ],
            'publicly_queryable' => true
        ]);
        add_action('init', 'custom_taxonomy_flush_rewrite');
        function custom_taxonomy_flush_rewrite()
        {
            global $wp_rewrite;
            $wp_rewrite->flush_rules();
        }
    }
    public function addField(CustomTaksonomyField $field)
    {
        $field->register();
        $this->fields[] = $field;
        return $this;
    }

}
abstract class CustomTaksonomyField
{
    protected string $field_code;
    private string $tax_name;
    protected string $field_name;
    public function __construct(string $field_code, string $field_name, string $tax_name)
    {
        $this->field_code = $field_code;
        $this->tax_name = $tax_name;
        $this->field_name = $field_name;
    }
    abstract public function AddFormFields($taxonomy);
    abstract public function EditFormFields($term, $taxonomy);
    abstract public function SaveFormFields($term_id);
    public function register()
    {
        add_action("{$this->tax_name}_add_form_fields", fn($taxonomy) => $this->AddFormFields($taxonomy));
        add_action("{$this->tax_name}_edit_form_fields", fn($term, $taxonomy) => $this->EditFormFields($term, $taxonomy), 10, 2);
        add_action("created_{$this->tax_name}", fn($term_id) => $this->SaveFormFields($term_id));
        add_action("edited_{$this->tax_name}", fn($term_id) => $this->SaveFormFields($term_id));

    }
}

class TaxCatCode extends CustomTaksonomyField
{

    /**
     * @inheritDoc
     */
    public function AddFormFields($taxonomy)
    {
        ?>
        <div class="form-field">
            <label for="<?= $this->field_code ?>"><?= $this->field_name ?></label>
            <input required type="text" name="<?= $this->field_code ?>" id="<?= $this->field_code ?>" />
        </div>
    <?
    }

    /**
     * @inheritDoc
     */
    public function EditFormFields($term, $taxonomy)
    {
        $value = get_term_meta($term->term_id, $this->field_code, true);

        ?>
        <tr class="form-field">
            <th>
                <label for="<?= $this->field_code ?>"><?= $this->field_name ?></label>
            </th>
            <td>
                <input required name="<?= $this->field_code ?>" id="<?= $this->field_code ?>" type="text"
                    value="<?= esc_attr($value) ?>" />
            </td>
        </tr>
    <?
    }
    /**
     * @inheritDoc
     */
    public function SaveFormFields($term_id)
    {
        if (isset($_POST[$this->field_code])) {
            update_term_meta($term_id, $this->field_code, sanitize_text_field($_POST[$this->field_code]));
        } else {
            // delete_term_meta($term_id, $this->field_code);
        }
    }
}
class TaxCatType extends CustomTaksonomyField
{

    private const OPTIONS = [
        'text' => 'Текст',
        'list' => 'Список',
        'color' => 'Палитра'
    ];
    /**
     * @inheritDoc
     */
    public function AddFormFields($taxonomy)
    {
        ?>
        <div class="form-field">
            <th>
                <label for="<?= $this->field_code ?>"><?= $this->field_name ?></label>
            </th>

            <td>
                <select required name="<?= $this->field_code ?>" id="<?= $this->field_code ?>">
                    <option value="">Не выбрано</option>
                    <? foreach (static::OPTIONS as $value => $name): ?>
                        <option value="<?= $value ?>"><?= $name ?></option>
                    <? endforeach; ?>
                </select>
            </td>
        </div>
    <?
    }

    /**
     * @inheritDoc
     */
    public function EditFormFields($term, $taxonomy)
    {
        $_value = get_term_meta($term->term_id, $this->field_code, true);
        ?>
        <div class="form-field">
            <th>
                <label for="<?= $this->field_code ?>"><?= $this->field_name ?></label>
            </th>

            <td>
                <select required name="<?= $this->field_code ?>" id="<?= $this->field_code ?>">
                    <option value="">Не выбрано</option>
                    <? foreach (static::OPTIONS as $value => $name): ?>
                        <option <? if ($_value == $value): ?> selected <? endif; ?> value="<?= $value ?>"><?= $name ?></option>
                    <? endforeach; ?>
                </select>
            </td>

        </div>
    <?
    }

    /**
     * @inheritDoc
     */
    public function SaveFormFields($term_id)
    {
        if (isset($_POST[$this->field_code])) {
            update_term_meta($term_id, $this->field_code, sanitize_text_field($_POST[$this->field_code]));
        } else {
            delete_term_meta($term_id, $this->field_code);
        }
    }
}

add_action('init', function () {
    $taxes = new CustomTaksonomies();
    $taxes
        ->addField(new TaxCatType('attr_type', 'Тип атрибута', CustomTaksonomies::TAX_CODE_ATTRIBUTES))
    ;
});



?>