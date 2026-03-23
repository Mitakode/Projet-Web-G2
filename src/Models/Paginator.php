<?php

    namespace App\Models;

class Paginator
{
    private $items;
    private $perPage;
    private $currentPage;


    public function __construct(array $items, int $perPage = 5)
    {
            $this->items = $items;
            $this->perPage = $perPage;
            $this->currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    }


    public function getCurrentPageItems(): array
    {
        $start = ($this->currentPage - 1) * $this->perPage;
        return array_slice($this->items, $start, $this->perPage);
    }


    public function getTotalPages(): int
    {
        return ceil(count($this->items) / $this->perPage);
    }


    public function renderLinks(): void
    {
        $totalPages = $this->getTotalPages();
        echo '<div class="pagination">';
        echo '<a href="?page=1">Première page</a>';
        echo '<a href="?page=' . max(1, $this->currentPage - 1) . '">Précédent</a>';

        for ($i = 1; $i <= $totalPages; $i++) {
            if ($i === $this->currentPage) {
                echo "<strong>$i</strong> ";
            } else {
                echo '<a href="?page=' . $i . '">' . $i . '</a> ';
            }
        }

        echo '<a href="?page=' . min($totalPages, $this->currentPage + 1) . '">Suivant</a>';
        echo '<a href="?page=' . $totalPages . '">Dernière page</a>';
        echo '</div>';
    }
}
