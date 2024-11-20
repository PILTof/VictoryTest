<?php
/**
 * Template Name: Разделы каталога
 * Template Post Type: page, product, post
 */
$arSections = explode(';', $_SERVER['sections_slug']);
$prevSection = null;
$currentSectionSlug = end($arSections);

$currentSection = get_term_by('slug', $currentSectionSlug, CustomTaksonomies::TAX_CODE_CATALOG);

if (!isset($_REQUEST['ajax'])) {
    wp_head();
}
$needFilter = false;
if (isset($_REQUEST['ajax']) && ($_REQUEST['ajax'] == "Y")) {
    ob_clean();
    ob_start();
    $needFilter = isset($_REQUEST['ajax']) && ($_REQUEST['ajax'] == "Y") && ($_REQUEST['type'] == 'filter');
}

$cats = get_terms(array(
    'taxonomy' => CustomTaksonomies::TAX_CODE_CATALOG,
    'hide_empty' => false,
    'child_of' => $currentSection->term_id
));
$cats = array_map(function ($q) {
    $q->link = get_category_link($q->term_id);
    return $q;
}, $cats);

$postFilter = [
];

if ($needFilter) {
    $_filters = [];
    wp_reset_query();
    $loop = new WP_Query(array(
        'post_type' => 'product',
        'tax_query' => array(
            array(
                'taxonomy' => CustomTaksonomies::TAX_CODE_CATALOG,
                'field' => 'slug',
                'terms' => $currentSectionSlug,
            ),
        ),
    ));

    $posts = $loop->get_posts();
    $posts = array_map(function ($post) {
        $meta = get_post_meta($post->ID);
        $meta = array_filter($meta, fn($q) => !preg_match('/^_(.*)/', $q), ARRAY_FILTER_USE_KEY);
        $meta = array_map(fn($q) => reset($q), $meta);
        $post->meta = $meta;
        return $post;
    }, $posts);

    foreach ($posts as $key => $post) {
        foreach ($post->meta as $key => $value) {
            $term = get_term_by('slug', $key, CustomTaksonomies::TAX_CODE_ATTRIBUTES);
            if (array_key_exists($key, $_filters) && count(array_filter($_filters[$key]['values'], fn($q) => $q == $value)) > 0) {
                continue;
            }
            $_filters[$key]['name'] = $term->name ?? null;
            $_filters[$key]['values'][] = $value;

        }
    }

    $requested = array_filter($_REQUEST, fn($v, $k) => in_array($k, array_keys($_filters)) && boolval($v), ARRAY_FILTER_USE_BOTH);

    if (count(array_keys($requested)) > 0) {
        if (count(array_keys($requested)) > 1) {
            $postFilter = [
                'meta_query' => [
                    'relation' => 'AND'
                ]
            ];
            foreach ($requested as $key => $value) {
                $postFilter['meta_query'][] = [
                    'key' => $key,
                    'value' => $value
                ];
            }
        } else {
            $postFilter = [
                'meta_key' => array_key_first($requested),
                'meta_value' => reset($requested)
            ];
        }

    }
}


wp_reset_query();

$loop = new WP_Query(
    [
        ...[
            'post_type' => 'product',
            'tax_query' => [
                [
                    'taxonomy' => CustomTaksonomies::TAX_CODE_CATALOG,
                    'field' => 'slug',
                    'terms' => $currentSectionSlug,
                ],
            ],
        ],
        ...$postFilter
    ]
);

$posts = $loop->get_posts();
$posts = array_map(function ($post) {
    $meta = get_post_meta($post->ID);
    $meta = array_filter($meta, fn($q) => !preg_match('/^_(.*)/', $q), ARRAY_FILTER_USE_KEY);
    $meta = array_map(fn($q) => reset($q), $meta);
    $post->meta = $meta;
    return $post;
}, $posts);

$filters = [];

foreach ($posts as $key => $post) {
    foreach ($post->meta as $key => $value) {
        $term = get_term_by('slug', $key, CustomTaksonomies::TAX_CODE_ATTRIBUTES);
        if (array_key_exists($key, $filters) && count(array_filter($filters[$key]['values'], fn($q) => $q == $value)) > 0) {
            continue;
        }
        $filters[$key]['name'] = $term->name ?? null;
        $filters[$key]['values'][] = $value;

    }
}
$templatePath = str_replace(str_replace('/', '\\', $_SERVER['DOCUMENT_ROOT']), '', get_template_directory());




?>
<div data-entity="page-container" style="display:flex;justify-content:space-evenly;">
    <div>
        <div>
            <h2><?= $currentSection->name ?></h2>
            <p><a href="/catalog/">Каталог</a></p>
            <div style="display:flex;display: flex;column-gap: 10px;">
                <? foreach ($cats as $i => $cat): ?>
                    <div>
                        <a href="<?= $cat->link ?>"><?= $cat->name ?></a>
                    </div>
                <? endforeach; ?>
            </div>
        </div>
        <form id="filter" action="<?= admin_url("admin-ajax.php") ?>" method="POST">
            <input type="hidden" value="<?= $_SERVER['sections_slug'] ?>" name="sections_slug">
            <input type="hidden" name="action" value="post_filter_category">
            <div style="padding: 5px;border: 1px solid;margin-top: 10px;">
                <h3>Фильтры</h3>
                <div style="display:flex;display: flex;column-gap: 10px;flex-direction:column;row-gap:20px;">
                    <? foreach ($filters as $slug => $filter): ?>
                        <div style="display: flex;flex-direction: column;row-gap: 10px;">
                            <span><?= $filter['name'] ?? "Фильтр $slug" ?></span>
                            <div style="display:flex;flex-direction:column;row-gap:5px;">
                                <select name="<?= $slug ?>" id="<?= $slug ?>">
                                    <option value="">Не выбрано</option>
                                    <? foreach ($filter['values'] as $j => $value): ?>
                                        <!-- <div style="display: flex;align-items: center;justify-content: flex-start;"> -->
                                        <option <? if ($needFilter && ($_REQUEST[$slug] == $value)): ?> selected <? endif; ?>
                                            value="<?= $value ?>"><?= $value ?></option>
                                        <!-- <input type="checkbox" name="<?= $slug ?>[]" value="<?= $value ?>" id=""> -->
                                        <!-- <label for="<?= $value ?>"><?= $value ?></label> -->
                                        <!-- </div> -->
                                    <? endforeach; ?>
                                </select>
                            </div>
                        </div>
                    <? endforeach; ?>
                </div>
                <div style="padding:10px;">
                    <input type="submit" data-type="filter" value="Отфильтровать" />
                    <input type="submit" data-type="reset" value="Сбросить">
                </div>
            </div>
        </form>
    </div>

    <div style="display:flex;row-gap:20px;align-items:center;padding:20px;flex-direction: column;">
        <? foreach ($posts as $i => $post): ?>
            <?
            setup_postdata($post);
            ?>
            <div
                style="display:flex;column-gap: 20px;justify-content: space-between;    padding: 5px;border: 1px solid black;    min-width: 500px;">
                <div>
                    <a href="<?= the_permalink() ?>"><?= $post->post_title ?></a>
                    <p><?= $post->post_content ?></p>
                </div>
                <div style="min-width: 150px;">
                    <div>Атрибуты</div>
                    <div style="display:flex;column-gap: 10px;">
                        <? foreach ($post->meta as $i => $value): ?>
                            <div>
                                <div><?= $i ?>:</div>
                                <div><?= $value ?></div>
                            </div>
                        <? endforeach; ?>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
    </div>
</div>

<?php wp_footer(); ?>