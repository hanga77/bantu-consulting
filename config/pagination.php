<?php
class Pagination {
    private $current_page;
    private $total_items;
    private $items_per_page;
    private $total_pages;
    
    public function __construct($total_items, $items_per_page = 10, $current_page = 1) {
        $this->total_items = $total_items;
        $this->items_per_page = $items_per_page;
        $this->current_page = max(1, intval($current_page));
        $this->total_pages = ceil($total_items / $items_per_page);
        
        if ($this->current_page > $this->total_pages) {
            $this->current_page = $this->total_pages;
        }
    }
    
    public function getOffset() {
        return ($this->current_page - 1) * $this->items_per_page;
    }
    
    public function getLimit() {
        return $this->items_per_page;
    }
    
    public function getCurrentPage() {
        return $this->current_page;
    }
    
    public function getTotalPages() {
        return $this->total_pages;
    }
    
    public function render($section) {
        if ($this->total_pages <= 1) return '';
        
        $html = '<nav aria-label="Page navigation"><ul class="pagination justify-content-center">';
        
        // Bouton Précédent
        if ($this->current_page > 1) {
            $html .= '<li class="page-item"><a class="page-link" href="?page=admin-dashboard&section=' . $section . '&pagination_page=' . ($this->current_page - 1) . '">← Précédent</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">← Précédent</span></li>';
        }
        
        // Pages numérotées
        for ($i = 1; $i <= $this->total_pages; $i++) {
            if ($i == $this->current_page) {
                $html .= '<li class="page-item active"><span class="page-link">' . $i . '</span></li>';
            } else {
                $html .= '<li class="page-item"><a class="page-link" href="?page=admin-dashboard&section=' . $section . '&pagination_page=' . $i . '">' . $i . '</a></li>';
            }
        }
        
        // Bouton Suivant
        if ($this->current_page < $this->total_pages) {
            $html .= '<li class="page-item"><a class="page-link" href="?page=admin-dashboard&section=' . $section . '&pagination_page=' . ($this->current_page + 1) . '">Suivant →</a></li>';
        } else {
            $html .= '<li class="page-item disabled"><span class="page-link">Suivant →</span></li>';
        }
        
        $html .= '</ul></nav>';
        return $html;
    }
}
?>
