<?php

    namespace App\Models;

class Paginator
{
    private $items;
    private $perPage;
    private $currentPage;
    private $pageParam;

    /**
     * Builds a paginator for an item list and query parameter name
     */
    public function __construct(array $items, int $perPage = 5, string $pageParam = 'page')
    {
            $this->items = $items;
            $this->perPage = $perPage;
            $this->pageParam = $pageParam;
            $this->currentPage = isset($_GET[$this->pageParam]) ? (int)$_GET[$this->pageParam] : 1;
    }

    /**
     * Returns items for the current page slice
     */
    public function getCurrentPageItems(): array
    {
        $start = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->items, $start, $this->perPage);
    }

    /**
     * Returns the total number of pages for current settings
     */
    public function getTotalPages(): int
    {
        return ceil(count($this->items) / $this->perPage);
    }

    /**
     * Builds a pagination url by updating the current page query parameter
     */
    private function buildPageUrl(int $page): string
    {
        $query = $_GET;
        $query[$this->pageParam] = $page;
        return '?' . http_build_query($query);
    }

    /**
     * Renders pagination links directly as HTML
     */
    public function renderLinks(): void
    {
        $totalPages = $this->getTotalPages();
        echo '<div class="pagination">';
        echo '<a href="' . $this->buildPageUrl(1) . '">Première page</a>';
        echo '<a href="' . $this->buildPageUrl(max(1, $this->currentPage - 1)) . '">Précédent</a>';

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i === $this->currentPage) {
                echo "<strong>$i</strong> ";
            } else {
                echo '<a href="' . $this->buildPageUrl($i) . '">' . $i . '</a> ';
            }
        }

        echo '<a href="' . $this->buildPageUrl(min($totalPages, $this->currentPage + 1)) . '">Suivant</a>';
        echo '<a href="' . $this->buildPageUrl($totalPages) . '">Dernière page</a>';
        echo '</div>';
    }
}
