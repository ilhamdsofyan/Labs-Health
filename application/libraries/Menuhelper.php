<?php

class Menuhelper
{
    public string $linkTemplate = '<a href="{url}" class="nav-link {active} {collapsed}" data-bs-toggle="{toggle}" data-bs-target="{target}" aria-expanded="{expanded}" aria-controls="{controls}">{icon}{label}{badge}{arrow}</a>';
    public string $labelTemplate = '{label}';
    public string $submenuTemplate = "<div class='collapse {show}' id='{id}' data-bs-parent='{parent}'>\n<nav class='sidenav-menu-nested nav {nested}'>\n{items}\n</nav>\n</div>";
    public string $headingTemplate = '<div class="sidenav-menu-heading">{label}</div>';
    public bool $activateParents = true;
    public string $defaultIconHtml = '<div class="nav-link-icon"><i data-feather="circle"></i></div>';
    public array $options = [
        "class" => "nav accordion",
        "id" => "accordionSidenav"
    ];
    public string $activeCssClass = 'active';

    private mixed $noDefaultAction = false;
    private mixed $noDefaultRoute = false;
    private array $group = [];
    private mixed $CI;

    protected array $items = [];
    protected string $route = '';
    protected array $params = [];

    public function __construct()
    {
        $this->CI = &get_instance();
        $this->CI->load->model(['rbac/menu']);
    }

    public function run(): void
    {
        $this->group = $this->CI->session->userdata('group_id') ?? [];
        $this->items = $this->CI->session->userdata('menus') ?? [];
        $this->route = $this->CI->helpers->getRoute() ?? '';
        $this->params = $this->CI->helpers->getQueryParams() ?? [];

        if ($this->items) {
            $hasActiveChild = false;
            $items = $this->normalizeItems($this->items, $hasActiveChild);
            if ($items) {
                $options = $this->options;
                $tag = $this->CI->helpers->arrayRemove($options, 'tag', 'div');
                echo Html::tag($tag, $this->renderItems($items), $options);
            }
        }
    }

    protected function renderItem(array $item, bool $active = false): string
    {
        $hasChildren = isset($item['items']);
        $id = 'collapse-' . md5($item['label']);
        $collapsed = $hasChildren && !$active ? 'collapsed' : '';

        $replacements = [
            '{label}' => strtr($this->labelTemplate, ['{label}' => $item['label'] ?? '']),
            '{icon}' => empty($item['icon']) ? $this->defaultIconHtml : '<div class="nav-link-icon"><i data-feather="' . $item['icon'] . '"></i></div>',
            '{url}' => !empty($item['url']) && $item['url'] != '#' ? site_url($item['url']) : 'javascript:void(0);',
            '{active}' => $active ? $this->activeCssClass : '',
            '{collapsed}' => $collapsed,
            '{toggle}' => $hasChildren ? 'collapse' : '',
            '{target}' => $hasChildren ? "#$id" : '',
            '{expanded}' => $active ? 'true' : 'false',
            '{controls}' => $hasChildren ? $id : '',
            '{arrow}' => $hasChildren ? '<div class="sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>' : '',
            '{badge}' => $item['badge'] ?? '',
        ];

        return strtr($this->linkTemplate, $replacements);
    }

    protected function renderItems(array $items, string $parent = '#accordionSidenav', int $level = 0): string
    {
        $lines = [];

        foreach ($items as $item) {
            // Handle heading items
            if (isset($item['type']) && $item['type'] === 'heading') {
                $lines[] = strtr($this->headingTemplate, ['{label}' => $item['label']]);
                continue;
            }

            if (!empty($item['assign']) && array_intersect($this->group, $item['assign'])) {
                $menu = $this->renderItem($item, $item['active'] ?? false);
                $liContent = $menu;

                if (!empty($item['items'])) {
                    $id = 'collapse-' . md5($item['label']);
                    $nestedClass = $level > 0 ? '' : 'accordion';
                    
                    $liContent .= strtr($this->submenuTemplate, [
                        '{id}' => $id,
                        '{show}' => $item['active'] ? 'show' : '',
                        '{parent}' => $parent,
                        '{items}' => $this->renderItems($item['items'], $id, $level + 1),
                        '{nested}' => $nestedClass,
                    ]);
                }

                $lines[] = $liContent;
            }
        }

        return implode("\n", $lines);
    }

    protected function normalizeItems(array $items, bool &$active): array
    {
        $result = [];

        foreach ($items as $item) {
            if (($item['visible'] ?? true) === false) {
                continue;
            }

            $item['label'] = $item['label'] ?? '';
            $item['icon'] = $item['icon'] ?? '';
            $encodeLabel = $item['encode'] ?? true;
            $item['label'] = $encodeLabel ? Html::encode($item['label']) : $item['label'];

            $hasActiveChild = false;
            if (isset($item['items'])) {
                $item['items'] = $this->normalizeItems($item['items'], $hasActiveChild);
                if (empty($item['items']) && ($this->hideEmptyItems ?? false)) {
                    if (!isset($item['url'])) continue;
                }
            }

            $item['active'] = $item['active'] ?? $this->isItemActive($item);
            if ($item['active']) $active = true;

            $result[] = $item;
        }

        return $result;
    }

    protected function isItemActive(array $item): bool
    {
        if (is_string($item['url'] ?? null)) {
            $this->urlToArray($item);
        }

        if (!isset($item['url'][0])) return false;

        $route = ltrim($item['url'][0], '/');

        if ($route !== $this->route && $route !== $this->noDefaultRoute && $route !== $this->noDefaultAction) {
            return false;
        }

        unset($item['url']['#']);

        if (count($item['url']) === 1 && !empty($this->params)) {
            return false;
        }

        foreach (array_slice($item['url'], 1) as $key => $value) {
            if ($value !== null && (!isset($this->params[$key]) || $this->params[$key] != $value)) {
                return false;
            }
        }

        return true;
    }

    protected function urlToArray(array &$item): void
    {
        $parseUrl = parse_url($item['url']);
        if (!$parseUrl) return;

        $item['url'] = [$parseUrl['path'] ?? '#'];

        if (!empty($parseUrl['query'])) {
            parse_str($parseUrl['query'], $params);
            $item['url'] += $params;
        }
    }
}
