<?php

namespace App\Libraries;

class PaginationHandle {

    private $total;
    private $page;
    private $limit;
    private $numLinks;
    private $url;
    private $textFirst;
    private $textLast;
    private $textNext;
    private $textPrev;
    private $htmlStart;
    private $htmlEnd;
    private $htmlItemStart;
    private $htmlItemEnd;
    private $htmlItemActiveStart;
    private $htmlItemActiveEnd;

    public function init($parameter = ['total' => 0, 'page' => 1, 'limit' => 20, 'num_links' => 8, 'url' => '', 'text_first' => '|&lt;', 'text_last' => '&gt;|', 'text_prev' => '&lt;', 'text_next' => '&gt;',], $html = []) {

        $this->total = $parameter['total'];
        $this->page = $parameter['page'];
        $this->limit = $parameter['limit'];
        $this->numLinks = !isset($parameter['num_links']) ? 8 : $parameter['num_links'];
        $this->url = !isset($parameter['url']) ? '' : $parameter['url'];
        $this->textFirst = !isset($parameter['text_first']) ? '|&lt;' : $parameter['text_first'];
        $this->textLast = !isset($parameter['text_last']) ? '&gt;|' : $parameter['text_last'];
        $this->textPrev = !isset($parameter['text_prev']) ? '&lt;' : $parameter['text_prev'];
        $this->textNext = !isset($parameter['text_next']) ? '&gt;' : $parameter['text_next'];

        $this->htmlStart = !isset($html['html_start']) ? '<ul class="pagination">' : $html['html_start'];
        $this->htmlEnd = !isset($html['html_end']) ? '</ul>' : $html['html_end'];
        $this->htmlItemStart = !isset($html['html_item_start']) ? '<li>' . '' : $html['html_item_start'];
        $this->htmlItemEnd = !isset($html['html_item_end']) ? '' . '</li>' : $html['html_item_end'];
        $this->htmlItemActiveStart = !isset($html['html_item_active_start']) ? '<li class="active">' : $html['html_item_active_start'];
        $this->htmlItemActiveEnd = !isset($html['html_item_active_end']) ? '' . '</li>' : $html['html_item_active_end'];
    }

    public function render() {
        $total = $this->total;
        $page = $this->page;
        if ($this->page < 1) {
            $page = 1;
        }
        $limit = $this->limit;
        if (!(int)$this->limit) {
            $limit = 10;
        }
        $numLinks = $this->numLinks;
        $numPages = ceil($total / $limit);

        $this->url = str_replace('%7Bpage%7D', '{page}', $this->url);

        $output = $this->htmlStart;

        if ($page > 1) {
            $output .= $this->htmlItemStart . '<a href="' . str_replace(['/page/{page}', '&amp;page={page}', '?page={page}', '&page={page}'], '', $this->url) . '">' . $this->textFirst . '</a>' . $this->htmlItemEnd;

            if ($page - 1 === 1) {
                $output .= $this->htmlItemStart . '<a href="' . str_replace(['/page/{page}', '&amp;page={page}', '?page={page}', '&page={page}'], '', $this->url) . '">' . $this->textPrev . '</a>' . $this->htmlItemEnd;
            } else {
                $output .= $this->htmlItemStart . '<a href="' . str_replace('{page}', $page - 1, $this->url) . '">' . $this->textPrev . '</a>' . $this->htmlItemEnd;
            }
        }

        if ($numPages > 1) {
            if ($numPages <= $numLinks) {
                $start = 1;
                $end = $numPages;
            } else {
                $start = $page - floor($numLinks / 2);
                $end = $page + floor($numLinks / 2);

                if ($start < 1) {
                    $end += abs($start) + 1;
                    $start = 1;
                }

                if ($end > $numPages) {
                    $start -= ($end - $numPages);
                    $end = $numPages;
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                if ($page == $i) {
                    $output .= $this->htmlItemActiveStart . '<span>' . $i . '</span>' . $this->htmlItemActiveEnd;
                } else {
                    if ($i === 1) {
                        $output .= $this->htmlItemStart . '<a href="' . str_replace(['/page/{page}', '&amp;page={page}', '?page={page}', '&page={page}'], '', $this->url) . '">' . $i . '</a>' . $this->htmlItemEnd;
                    } else {
                        $output .= $this->htmlItemStart . '<a href="' . str_replace('{page}', $i, $this->url) . '">' . $i . '</a>' . $this->htmlItemEnd;
                    }
                }
            }
        }

        if ($page < $numPages) {
            $output .= $this->htmlItemStart . '<a href="' . str_replace('{page}', $page + 1, $this->url) . '">' . $this->textNext . '</a>' . $this->htmlItemEnd;
            $output .= $this->htmlItemStart . '<a href="' . str_replace('{page}', $numPages, $this->url) . '">' . $this->textLast . '</a>' . $this->htmlItemEnd;
        }

        $output .= $this->htmlEnd;

        if ($numPages > 1) {
            return $output;
        } else {
            return '';
        }
    }
}
